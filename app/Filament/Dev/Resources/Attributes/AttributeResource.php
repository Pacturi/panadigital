<?php

namespace App\Filament\Dev\Resources\Attributes;

use App\Filament\Dev\Resources\Attributes\Pages\CreateAttribute;
use App\Filament\Dev\Resources\Attributes\Pages\EditAttribute;
use App\Filament\Dev\Resources\Attributes\Pages\ListAttributes;
use App\Filament\Dev\Resources\Attributes\Schemas\AttributeForm;
use App\Filament\Dev\Resources\Attributes\Tables\AttributesTable;
use App\Models\Attribute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Atributos';

    protected static ?string $modelLabel = 'atributo';

    protected static ?string $pluralModelLabel = 'atributos';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttributes::route('/'),
            'create' => CreateAttribute::route('/create'),
            'edit' => EditAttribute::route('/{record}/edit'),
        ];
    }
}
