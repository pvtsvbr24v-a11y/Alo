<x-layouts.admin title="المنتجات">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-extrabold">المنتجات</h1>
        <div class="flex gap-2">
            <form method="GET" role="search"><label for="q" class="sr-only">بحث</label><input id="q" type="search" name="q" value="{{ $search }}" placeholder="ابحث…" class="input py-2"></form>
            <a href="{{ route('admin.products.create') }}" class="btn-primary">+ منتج جديد</a>
        </div>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-right text-muted">
                <tr><th class="py-2 font-bold">المنتج</th><th class="py-2 font-bold">القسم</th><th class="py-2 font-bold">السعر</th><th class="py-2 font-bold">الكمية</th><th class="py-2 font-bold">الحالة</th><th></th></tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @forelse ($products as $product)
                    <tr>
                        <td class="py-2">
                            <div class="flex items-center gap-3">
                                <x-product-image :product="$product" class="h-12 w-12 shrink-0 rounded-lg text-lg" />
                                <span class="font-bold">{{ $product->name }}</span>
                                @if ($product->is_featured)<span class="rounded bg-brand/10 px-1.5 py-0.5 text-xs text-brand">مميز</span>@endif
                            </div>
                        </td>
                        <td class="py-2">{{ $product->category->name }}</td>
                        <td class="py-2"><x-price :amount="$product->price" /></td>
                        <td class="py-2 font-bold {{ $product->stock === 0 ? 'text-rose-600' : '' }}">{{ $product->stock }}</td>
                        <td class="py-2">{{ $product->is_active ? 'ظاهر' : 'مخفي' }}</td>
                        <td class="py-2 text-left"><a href="{{ route('admin.products.edit', $product) }}" class="font-bold text-brand hover:underline">تعديل</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-muted">ما فيه منتجات. <a href="{{ route('admin.products.create') }}" class="font-bold text-brand">أضف أول منتج</a></td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</x-layouts.admin>
