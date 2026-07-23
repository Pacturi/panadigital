<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\Actions\ChargeOrderAction;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Models\Order;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema, showChargeNow: false);
    }

    protected function getHeaderActions(): array
    {
        return [
            ChargeOrderAction::makeForPage(fn (): Order => $this->getOrderRecord(), 'charge_header')
                ->before(function (): void {
                    $this->save(shouldRedirect: false, shouldSendSavedNotification: false);
                    $this->record->refresh()->load('items');
                }),
            DeleteAction::make()
                ->visible(fn (): bool => $this->record instanceof Order && $this->record->isDraft()),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Guardar cambios'),
            ChargeOrderAction::makeForPage(fn (): Order => $this->getOrderRecord(), 'charge_footer')
                ->before(function (): void {
                    $this->save(shouldRedirect: false, shouldSendSavedNotification: false);
                    $this->record->refresh()->load('items');
                }),
            $this->getCancelFormAction()
                ->label('Volver'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pedido actualizado')
            ->body('Cuando el cliente pague, usá “Cobrar y generar venta”.');
    }

    protected function afterSave(): void
    {
        /** @var Order $order */
        $order = $this->record;
        $order->recalculateTotals();
        $this->record->refresh();
    }

    protected function authorizeAccess(): void
    {
        parent::authorizeAccess();

        abort_unless($this->record instanceof Order && $this->record->isDraft(), 403);
    }

    private function getOrderRecord(): Order
    {
        /** @var Order $order */
        $order = $this->record;

        return $order;
    }
}
