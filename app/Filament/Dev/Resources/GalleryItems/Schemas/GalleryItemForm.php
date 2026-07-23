<?php

namespace App\Filament\Dev\Resources\GalleryItems\Schemas;

use App\Models\GalleryItem;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        GalleryItem::TYPE_BRAND => 'Marca',
                        GalleryItem::TYPE_PRODUCT => 'Imagen de producto',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Ej: Huggies, o "Huggies Classic XG 32" para imágenes de producto.'),
                FileUpload::make('image_path')
                    ->label('Imagen')
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->visibility('public')
                    ->required()
                    ->imageEditor()
                    ->openable(),
            ]);
    }
}
