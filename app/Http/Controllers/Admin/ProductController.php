<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('q')->toString());

        return view('admin.products.index', [
            'products' => Product::query()
                ->with('category')
                ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(['is_active' => true, 'stock' => 10]), 'categories' => Category::query()->orderBy('sort_order')->get()]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = new Product($this->attributes($request));
        $product->slug = $this->uniqueSlug($request->validated('name'));
        $product->save();

        return redirect()->route('admin.products.index')->with('status', 'تمت إضافة المنتج');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::query()->orderBy('sort_order')->get()]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->attributes($request, $product));

        return redirect()->route('admin.products.index')->with('status', 'تم حفظ التعديلات');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'تم حذف المنتج');
    }

    /**
     * الصورة المرفوعة تنحفظ كـ data URI داخل قاعدة البيانات.
     *
     * @return array<string, mixed>
     */
    private function attributes(ProductRequest $request, ?Product $product = null): array
    {
        $attributes = $request->safe()->except(['image_file', 'image_url']);
        $attributes['is_featured'] = $request->boolean('is_featured');
        $attributes['is_active'] = $request->boolean('is_active');
        $attributes['image'] = $product?->image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $attributes['image'] = 'data:'.$file->getMimeType().';base64,'.base64_encode($file->getContent());
        } elseif ($request->filled('image_url')) {
            $attributes['image'] = $request->validated('image_url');
        }

        return $attributes;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name, '-', 'ar') ?: 'product';

        do {
            $slug = $base.'-'.Str::lower(Str::random(5));
        } while (Product::query()->where('slug', $slug)->exists());

        return $slug;
    }
}
