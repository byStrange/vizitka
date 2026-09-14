<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $category = $request->query('category');

        $products = Product::query()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Product::query()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return Inertia::render('Shop', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }

    public function show(Product $product): Response
    {
        return Inertia::render('ProductDetail', [
            'product' => $product,
        ]);
    }
}
