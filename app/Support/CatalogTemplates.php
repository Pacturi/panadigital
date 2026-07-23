<?php

namespace App\Support;

class CatalogTemplates
{
    /**
     * @return array<string, array{label: string, description?: string, file: string}>
     */
    public static function all(): array
    {
        return config('catalog-templates.available', []);
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::all())
            ->mapWithKeys(fn (array $meta, string $key): array => [$key => $meta['label']])
            ->all();
    }

    public static function default(): string
    {
        return (string) config('catalog-templates.default');
    }

    public static function resolve(?string $key): string
    {
        $key = $key ?: self::default();

        return array_key_exists($key, self::all()) ? $key : self::default();
    }

    /**
     * Vista PHP autocontenida de la plantilla (HTML + CSS + JS).
     */
    public static function viewName(string $template): string
    {
        $key = self::resolve($template);
        $file = self::all()[$key]['file'] ?? ('template'.ucfirst($key));

        return 'public-templates.'.$key.'.'.$file;
    }

    /**
     * Vista del listado de productos (filtros, categorías, precios).
     */
    public static function productsViewName(string $template): string
    {
        $key = self::resolve($template);
        $meta = self::all()[$key] ?? [];
        $file = $meta['file_products'] ?? (($meta['file'] ?? ('template'.ucfirst($key))).'Products');

        return 'public-templates.'.$key.'.'.$file;
    }

    /**
     * @return list<string>
     */
    public static function reservedSlugs(): array
    {
        return config('catalog-templates.reserved_slugs', []);
    }

    public static function isReservedSlug(string $slug): bool
    {
        return in_array(strtolower($slug), self::reservedSlugs(), true);
    }
}
