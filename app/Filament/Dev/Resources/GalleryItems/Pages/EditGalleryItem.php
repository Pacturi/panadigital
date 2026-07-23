<?php

namespace App\Filament\Dev\Resources\GalleryItems\Pages;

use App\Filament\Dev\Resources\GalleryItems\GalleryItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGalleryItem extends EditRecord
{
    protected static string $resource = GalleryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
