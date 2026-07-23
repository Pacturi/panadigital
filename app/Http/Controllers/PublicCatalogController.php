<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Product;
use App\Models\Tenant;
use App\Support\CatalogTemplates;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PublicCatalogController extends Controller
{
    public function catalog(string $slug): View|Response
    {
        $business = $this->resolveBusiness($slug);
        $template = $business->resolvedTemplate();

        $products = $business->activeProducts()
            ->with(['brandGalleryItem', 'category'])
            ->get();

        return view(CatalogTemplates::viewName($template), [
            'page' => 'inicio',
            'business' => $this->businessPayload($business),
            'products' => $products
                ->map(fn (Product $product): array => $this->productCard($business, $product))
                ->all(),
            'categories' => $this->categoriesFromProducts($products),
            'brands' => $this->brandsFromProducts($products),
            'product' => null,
        ]);
    }

    public function products(string $slug): View|Response
    {
        $business = $this->resolveBusiness($slug);
        $template = $business->resolvedTemplate();

        $products = $business->activeProducts()
            ->with(['brandGalleryItem', 'category'])
            ->get();

        return view(CatalogTemplates::productsViewName($template), [
            'page' => 'productos',
            'business' => $this->businessPayload($business),
            'products' => $products
                ->map(fn (Product $product): array => $this->productCard($business, $product))
                ->all(),
            'categories' => $this->categoriesFromProducts($products),
            'brands' => [],
            'product' => null,
        ]);
    }

    public function product(string $slug, string $productoSlugOrId): View|Response
    {
        $business = $this->resolveBusiness($slug);
        $template = $business->resolvedTemplate();

        $product = $business->activeProducts()
            ->with(['brandGalleryItem', 'category'])
            ->where(function ($query) use ($productoSlugOrId): void {
                $query->where('slug', $productoSlugOrId);

                if (ctype_digit($productoSlugOrId)) {
                    $query->orWhere('id', (int) $productoSlugOrId);
                }
            })
            ->firstOrFail();

        return view(CatalogTemplates::viewName($template), [
            'page' => 'producto',
            'business' => $this->businessPayload($business),
            'products' => [],
            'categories' => [],
            'brands' => [],
            'product' => $this->productDetail($business, $product),
        ]);
    }

    private function resolveBusiness(string $slug): Tenant
    {
        abort_if(CatalogTemplates::isReservedSlug($slug), 404);

        return Tenant::query()
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();
    }

    /**
     * @return array<string, mixed>
     */
    private function businessPayload(Tenant $business): array
    {
        return [
            'name' => $business->name,
            'slug' => $business->slug,
            'description' => $business->description,
            'phone' => $business->phone,
            'whatsapp_url' => $business->whatsappUrl(),
            'instagram' => $business->instagram,
            'instagram_url' => $business->instagramUrl(),
            'logo_url' => $business->logo_url,
            'banner_url' => $business->banner_url,
            'hero_title' => $business->hero_title ?: $business->name,
            'hero_subtitle' => $business->hero_subtitle ?: $business->description,
            'catalog_url' => $business->catalogUrl(),
            'products_url' => $business->productsUrl(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function productCard(Tenant $business, Product $product): array
    {
        $brand = $product->brandGalleryItem;
        $category = $product->category;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'price_formatted' => $this->formatPrice($product->price),
            'image_url' => $product->image_url,
            'url' => url('/'.$business->slug.'/producto/'.$product->slug),
            'whatsapp_url' => $business->whatsappUrl(
                'Hola! Me interesa: '.$product->name.' ('.$this->formatPrice($product->price).')'
            ),
            'category' => $category ? [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ] : null,
            'brand' => $brand ? [
                'name' => $brand->name,
                'image_url' => $brand->image_url,
            ] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function productDetail(Tenant $business, Product $product): array
    {
        return [
            ...$this->productCard($business, $product),
            'description' => $product->description,
            'stock' => $product->stock,
            'attribute_values' => $product->attribute_values ?? [],
        ];
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return list<array{id: int, name: string, slug: string, count: int}>
     */
    private function categoriesFromProducts(Collection $products): array
    {
        return $products
            ->pluck('category')
            ->filter()
            ->groupBy('id')
            ->map(function (Collection $group): array {
                /** @var Category $category */
                $category = $group->first();

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'count' => $group->count(),
                ];
            })
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return list<array{name: string, image_url: ?string}>
     */
    private function brandsFromProducts(Collection $products): array
    {
        return $products
            ->pluck('brandGalleryItem')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->map(fn (GalleryItem $brand): array => [
                'name' => $brand->name,
                'image_url' => $brand->image_url,
            ])
            ->all();
    }

    private function formatPrice(mixed $price): string
    {
        return '$'.number_format((float) $price, 2, ',', '.');
    }
}
