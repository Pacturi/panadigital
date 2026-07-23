<?php

namespace App\Filament\Dev\Resources\Categories\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    protected static ?string $title = 'Atributos asociados';

    protected static ?string $modelLabel = 'atributo';

    protected static ?string $pluralModelLabel = 'atributos';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Atributo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'select' => 'Selección',
                        'text' => 'Texto',
                        'number' => 'Número',
                        default => $state,
                    }),
                ToggleColumn::make('required')
                    ->label('Obligatorio')
                    ->getStateUsing(fn (Model $record): bool => (bool) $record->pivot->required)
                    ->updateStateUsing(function (Model $record, mixed $state): void {
                        $this->getOwnerRecord()
                            ->attributes()
                            ->updateExistingPivot($record->getKey(), [
                                'required' => (bool) $state,
                            ]);
                    }),
                TextInputColumn::make('order')
                    ->label('Orden')
                    ->rules(['integer', 'min:0'])
                    ->width('6rem')
                    ->getStateUsing(fn (Model $record): int => (int) $record->pivot->order)
                    ->updateStateUsing(function (Model $record, mixed $state): void {
                        $this->getOwnerRecord()
                            ->attributes()
                            ->updateExistingPivot($record->getKey(), [
                                'order' => (int) $state,
                            ]);
                    }),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Asociar atributo')
                    ->preloadRecordSelect()
                    ->multiple()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Atributo')
                            ->required(),
                        Toggle::make('required')
                            ->label('Obligatorio')
                            ->default(false),
                        TextInput::make('order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ]),
            ])
            ->recordActions([
                DetachAction::make()->label('Desasociar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }
}
