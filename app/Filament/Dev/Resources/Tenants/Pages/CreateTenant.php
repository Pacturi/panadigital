<?php

namespace App\Filament\Dev\Resources\Tenants\Pages;

use App\Filament\Dev\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
