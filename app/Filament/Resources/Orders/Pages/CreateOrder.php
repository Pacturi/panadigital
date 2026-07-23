<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Filament\Resources\Orders\Actions\ChargeOrderAction;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Sales\SaleResource;
use App\Models\Order;
use App\Models\Sale;
use App\Services\CompleteOrder;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    /** @var array<string, mixed> */
    protected array $chargePayload = [];

    protected ?Sale $createdSale = null;

    public function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema, showChargeNow: true);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenantId = (int) Filament::getTenant()?->getKey();

        $this->chargePayload = [
            'charge_now' => (bool) data_get($this->data, 'charge_now', false),
            'payment_method' => data_get($this->data, 'payment_method', PaymentMethod::Cash->value),
        ];

        $data['tenant_id'] = $tenantId;
        $data['user_id'] = auth()->id();
        $data['number'] = Order::generateNumber($tenantId);
        $data['status'] = OrderStatus::Draft;
        $data['subtotal'] = 0;
        $data['total'] = 0;

        unset($data['charge_now'], $data['payment_method']);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Order $order */
        $order = $this->record;
        $order->recalculateTotals();
        $order->refresh()->load('items');

        if (($this->chargePayload['charge_now'] ?? false) !== true) {
            return;
        }

        $this->createdSale = ChargeOrderAction::complete(
            $order,
            [
                'payment_method' => $this->chargePayload['payment_method'] ?? PaymentMethod::Cash->value,
                'notes' => $order->notes,
            ],
            app(CompleteOrder::class),
        );
    }

    protected function getRedirectUrl(): string
    {
        if ($this->createdSale) {
            return SaleResource::getUrl('view', ['record' => $this->createdSale]);
        }

        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        if ($this->createdSale) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title('Pedido guardado')
            ->body('Cuando el cliente pague, usá “Cobrar y generar venta” (arriba, abajo o desde el listado).')
            ->persistent();
    }
}
