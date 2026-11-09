<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

/**
 * المدخل التفاعلي: يحكي قصة Alo بالتمرير، وبعدها يودّي للمتجر.
 */
class IntroController extends Controller
{
    public function __invoke(): View
    {
        return view('intro', [
            'products' => Product::query()->active()->orderByDesc('is_featured')->latest()->take(6)->get(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
        ]);
    }
}
