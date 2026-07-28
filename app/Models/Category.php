<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getFirstProductImageUrlAttribute(): ?string
    {
        $image = $this->products()
            ->where('is_active', true)
            ->whereNotNull('image')
            ->orderBy('id')
            ->value('image');

        return $image ? Storage::disk('public')->url($image) : null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
