<?php

namespace App\Filament\Dev\Resources\GalleryItems\Pages;

use App\Filament\Dev\Resources\GalleryItems\GalleryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryItem extends CreateRecord
{
    protected static string $resource = GalleryItemResource::class;
}
