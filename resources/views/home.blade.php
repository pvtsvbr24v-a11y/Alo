<x-layouts.store>
    @if ($categories->isNotEmpty())
        <nav class="mb-8 flex gap-2 overflow-x-auto pb-1" aria-label="الأقسام">
            @foreach ($categories as $category)
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="chip">{{ $category->name }}</a>
            @endforeach
        </nav>
    @endif

    @if ($products->isEmpty())
        <div class="card py-16 text-center text-muted">ما فيه منتجات للحين.</div>
    @else
        <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('shop') }}" class="btn-outline">كل المنتجات</a>
        </div>
    @endif
</x-layouts.store>
