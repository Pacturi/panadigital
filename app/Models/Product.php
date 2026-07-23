<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'brand',
        'brand_gallery_item_id',
        'slug',
        'sku',
        'price',
        'stock',
        'image',
        'attribute_values',
        'description',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'price' => 'decimal:2',
            'stock' => 'integer',
            'attribute_values' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (filled($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brandGalleryItem(): BelongsTo
    {
        return $this->belongsTo(GalleryItem::class, 'brand_gallery_item_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! filled($this->image)) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }
}
