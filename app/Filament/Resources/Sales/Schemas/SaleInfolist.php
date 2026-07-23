<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Enums\PaymentMethod;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Venta')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('number')
                            ->label('Número'),
                        TextEntry::make('paid_at')
                            ->label('Fecha de cobro')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('payment_method')
                            ->label('Método de pago')
                            ->formatStateUsing(fn (PaymentMethod|string $state): string => $state instanceof PaymentMethod
                                ? $state->label()
                                : PaymentMethod::from($state)->label()),
                        TextEntry::make('user.name')
                            ->label('Cobrada por')
                            ->placeholder('—'),
                        TextEntry::make('order.number')
                            ->label('Pedido origen')
                            ->placeholder('—'),
                        TextEntry::make('total')
                            ->label('Total')
                            ->money('ARS'),
                        TextEntry::make('notes')
                            ->label('Notas')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),
                Section::make('Ítems')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('product_name')
                                    ->label('Producto'),
                                TextEntry::make('product_sku')
                                    ->label('SKU')
                                    ->placeholder('—'),
                                TextEntry::make('quantity')
                                    ->label('Cant.'),
                                TextEntry::make('unit_price')
                                    ->label('Precio')
                                    ->money('ARS'),
                                TextEntry::make('line_total')
                                    ->label('Subtotal')
                                    ->money('ARS'),
                            ])
                            ->columns(5),
                    ]),
            ]);
    }
}
