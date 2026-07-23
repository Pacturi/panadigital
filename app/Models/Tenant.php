<?php

namespace App\Models;

use App\Support\CatalogTemplates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'phone',
        'instagram',
        'logo_path',
        'banner_path',
        'hero_title',
        'hero_subtitle',
        'template',
        'active',
        'feed_token',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant): void {
            $tenant->feed_token ??= Str::random(40);
            $tenant->template ??= CatalogTemplates::default();
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function activeProducts(): HasMany
    {
        return $this->products()->where('active', true)->orderBy('name');
    }

    public function resolvedTemplate(): string
    {
        return CatalogTemplates::resolve($this->template);
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone);

        if (blank($digits)) {
            return null;
        }

        $url = 'https://wa.me/'.$digits;

        if (filled($message)) {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    public function catalogUrl(): string
    {
        return url('/'.$this->slug);
    }

    public function productsUrl(): string
    {
        return url('/'.$this->slug.'/productos');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->publicDiskUrl($this->logo_path);
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->publicDiskUrl($this->banner_path);
    }

    public function instagramUrl(): ?string
    {
        $handle = trim((string) $this->instagram);

        if ($handle === '') {
            return null;
        }

        if (str_starts_with($handle, 'http://') || str_starts_with($handle, 'https://')) {
            return $handle;
        }

        return 'https://instagram.com/'.ltrim($handle, '@');
    }

    private function publicDiskUrl(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }
}
