<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Actions\ChargeOrderAction;
use App\Filament\Resources\Sales\SaleResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Número')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label())
                    ->color(fn (OrderStatus $state): string => $state->color()),
                TextColumn::make('items_count')
                    ->label('Ítems')
                    ->counts('items')
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Creado por')
                    ->toggleable()
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->label('Cobrado')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(
                        fn (OrderStatus $status): array => [$status->value => $status->label()]
                    )->all())
                    ->default(OrderStatus::Draft->value),
            ])
            ->recordActions([
                ChargeOrderAction::make(),
                EditAction::make()
                    ->visible(fn (Order $record): bool => $record->isDraft()),
                Action::make('viewSale')
                    ->label('Ver venta')
                    ->icon('heroicon-o-banknotes')
                    ->url(fn (Order $record): ?string => $record->sale
                        ? SaleResource::getUrl('view', ['record' => $record->sale])
                        : null)
                    ->visible(fn (Order $record): bool => $record->isPaid()),
                Action::make('cancel')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar pedido')
                    ->modalDescription('El pedido quedará cancelado y no se podrá cobrar.')
                    ->visible(fn (Order $record): bool => $record->isDraft())
                    ->action(function (Order $record): void {
                        $record->forceFill([
                            'status' => OrderStatus::Cancelled,
                            'cancelled_at' => now(),
                        ])->save();

                        Notification::make()
                            ->title('Pedido cancelado')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records): void {
                            $deleted = 0;

                            foreach ($records as $record) {
                                if ($record instanceof Order && $record->isDraft()) {
                                    $record->delete();
                                    $deleted++;
                                }
                            }

                            if ($deleted === 0) {
                                throw ValidationException::withMessages([
                                    'records' => 'Solo se pueden eliminar pedidos en borrador.',
                                ]);
                            }

                            Notification::make()
                                ->title("Se eliminaron {$deleted} pedidos en borrador")
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}
