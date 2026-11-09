<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'categories' => Category::query()->orderBy('sort_order')->withCount(['products' => fn ($query) => $query->active()])->get(),
            'featuredProducts' => Product::query()->active()->where('is_featured', true)->latest()->take(8)->get(),
            'onSaleProduct' => Product::query()->active()->whereColumn('compare_at_price', '>', 'price')->where('stock', '>', 0)->latest()->first(),
            'newProducts' => Product::query()->active()->latest()->take(4)->get(),
        ]);
    }
}
