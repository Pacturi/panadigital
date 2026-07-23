<?php

namespace App\Filament\Dev\Resources\Users\Pages;

use App\Filament\Dev\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
