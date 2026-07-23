<?php

namespace App\Filament\Dev\Resources\GalleryItems\Pages;

use App\Filament\Dev\Resources\GalleryItems\GalleryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGalleryItems extends ListRecords
{
    protected static string $resource = GalleryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
