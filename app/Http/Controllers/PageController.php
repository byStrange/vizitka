<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function home(): Response
    {
        $settings = Setting::all()->mapWithKeys(
            fn (Setting $s): array => [$s->key => $s->value['ru'] ?? ''],
        )->toArray();

        return Inertia::render('HomePage', [
            'categories' => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Category $c): array => [
                    'slug' => $c->slug,
                    'name' => $c->getTranslation('name', 'ru'),
                    'description' => $c->getTranslation('description', 'ru'),
                    'itemCount' => $c->products()->where('is_active', true)->count(),
                    'image' => $c->first_product_image_url,
                ]),
            'newProducts' => Product::where('is_active', true)
                ->where('is_new', true)
                ->with('category:id,slug,name')
                ->latest()
                ->take(6)
                ->get()
                ->map(fn (Product $p): array => $this->productToArray($p)),
            'discountProducts' => Product::where('is_active', true)
                ->whereNotNull('discount_percent')
                ->with('category:id,slug,name')
                ->orderByDesc('discount_percent')
                ->take(4)
                ->get()
                ->map(fn (Product $p): array => $this->productToArray($p)),
            'settings' => $settings,
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('AboutPage');
    }

    /** @return array<string, mixed> */
    private function productToArray(Product $product): array
    {
        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->getTranslation('name', 'ru'),
            'sku' => $product->sku,
            'category' => $product->category?->getTranslation('name', 'ru') ?? '',
            'description' => $product->getTranslation('description', 'ru'),
            'image' => $product->image_url,
            'moq' => $product->moq,
            'leadTime' => $product->lead_time,
            'customization' => $product->customization,
            'isNew' => $product->is_new,
            'discount' => $product->discount_percent,
            'specs' => $product->specs ?? [],
        ];
    }
}
