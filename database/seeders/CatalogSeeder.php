<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = $this->seedAttributes();
        $this->seedCategories($attributes);
    }

    /**
     * @return array<string, Attribute>
     */
    private function seedAttributes(): array
    {
        $definitions = [
            'marca' => [
                'name' => 'Marca',
                'type' => 'select',
                'options' => [
                    'Pampers', 'Huggies', 'Babysec', 'Dove', 'Johnson\'s',
                    'Avent', 'Nuk', 'Chicco', 'Medela', 'Mam',
                    'Genérico', 'Otra',
                ],
            ],
            'talle' => [
                'name' => 'Talle',
                'type' => 'select',
                'options' => [
                    'RN', 'RN+', 'P', 'M', 'G', 'XG', 'XXG',
                    '0-3 meses', '3-6 meses', '6-9 meses', '9-12 meses',
                    '12-18 meses', '18-24 meses', '2T', '3T', '4T',
                    'Único',
                ],
            ],
            'color' => [
                'name' => 'Color',
                'type' => 'select',
                'options' => [
                    'Blanco', 'Negro', 'Rosa', 'Celeste', 'Azul',
                    'Rojo', 'Verde', 'Amarillo', 'Gris', 'Beige',
                    'Multicolor', 'Estampado',
                ],
            ],
            'genero' => [
                'name' => 'Género',
                'type' => 'select',
                'options' => ['Unisex', 'Nena', 'Nene'],
            ],
            'cantidad' => [
                'name' => 'Cantidad',
                'type' => 'number',
                'options' => [],
            ],
            'capacidad' => [
                'name' => 'Capacidad',
                'type' => 'select',
                'options' => ['60 ml', '125 ml', '150 ml', '260 ml', '330 ml'],
            ],
            'material' => [
                'name' => 'Material',
                'type' => 'select',
                'options' => [
                    'Plástico', 'Vidrio', 'Silicona', 'Algodón',
                    'Bambú', 'Poliéster', 'Mixto',
                ],
            ],
            'etapa' => [
                'name' => 'Etapa',
                'type' => 'select',
                'options' => [
                    'Recién nacido', '0-6 meses', '6+ meses',
                    '1 etapa', '2 etapa', '3 etapa',
                ],
            ],
            'tipo-cierre' => [
                'name' => 'Tipo de cierre',
                'type' => 'select',
                'options' => ['Broches', 'Velcro', 'Elástico', 'Sin cierre'],
            ],
            'flujo' => [
                'name' => 'Flujo',
                'type' => 'select',
                'options' => ['Lento', 'Medio', 'Rápido', 'Variable'],
            ],
            'sabor' => [
                'name' => 'Sabor',
                'type' => 'text',
                'options' => [],
            ],
            'edad-recomendada' => [
                'name' => 'Edad recomendada',
                'type' => 'text',
                'options' => [],
            ],
        ];

        $attributes = [];

        foreach ($definitions as $slug => $definition) {
            $attribute = Attribute::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $definition['name'],
                    'type' => $definition['type'],
                ],
            );

            if ($definition['type'] === 'select') {
                $existing = $attribute->options()->pluck('value')->all();

                foreach ($definition['options'] as $value) {
                    if (! in_array($value, $existing, true)) {
                        $attribute->options()->create(['value' => $value]);
                    }
                }
            }

            $attributes[$slug] = $attribute->fresh('options');
        }

        return $attributes;
    }

    /**
     * @param  array<string, Attribute>  $attributes
     */
    private function seedCategories(array $attributes): void
    {
        $definitions = [
            'panales' => [
                'name' => 'Pañales',
                'attributes' => [
                    ['slug' => 'marca', 'required' => true, 'order' => 1],
                    ['slug' => 'talle', 'required' => true, 'order' => 2],
                    ['slug' => 'cantidad', 'required' => true, 'order' => 3],
                    ['slug' => 'genero', 'required' => false, 'order' => 4],
                ],
            ],
            'panales-de-natacion' => [
                'name' => 'Pañales de natación',
                'attributes' => [
                    ['slug' => 'marca', 'required' => true, 'order' => 1],
                    ['slug' => 'talle', 'required' => true, 'order' => 2],
                    ['slug' => 'cantidad', 'required' => false, 'order' => 3],
                ],
            ],
            'toallitas' => [
                'name' => 'Toallitas húmedas',
                'attributes' => [
                    ['slug' => 'marca', 'required' => true, 'order' => 1],
                    ['slug' => 'cantidad', 'required' => true, 'order' => 2],
                    ['slug' => 'sabor', 'required' => false, 'order' => 3],
                ],
            ],
            'mamaderas' => [
                'name' => 'Mamaderas',
                'attributes' => [
                    ['slug' => 'marca', 'required' => true, 'order' => 1],
                    ['slug' => 'capacidad', 'required' => true, 'order' => 2],
                    ['slug' => 'material', 'required' => true, 'order' => 3],
                    ['slug' => 'flujo', 'required' => false, 'order' => 4],
                ],
            ],
            'chupetes' => [
                'name' => 'Chupetes',
                'attributes' => [
                    ['slug' => 'marca', 'required' => true, 'order' => 1],
                    ['slug' => 'etapa', 'required' => true, 'order' => 2],
                    ['slug' => 'material', 'required' => false, 'order' => 3],
                    ['slug' => 'color', 'required' => false, 'order' => 4],
                ],
            ],
            'remeras' => [
                'name' => 'Remeras',
                'attributes' => [
                    ['slug' => 'talle', 'required' => true, 'order' => 1],
                    ['slug' => 'color', 'required' => true, 'order' => 2],
                    ['slug' => 'genero', 'required' => true, 'order' => 3],
                    ['slug' => 'material', 'required' => false, 'order' => 4],
                ],
            ],
            'bodies' => [
                'name' => 'Bodies',
                'attributes' => [
                    ['slug' => 'talle', 'required' => true, 'order' => 1],
                    ['slug' => 'color', 'required' => true, 'order' => 2],
                    ['slug' => 'genero', 'required' => true, 'order' => 3],
                    ['slug' => 'tipo-cierre', 'required' => false, 'order' => 4],
                    ['slug' => 'material', 'required' => false, 'order' => 5],
                ],
            ],
            'pantalones' => [
                'name' => 'Pantalones',
                'attributes' => [
                    ['slug' => 'talle', 'required' => true, 'order' => 1],
                    ['slug' => 'color', 'required' => true, 'order' => 2],
                    ['slug' => 'genero', 'required' => true, 'order' => 3],
                    ['slug' => 'material', 'required' => false, 'order' => 4],
                ],
            ],
            'enteritos' => [
                'name' => 'Enteritos',
                'attributes' => [
                    ['slug' => 'talle', 'required' => true, 'order' => 1],
                    ['slug' => 'color', 'required' => true, 'order' => 2],
                    ['slug' => 'genero', 'required' => true, 'order' => 3],
                    ['slug' => 'tipo-cierre', 'required' => false, 'order' => 4],
                ],
            ],
            'accesorios-lactancia' => [
                'name' => 'Accesorios de lactancia',
                'attributes' => [
                    ['slug' => 'marca', 'required' => true, 'order' => 1],
                    ['slug' => 'material', 'required' => false, 'order' => 2],
                    ['slug' => 'capacidad', 'required' => false, 'order' => 3],
                ],
            ],
            'higiene' => [
                'name' => 'Higiene y cuidado',
                'attributes' => [
                    ['slug' => 'marca', 'required' => true, 'order' => 1],
                    ['slug' => 'cantidad', 'required' => false, 'order' => 2],
                    ['slug' => 'edad-recomendada', 'required' => false, 'order' => 3],
                ],
            ],
            'juguetes' => [
                'name' => 'Juguetes',
                'attributes' => [
                    ['slug' => 'marca', 'required' => false, 'order' => 1],
                    ['slug' => 'edad-recomendada', 'required' => true, 'order' => 2],
                    ['slug' => 'material', 'required' => false, 'order' => 3],
                    ['slug' => 'color', 'required' => false, 'order' => 4],
                ],
            ],
        ];

        foreach ($definitions as $slug => $definition) {
            $category = Category::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $definition['name'],
                    'parent_id' => null,
                ],
            );

            $sync = [];

            foreach ($definition['attributes'] as $assignment) {
                $attribute = $attributes[$assignment['slug']] ?? null;

                if (! $attribute) {
                    continue;
                }

                $sync[$attribute->id] = [
                    'required' => $assignment['required'],
                    'order' => $assignment['order'],
                ];
            }

            $category->attributes()->sync($sync);
        }
    }
}
