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
            'categories' => Category::query()->orderBy('sort_order')->get(),
            'products' => Product::query()->active()->orderByDesc('is_featured')->latest()->take(12)->get(),
        ]);
    }
}
