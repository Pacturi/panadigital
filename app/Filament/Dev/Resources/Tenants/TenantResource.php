<?php

namespace App\Filament\Dev\Resources\Tenants;

use App\Filament\Dev\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Dev\Resources\Tenants\Pages\EditTenant;
use App\Filament\Dev\Resources\Tenants\Pages\ListTenants;
use App\Filament\Dev\Resources\Tenants\Schemas\TenantForm;
use App\Filament\Dev\Resources\Tenants\Tables\TenantsTable;
use App\Models\Tenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Pañaleras';

    protected static ?string $modelLabel = 'pañalera';

    protected static ?string $pluralModelLabel = 'pañaleras';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
