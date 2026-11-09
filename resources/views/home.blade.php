<x-layouts.store :overlay="true">
    {{-- ١. البنر المقلّم (Frame 2) --}}
    <section class="stripes flex min-h-[420px] items-center justify-center rounded-b-[18px] px-6 pt-24 pb-16 sm:min-h-[min(70vh,640px)]">
        <h1 class="font-latin text-5xl leading-none font-bold text-wine sm:text-[60px]" dir="ltr">ALO</h1>
    </section>

    {{-- ٢. المنتجات (المساحة البيضا) --}}
    <section class="bg-white px-6 py-20">
        @if ($categories->isNotEmpty())
            <nav class="mb-10 flex flex-wrap justify-center gap-x-6 gap-y-2" aria-label="الأقسام">
                <a href="{{ route('shop') }}" class="chip-active">الكل</a>
                @foreach ($categories as $category)
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" class="chip">{{ $category->name }}</a>
                @endforeach
            </nav>
        @endif
        <div class="mx-auto grid max-w-[1000px] grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-4">
            @forelse ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @empty
                @foreach (range(1, 4) as $slot)
                    <div class="aspect-square bg-cream" aria-hidden="true"></div>
                @endforeach
            @endforelse
        </div>
    </section>

    {{-- ٣. البنر الوردي (Frame 13) --}}
    <section class="swirl min-h-[260px] sm:min-h-[340px]" aria-hidden="true"></section>

    {{-- ٤. منتجات إضافية (المساحة البيضا) --}}
    @if ($gridProducts->count() > $featuredProducts->count())
        <section class="bg-white px-6 py-20">
            <div class="mx-auto grid max-w-[1000px] grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-4">
                @foreach ($gridProducts->whereNotIn('id', $featuredProducts->modelKeys())->take(4) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('shop') }}" class="btn-outline h-12 min-h-12 w-[183px] px-0" aria-label="المتجر">←</a>
            </div>
        </section>
    @else
        <div class="h-20 bg-white" aria-hidden="true"></div>
    @endif

    {{-- ٥. البنر الأزرق الفاتح (Frame 16) --}}
    <section class="sky min-h-[300px] sm:min-h-[420px]" aria-hidden="true"></section>
</x-layouts.store>
