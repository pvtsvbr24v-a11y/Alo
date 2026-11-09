<x-layouts.admin title="الأقسام">
    <h1 class="mb-6 text-2xl font-extrabold">الأقسام</h1>
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card space-y-3 lg:col-span-2">
            @forelse ($categories as $category)
                <div class="flex flex-wrap items-center gap-3 border-b border-ink/5 pb-3 last:border-0">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex flex-1 items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <label for="cat-{{ $category->id }}" class="sr-only">اسم القسم</label>
                        <input id="cat-{{ $category->id }}" name="name" value="{{ $category->name }}" class="input py-2">
                        <button class="btn-outline py-2">حفظ</button>
                    </form>
                    <span class="text-sm text-muted">{{ $category->products_count }} منتج</span>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('حذف القسم؟')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm font-bold text-rose-600 hover:underline">حذف</button>
                    </form>
                </div>
            @empty
                <p class="text-muted">ما فيه أقسام.</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="card h-fit space-y-3">
            @csrf
            <label for="new-category" class="label">قسم جديد</label>
            <input id="new-category" name="name" class="input" required value="{{ old('name') }}">
            @error('name')<p class="field-error">{{ $message }}</p>@enderror
            <button class="btn-primary w-full">إضافة</button>
        </form>
    </div>
</x-layouts.admin>
