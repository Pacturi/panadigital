<?php

namespace App\Filament\Dev\Resources\Attributes\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, ?string $state, callable $set): void {
                        if ($operation === 'create' && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'select' => 'Selección (opciones fijas)',
                        'text' => 'Texto libre',
                        'number' => 'Número',
                    ])
                    ->required()
                    ->live()
                    ->native(false),
                Repeater::make('options')
                    ->label('Opciones')
                    ->relationship()
                    ->schema([
                        TextInput::make('value')
                            ->label('Valor')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel('Agregar opción')
                    ->reorderable(false)
                    ->visible(fn (Get $get): bool => $get('type') === 'select')
                    ->columnSpanFull(),
            ]);
    }
}
