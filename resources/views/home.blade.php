<x-layouts.store :full="true">
    {{-- ١. البنر المقلّم (Frame 2) والقطة بنفس مكانها في Figma: نصها السفلي يطلع تحت البنر --}}
    <section class="stripes relative z-10 mx-3 mt-3 h-[240px] rounded-[18px] sm:mx-5 sm:h-[min(42vh,380px)]">
        <img src="{{ asset('images/alo/cat-sticker.png') }}" alt="" class="pointer-events-none absolute right-[2.6%] bottom-0 w-[15%] max-w-[220px] min-w-[100px] translate-y-[61%]" width="392" height="288">
    </section>

    {{-- ٢. المنتجات (المساحة البيضا) --}}
    <section class="bg-white px-6 pt-[max(4.5rem,8vw)] pb-20 lg:pt-28">
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
    <section class="swirl h-[160px] sm:h-[220px]" aria-hidden="true"></section>

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
    <section class="sky h-[160px] sm:h-[240px]" aria-hidden="true"></section>
</x-layouts.store>
