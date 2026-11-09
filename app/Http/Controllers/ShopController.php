<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * @var array<string, string>
     */
    public const SORT_OPTIONS = [
        'newest' => 'الأحدث',
        'price_asc' => 'السعر: من الأقل',
        'price_desc' => 'السعر: من الأعلى',
    ];

    public function index(Request $request): View
    {
        $categories = Category::query()->orderBy('sort_order')->get();
        $activeCategory = $categories->firstWhere('slug', $request->string('category')->toString());
        $sort = array_key_exists($request->string('sort')->toString(), self::SORT_OPTIONS) ? $request->string('sort')->toString() : 'newest';
        $search = trim($request->string('q')->toString());
        $onlyOffers = $request->boolean('offers');

        $products = Product::query()
            ->active()
            ->when($activeCategory, fn ($query) => $query->where('category_id', $activeCategory->id))
            ->when($onlyOffers, fn ($query) => $query->whereColumn('compare_at_price', '>', 'price'))
            ->when($search !== '', fn ($query) => $query->where(fn ($inner) => $inner
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")))
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when($sort === 'newest', fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        return view('shop', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'products' => $products,
            'sort' => $sort,
            'search' => $search,
            'onlyOffers' => $onlyOffers,
            'sortOptions' => self::SORT_OPTIONS,
        ]);
    }
}
