<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::query()->active()->where('is_featured', true)->latest()->take(4)->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::query()->active()->latest()->take(4)->get();
        }

        return view('home', [
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'featuredProducts' => $featuredProducts,
            'offerProducts' => Product::query()->active()->whereColumn('compare_at_price', '>', 'price')->latest()->take(4)->get(),
            'gridProducts' => Product::query()->active()->with('category')->latest()->take(8)->get(),
        ]);
    }
}
