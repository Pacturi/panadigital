<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Attribute;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return self::configureCreate($schema);
    }

    public static function configureCreate(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                self::productSection(),
                self::attributesSection(),
                Section::make('Inventario')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('price')
                            ->label('Precio')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->required(),
                        TextInput::make('stock')
                            ->label('Stock')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        Toggle::make('active')
                            ->label('Activo')
                            ->default(true)
                            ->inline(false),
                        self::imageField(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function configureEdit(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([
                                self::imageField()
                                    ->imagePreviewHeight('280')
                                    ->panelLayout('integrated')
                                    ->imageEditor()
                                    ->openable()
                                    ->downloadable(),
                                Group::make()
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Precio')
                                            ->numeric()
                                            ->prefix('$')
                                            ->minValue(0)
                                            ->required()
                                            ->extraInputAttributes(['class' => 'text-2xl font-semibold']),
                                        TextInput::make('stock')
                                            ->label('Stock')
                                            ->numeric()
                                            ->minValue(0)
                                            ->required()
                                            ->extraInputAttributes(['class' => 'text-2xl font-semibold']),
                                    ]),
                            ]),
                    ]),
                self::productSection(),
                self::attributesSection(),
                Section::make('Inventario')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Toggle::make('active')
                            ->label('Activo')
                            ->default(true)
                            ->inline(false),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3),
                    ]),
            ]);
    }

    private static function imageField(): FileUpload
    {
        return FileUpload::make('image')
            ->label('Imagen')
            ->image()
            ->disk('public')
            ->directory('products')
            ->visibility('public');
    }

    private static function productSection(): Section
    {
        return Section::make('Producto')
            ->columnSpanFull()
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, Set $set): void {
                        $set('slug', filled($state) ? Str::slug($state) : null);
                    }),
                Hidden::make('slug'),
                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->native(false)
                    ->afterStateUpdated(function (?int $state, Set $set): void {
                        $set('attribute_values', self::emptyValuesForCategory($state));
                    }),
                Select::make('brand_gallery_item_id')
                    ->label('Marca')
                    ->relationship(
                        name: 'brandGalleryItem',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->brands(),
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->native(false)
                    ->helperText('Opcional. Solo logos de marca de la galería global.'),
            ]);
    }

    private static function attributesSection(): Section
    {
        return Section::make('Atributos')
            ->columnSpanFull()
            ->description('Se muestran según la categoría seleccionada.')
            ->visible(fn (Get $get): bool => filled($get('category_id')))
            ->schema([
                Group::make()
                    ->statePath('attribute_values')
                    ->schema(self::allAttributeFields()),
            ]);
    }

    /**
     * @return array<string, null>
     */
    public static function emptyValuesForCategory(?int $categoryId): array
    {
        if (! $categoryId) {
            return [];
        }

        return Category::query()
            ->with('attributes')
            ->find($categoryId)
            ?->attributes
            ->mapWithKeys(fn (Attribute $attribute): array => [$attribute->slug => null])
            ->all()
            ?? [];
    }

    /**
     * @return array<int, Select|TextInput>
     */
    private static function allAttributeFields(): array
    {
        return Attribute::query()
            ->with('options')
            ->orderBy('name')
            ->get()
            ->map(function (Attribute $attribute) {
                $field = match ($attribute->type) {
                    'select' => Select::make($attribute->slug)
                        ->options($attribute->options->pluck('value', 'value')->all())
                        ->native(false)
                        ->searchable(),
                    'number' => TextInput::make($attribute->slug)->numeric(),
                    default => TextInput::make($attribute->slug),
                };

                return $field
                    ->label($attribute->name)
                    ->visible(fn (Get $get): bool => self::categoryHasAttribute($get, $attribute->id))
                    ->required(fn (Get $get): bool => self::attributeIsRequired($get, $attribute->id))
                    ->dehydrated(fn (Get $get): bool => self::categoryHasAttribute($get, $attribute->id));
            })
            ->all();
    }

    private static function categoryHasAttribute(Get $get, int $attributeId): bool
    {
        return self::attributesForCategory($get)->has($attributeId);
    }

    private static function attributeIsRequired(Get $get, int $attributeId): bool
    {
        $attribute = self::attributesForCategory($get)->get($attributeId);

        return (bool) ($attribute?->pivot?->required ?? false);
    }

    /**
     * @return Collection<int, Attribute>
     */
    private static function attributesForCategory(Get $get): Collection
    {
        $categoryId = $get('../../category_id')
            ?? $get('../category_id')
            ?? $get('category_id');

        if (! $categoryId) {
            return collect();
        }

        /** @var array<int, Collection<int, Attribute>> $cache */
        static $cache = [];

        return $cache[(int) $categoryId] ??= Category::query()
            ->with('attributes')
            ->find((int) $categoryId)
            ?->attributes
            ->keyBy('id')
            ?? collect();
    }
}
