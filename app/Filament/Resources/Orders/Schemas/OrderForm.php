<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\PaymentMethod;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class OrderForm
{
    public static function configure(Schema $schema, bool $showChargeNow = false): Schema
    {
        $components = [
            Section::make('Ítems del pedido')
                ->description('Agregá o quitá productos antes de cobrar. El stock se descuenta recién al cobrar.')
                ->icon(Heroicon::OutlinedShoppingCart)
                ->columnSpanFull()
                ->schema([
                    Repeater::make('items')
                        ->label('Productos')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                                ->label('Producto')
                                ->options(fn (): array => Product::query()
                                    ->where('tenant_id', Filament::getTenant()?->getKey())
                                    ->where('active', true)
                                    ->orderBy('name')
                                    ->get()
                                    ->mapWithKeys(fn (Product $product): array => [
                                        $product->id => $product->name.(filled($product->sku) ? " ({$product->sku})" : '').' — stock '.$product->stock,
                                    ])
                                    ->all())
                                ->searchable()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (?int $state, Set $set): void {
                                    $product = $state ? Product::query()->find($state) : null;

                                    $set('unit_price', $product?->price ?? 0);
                                    $set('product_name', $product?->name ?? '');
                                    $set('product_sku', $product?->sku);
                                    $set('quantity', 1);
                                })
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->columnSpan(2),
                            Hidden::make('product_name')
                                ->dehydrated(),
                            Hidden::make('product_sku')
                                ->dehydrated(),
                            TextInput::make('quantity')
                                ->label('Cant.')
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->required()
                                ->live(debounce: 300),
                            TextInput::make('unit_price')
                                ->label('Precio')
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0)
                                ->required()
                                ->live(debounce: 300),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->addActionLabel('Agregar producto')
                        ->reorderable(false)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['product_name'] ?? null)
                        ->live()
                        ->minItems(1)
                        ->required(),
                ]),
            Section::make('Total estimado')
                ->description('Se actualiza al cambiar productos, cantidades o precios.')
                ->icon(Heroicon::OutlinedBanknotes)
                ->iconColor('primary')
                ->columnSpanFull()
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 2,
                    ])->schema([
                        TextEntry::make('items_count_preview')
                            ->label('Productos')
                            ->state(fn (Get $get): string => (string) self::itemsSummary($get)['count'])
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('gray')
                            ->extraAttributes([
                                'class' => 'text-3xl md:text-4xl tracking-tight',
                            ]),
                        TextEntry::make('total_preview')
                            ->label('Total a cobrar')
                            ->state(fn (Get $get): string => '$ '.number_format(self::itemsSummary($get)['total'], 2, ',', '.'))
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->extraAttributes([
                                'class' => 'text-3xl md:text-4xl tracking-tight',
                            ]),
                    ]),
                ]),
        ];

        if ($showChargeNow) {
            $components[] = Section::make('Cobro')
                ->description('Si el cliente ya está pagando, marcá “Cobrar ahora”. Si no, guardá el pedido y cobralo después.')
                ->icon(Heroicon::OutlinedCreditCard)
                ->columnSpanFull()
                ->schema([
                    Toggle::make('charge_now')
                        ->label('Cobrar ahora')
                        ->helperText('Al guardar se pedirá el método de pago y se generará la venta.')
                        ->live()
                        ->dehydrated(false)
                        ->default(false),
                    Select::make('payment_method')
                        ->label('Método de pago')
                        ->options(PaymentMethod::options())
                        ->native(false)
                        ->default(PaymentMethod::Cash->value)
                        ->required(fn (Get $get): bool => (bool) $get('charge_now'))
                        ->visible(fn (Get $get): bool => (bool) $get('charge_now'))
                        ->dehydrated(false),
                ]);
        } else {
            $components[] = Section::make('Finalizar pedido')
                ->description('Cuando el cliente pague, usá el botón verde “Cobrar y generar venta” (arriba o abajo). Ahí elegís efectivo, transferencia, etc.')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('charge_hint')
                        ->label('Estado')
                        ->state('Pedido en borrador — editable. El cobro aún no se registró.')
                        ->badge()
                        ->color('warning'),
                ]);
        }

        $components[] = Section::make('Notas')
            ->description('Opcional. Solo para uso interno del local.')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->columnSpanFull()
            ->collapsed()
            ->schema([
                Textarea::make('notes')
                    ->label('Notas internas')
                    ->rows(3)
                    ->placeholder('Ej: cliente pidió bolsa, vuelve a buscar más tarde…')
                    ->columnSpanFull(),
            ]);

        return $schema->components($components);
    }

    /**
     * @return array{count: int, total: float}
     */
    private static function itemsSummary(Get $get): array
    {
        $items = collect($get('items') ?? [])
            ->filter(fn (mixed $item): bool => is_array($item) && filled($item['product_id'] ?? null));

        return [
            'count' => $items->sum(fn (array $item): int => (int) ($item['quantity'] ?? 0)),
            'total' => (float) $items->sum(
                fn (array $item): float => (float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 0)
            ),
        ];
    }
}
