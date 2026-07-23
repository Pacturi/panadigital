<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'product_sku',
        'unit_price',
        'quantity',
        'line_total',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity' => 'integer',
            'line_total' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrderItem $item): void {
            if ($item->product_id && blank($item->product_name)) {
                $product = $item->product ?? Product::query()->find($item->product_id);

                if ($product) {
                    $item->product_name = $product->name;
                    $item->product_sku = $product->sku;
                }
            }

            $item->line_total = round((float) $item->unit_price * (int) $item->quantity, 2);
        });

        static::saved(function (OrderItem $item): void {
            $item->order?->recalculateTotals();
        });

        static::deleted(function (OrderItem $item): void {
            $item->order?->recalculateTotals();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
