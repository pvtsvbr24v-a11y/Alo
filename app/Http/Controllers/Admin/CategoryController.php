<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories', ['categories' => Category::query()->withCount('products')->orderBy('sort_order')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:100']], [], ['name' => 'اسم القسم']);

        $base = Str::slug($validated['name'], '-', 'ar') ?: 'category';
        $slug = $base;

        while (Category::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(4));
        }

        Category::query()->create([
            'name' => $validated['name'],
            'slug' => $slug,
            'sort_order' => (int) Category::query()->max('sort_order') + 1,
        ]);

        return back()->with('status', 'تمت إضافة القسم');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:100']], [], ['name' => 'اسم القسم']);

        $category->update(['name' => $validated['name']]);

        return back()->with('status', 'تم تعديل القسم');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'ما تقدر تحذف قسم فيه منتجات، انقلها لقسم ثاني أول');
        }

        $category->delete();

        return back()->with('status', 'تم حذف القسم');
    }
}
