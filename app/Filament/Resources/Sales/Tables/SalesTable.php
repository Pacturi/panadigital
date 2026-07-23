<?php

namespace App\Filament\Resources\Sales\Tables;

use App\Enums\PaymentMethod;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SalesTable
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
                TextColumn::make('order.number')
                    ->label('Pedido')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('payment_method')
                    ->label('Pago')
                    ->badge()
                    ->formatStateUsing(fn (PaymentMethod $state): string => $state->label())
                    ->color('gray'),
                TextColumn::make('items_count')
                    ->label('Ítems')
                    ->counts('items'),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Cobrada por')
                    ->toggleable()
                    ->placeholder('—'),
                TextColumn::make('paid_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('paid_at', 'desc')
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Método de pago')
                    ->options(PaymentMethod::options()),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
