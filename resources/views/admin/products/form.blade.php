<x-layouts.admin :title="$product->exists ? 'تعديل منتج' : 'منتج جديد'">
    <h1 class="mb-6 text-2xl font-extrabold">{{ $product->exists ? 'تعديل: '.$product->name : 'منتج جديد' }}</h1>

    @if ($categories->isEmpty())
        <div class="card">لازم تضيف قسم أول. <a href="{{ route('admin.categories.index') }}" class="font-bold text-brand">إضافة قسم</a></div>
    @else
        <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
            @csrf
            @if ($product->exists) @method('PUT') @endif

            <div class="card space-y-4 lg:col-span-2">
                <div>
                    <label for="name" class="label">اسم المنتج</label>
                    <input id="name" name="name" class="input" required value="{{ old('name', $product->name) }}">
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="category_id" class="label">القسم</label>
                    <select id="category_id" name="category_id" class="input" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="description" class="label">الوصف</label>
                    <textarea id="description" name="description" rows="5" class="input">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label for="price" class="label">السعر (ر.س)</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" class="input" required value="{{ old('price', $product->price) }}">
                        @error('price')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="compare_at_price" class="label">السعر قبل الخصم</label>
                        <input id="compare_at_price" name="compare_at_price" type="number" step="0.01" min="0" class="input" value="{{ old('compare_at_price', $product->compare_at_price) }}">
                        @error('compare_at_price')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="stock" class="label">الكمية</label>
                        <input id="stock" name="stock" type="number" min="0" class="input" required value="{{ old('stock', $product->stock) }}">
                        @error('stock')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="card space-y-3">
                    <p class="label">الصورة</p>
                    <x-product-image :product="$product" class="aspect-square w-full rounded-xl" />
                    <div>
                        <label for="image_file" class="text-sm font-bold">رفع صورة (أقل من 1 ميجا)</label>
                        <input id="image_file" name="image_file" type="file" accept="image/*" class="mt-1 block w-full text-sm">
                        @error('image_file')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="image_url" class="text-sm font-bold">أو رابط صورة</label>
                        <input id="image_url" name="image_url" type="url" dir="ltr" class="input mt-1 text-sm" value="{{ old('image_url') }}" placeholder="https://">
                        @error('image_url')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="card space-y-3">
                    <input type="hidden" name="is_active" value="0">
                    <label class="flex items-center gap-2 font-bold"><input type="checkbox" name="is_active" value="1" class="accent-brand" @checked(old('is_active', $product->is_active))> ظاهر في المتجر</label>
                    <input type="hidden" name="is_featured" value="0">
                    <label class="flex items-center gap-2 font-bold"><input type="checkbox" name="is_featured" value="1" class="accent-brand" @checked(old('is_featured', $product->is_featured))> يظهر في الأكثر مبيعاً</label>
                </div>
                <button type="submit" class="btn-primary w-full">{{ $product->exists ? 'حفظ التعديلات' : 'إضافة المنتج' }}</button>
            </div>
        </form>

        @if ($product->exists)
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="mt-6" onsubmit="return confirm('متأكد تبي تحذف المنتج؟')">
                @csrf
                @method('DELETE')
                <button class="text-sm font-bold text-rose-600 hover:underline">حذف المنتج</button>
            </form>
        @endif
    @endif
</x-layouts.admin>
