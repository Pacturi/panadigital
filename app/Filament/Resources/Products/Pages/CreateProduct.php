<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Schemas\ProductForm;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function form(Schema $schema): Schema
    {
        return ProductForm::configureCreate($schema);
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'attribute_values' => [],
            'stock' => 0,
            'active' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attribute_values'] = collect($data['attribute_values'] ?? [])
            ->filter(fn (mixed $value): bool => filled($value))
            ->all();

        return $data;
    }
}
