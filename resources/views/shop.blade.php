<x-layouts.store :title="$onlyOffers ? 'Offers' : ($activeCategory?->name ?? 'المتجر')">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="squiggle text-4xl">{{ $onlyOffers ? 'Offers' : ($activeCategory?->name ?? 'كل المنتجات') }}</h1>
            @if ($search !== '')
                <p class="mt-1 text-muted">نتائج البحث عن «{{ $search }}»</p>
            @endif
        </div>

        <form method="GET" action="{{ route('shop') }}" class="flex items-center gap-2">
            @if ($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory->slug }}">@endif
            @if ($onlyOffers)<input type="hidden" name="offers" value="1">@endif
            @if ($search !== '')<input type="hidden" name="q" value="{{ $search }}">@endif
            <label for="sort" class="text-sm font-bold">ترتيب:</label>
            <select id="sort" name="sort" class="input w-auto rounded-full py-2 text-sm" onchange="this.form.submit()">
                @foreach ($sortOptions as $value => $label)
                    <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <noscript><button class="btn-outline">تطبيق</button></noscript>
        </form>
    </div>

    <nav class="mb-8 flex gap-2 overflow-x-auto pb-1" aria-label="الأقسام">
        <a href="{{ route('shop', array_filter(['sort' => $sort, 'q' => $search])) }}" class="{{ $activeCategory || $onlyOffers ? 'chip' : 'chip-active' }}">الكل</a>
        @foreach ($categories as $category)
            <a href="{{ route('shop', array_filter(['category' => $category->slug, 'sort' => $sort, 'q' => $search])) }}" class="{{ $activeCategory?->is($category) ? 'chip-active' : 'chip' }}">{{ $category->name }}</a>
        @endforeach
        <a href="{{ route('shop', ['offers' => 1]) }}" class="{{ $onlyOffers ? 'chip-active' : 'chip' }}">Offers</a>
    </nav>

    @if ($products->isEmpty())
        <div class="card py-16 text-center">
            <p class="text-4xl" aria-hidden="true">🔍</p><p class="mt-2 font-display text-2xl font-bold">ما لقينا حكايات هنا</p>
            <p class="mt-1 text-muted">جرّب قسم ثاني أو كلمة بحث مختلفة.</p>
            <a href="{{ route('shop') }}" class="btn-primary mt-5">عرض كل المنتجات</a>
        </div>
    @else
        <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-3 md:gap-x-6 lg:grid-cols-4">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        <div class="mt-8">{{ $products->links() }}</div>
    @endif
</x-layouts.store>
