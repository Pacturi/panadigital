<?php

namespace App\Filament\Resources\Orders\Actions;

use App\Enums\PaymentMethod;
use App\Filament\Resources\Sales\SaleResource;
use App\Models\Order;
use App\Models\Sale;
use App\Services\CompleteOrder;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class ChargeOrderAction
{
    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public static function formSchema(): array
    {
        return [
            Select::make('payment_method')
                ->label('Método de pago')
                ->options(PaymentMethod::options())
                ->required()
                ->native(false)
                ->default(PaymentMethod::Cash->value)
                ->helperText('Elegí cómo abona el cliente (efectivo, transferencia, etc.).'),
            Textarea::make('notes')
                ->label('Notas del cobro')
                ->rows(2),
        ];
    }

    public static function make(string $name = 'charge'): Action
    {
        return Action::make($name)
            ->label('Cobrar')
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->button()
            ->form(self::formSchema())
            ->modalHeading(fn (Order $record): string => 'Cobrar pedido '.$record->number)
            ->modalDescription(fn (Order $record): string => 'Total: $ '.number_format((float) $record->total, 2, ',', '.').'. Se descontará el stock y se generará la venta.')
            ->modalSubmitActionLabel('Confirmar cobro')
            ->visible(fn (Order $record): bool => $record->isDraft())
            ->disabled(fn (Order $record): bool => ((int) ($record->items_count ?? $record->items()->count()) === 0) || (float) $record->total <= 0)
            ->action(function (Order $record, array $data, CompleteOrder $completeOrder): void {
                $sale = self::complete($record, $data, $completeOrder);

                if ($sale) {
                    redirect(SaleResource::getUrl('view', ['record' => $sale]));
                }
            });
    }

    /**
     * @param  callable(): Order  $getOrder
     */
    public static function makeForPage(callable $getOrder, string $name = 'charge'): Action
    {
        return Action::make($name)
            ->label('Cobrar y generar venta')
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->size('lg')
            ->button()
            ->form(self::formSchema())
            ->modalHeading(fn (): string => 'Cobrar pedido '.$getOrder()->number)
            ->modalDescription(fn (): string => 'Total: $ '.number_format((float) $getOrder()->total, 2, ',', '.').'. Se descontará el stock y se generará la venta.')
            ->modalSubmitActionLabel('Confirmar cobro')
            ->visible(fn (): bool => $getOrder()->isDraft())
            ->disabled(fn (): bool => $getOrder()->items()->count() === 0 || (float) $getOrder()->total <= 0)
            ->action(function (array $data, CompleteOrder $completeOrder) use ($getOrder): void {
                $sale = self::complete($getOrder()->fresh(['items']) ?? $getOrder(), $data, $completeOrder);

                if ($sale) {
                    redirect(SaleResource::getUrl('view', ['record' => $sale]));
                }
            });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function complete(Order $order, array $data, CompleteOrder $completeOrder): ?Sale
    {
        try {
            $sale = $completeOrder->handle(
                order: $order,
                paymentMethod: PaymentMethod::from($data['payment_method']),
                notes: $data['notes'] ?? null,
                userId: auth()->id(),
            );
        } catch (ValidationException $exception) {
            Notification::make()
                ->title('No se pudo cobrar')
                ->body(collect($exception->errors())->flatten()->first())
                ->danger()
                ->send();

            return null;
        }

        Notification::make()
            ->title('Venta registrada')
            ->body('Venta '.$sale->number.' por $ '.number_format((float) $sale->total, 2, ',', '.'))
            ->success()
            ->send();

        return $sale;
    }
}
