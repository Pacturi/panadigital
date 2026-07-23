<?php

namespace App\Filament\Dev\Resources\Tenants\Schemas;

use App\Support\CatalogTemplates;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                        if ($operation === 'create' && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('URL pública: /{slug}'),
                Textarea::make('description')
                    ->label('Descripción corta')
                    ->rows(2),
                TextInput::make('phone')
                    ->label('Teléfono / WhatsApp')
                    ->tel(),
                Select::make('template')
                    ->label('Plantilla')
                    ->options(CatalogTemplates::options())
                    ->default(CatalogTemplates::default())
                    ->required()
                    ->native(false),
                Toggle::make('active')
                    ->label('Catálogo activo')
                    ->default(true),
            ]);
    }
}
