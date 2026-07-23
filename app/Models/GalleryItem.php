<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    public const TYPE_BRAND = 'marca';

    public const TYPE_PRODUCT = 'producto';

    protected $fillable = [
        'type',
        'name',
        'image_path',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_gallery_item_id');
    }

    public function scopeBrands(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_BRAND)->orderBy('name');
    }

    public function scopeProductImages(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_PRODUCT)->orderBy('name');
    }

    public function isBrand(): bool
    {
        return $this->type === self::TYPE_BRAND;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
