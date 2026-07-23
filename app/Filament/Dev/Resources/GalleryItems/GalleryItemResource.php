<?php

namespace App\Filament\Dev\Resources\GalleryItems;

use App\Filament\Dev\Resources\GalleryItems\Pages\CreateGalleryItem;
use App\Filament\Dev\Resources\GalleryItems\Pages\EditGalleryItem;
use App\Filament\Dev\Resources\GalleryItems\Pages\ListGalleryItems;
use App\Filament\Dev\Resources\GalleryItems\Schemas\GalleryItemForm;
use App\Filament\Dev\Resources\GalleryItems\Tables\GalleryItemsTable;
use App\Models\GalleryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Galería';

    protected static ?string $modelLabel = 'ítem de galería';

    protected static ?string $pluralModelLabel = 'galería';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GalleryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleryItems::route('/'),
            'create' => CreateGalleryItem::route('/create'),
            'edit' => EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
