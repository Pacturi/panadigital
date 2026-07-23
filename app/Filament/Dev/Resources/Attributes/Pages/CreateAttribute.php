<?php

namespace App\Filament\Dev\Resources\Attributes\Pages;

use App\Filament\Dev\Resources\Attributes\AttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;
}
