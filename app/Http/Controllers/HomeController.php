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

        $offerProducts = Product::query()->active()->whereColumn('compare_at_price', '>', 'price')->latest()->take(4)->get();

        if ($offerProducts->count() < 4) {
            $offerProducts = $offerProducts->concat(
                Product::query()->active()->whereKeyNot($offerProducts->modelKeys())->latest()->take(4 - $offerProducts->count())->get()
            );
        }

        return view('home', [
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'featuredProducts' => $featuredProducts,
            'gridProducts' => Product::query()->active()->with('category')->latest()->take(8)->get(),
            'offerProducts' => $offerProducts,
        ]);
    }
}
