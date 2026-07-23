<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompleteOrder
{
    public function handle(Order $order, PaymentMethod $paymentMethod, ?string $notes = null, ?int $userId = null): Sale
    {
        return DB::transaction(function () use ($order, $paymentMethod, $notes, $userId): Sale {
            /** @var Order $order */
            $order = Order::query()
                ->whereKey($order->getKey())
                ->lockForUpdate()
                ->with('items')
                ->firstOrFail();

            if (! $order->isDraft()) {
                throw ValidationException::withMessages([
                    'order' => 'Solo se pueden cobrar pedidos en borrador.',
                ]);
            }

            if ($order->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'order' => 'El pedido no tiene productos.',
                ]);
            }

            $order->recalculateTotals();
            $order->refresh();

            foreach ($order->items as $item) {
                $this->assertAndDecrementStock($item->product_id, $item->product_variant_id, (int) $item->quantity);
            }

            $paidAt = now();

            $sale = Sale::query()->create([
                'tenant_id' => $order->tenant_id,
                'order_id' => $order->id,
                'user_id' => $userId ?? auth()->id(),
                'number' => Sale::generateNumber((int) $order->tenant_id),
                'payment_method' => $paymentMethod,
                'subtotal' => $order->subtotal,
                'total' => $order->total,
                'paid_at' => $paidAt,
                'notes' => $notes,
            ]);

            foreach ($order->items as $item) {
                SaleItem::query()->create([
                    'sale_id' => $sale->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product_name,
                    'product_sku' => $item->product_sku,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->line_total,
                ]);
            }

            $order->forceFill([
                'status' => OrderStatus::Paid,
                'paid_at' => $paidAt,
                'notes' => filled($notes) ? $notes : $order->notes,
            ])->save();

            return $sale->load('items');
        });
    }

    private function assertAndDecrementStock(?int $productId, ?int $variantId, int $quantity): void
    {
        if ($variantId) {
            /** @var ProductVariant $variant */
            $variant = ProductVariant::query()
                ->whereKey($variantId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($variant->stock < $quantity) {
                throw ValidationException::withMessages([
                    'stock' => "Stock insuficiente para la variante del producto #{$variant->product_id}. Disponible: {$variant->stock}.",
                ]);
            }

            $variant->decrement('stock', $quantity);

            return;
        }

        /** @var Product $product */
        $product = Product::query()
            ->whereKey($productId)
            ->lockForUpdate()
            ->firstOrFail();

        if ($product->stock < $quantity) {
            throw ValidationException::withMessages([
                'stock' => "Stock insuficiente para \"{$product->name}\". Disponible: {$product->stock}.",
            ]);
        }

        $product->decrement('stock', $quantity);
    }
}
