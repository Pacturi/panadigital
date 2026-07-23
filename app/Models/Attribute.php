<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    protected static function booted(): void
    {
        static::creating(function (Attribute $attribute): void {
            $attribute->slug ??= Str::slug($attribute->name);
        });

        static::updating(function (Attribute $attribute): void {
            if ($attribute->isDirty('name') && ! $attribute->isDirty('slug')) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });
    }

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class)->orderBy('value');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_attribute')
            ->withPivot(['required', 'order'])
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function isSelect(): bool
    {
        return $this->type === 'select';
    }
}
