<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        $content = view('sitemap', [
            'products' => $products,
            'categories' => $categories,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'text/xml',
        ]);
    }
}
