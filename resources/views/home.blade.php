@php($text = config('content.home'))
<x-layouts.store>
    {{-- ١. البنر: قماش مقلّم ومنتجات --}}
    <section class="stripes relative -mx-4 overflow-hidden px-4 py-14 sm:mx-0 sm:rounded-[2.5rem] sm:py-20">
        <div class="mx-auto grid max-w-5xl items-center gap-10 md:grid-cols-2">
            <div class="glass rounded-[2rem] px-8 py-10 text-center md:text-start {{ blank($text['hero_title']) && blank($text['hero_text']) ? 'hidden md:block md:min-h-64' : '' }}">
                @if (filled($text['hero_badge']))
                    <span class="inline-block rounded-full bg-rose-soft px-4 py-1.5 text-xs font-bold text-brand-dark">{{ $text['hero_badge'] }}</span>
                @endif
                @if (filled($text['hero_title']))
                    <h1 class="squiggle mt-5 text-5xl leading-tight sm:text-6xl">{{ $text['hero_title'] }}</h1>
                @endif
                @if (filled($text['hero_text']))
                    <p class="mt-5 leading-8 text-muted">{{ $text['hero_text'] }}</p>
                @endif
                @if (filled($text['hero_button']))
                    <a href="{{ route('shop') }}" class="btn-primary mt-8">{{ $text['hero_button'] }}</a>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach ($newProducts->take(4) as $product)
                    <a href="{{ route('products.show', $product) }}" class="sticker block {{ $loop->odd ? 'translate-y-6' : '' }}" style="--tilt: {{ $loop->odd ? '-3' : '3' }}deg">
                        <x-product-image :product="$product" class="aspect-square w-full rounded-[1.4rem]" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ٢. الأقسام على قماش المربعات --}}
    @if ($categories->isNotEmpty())
        <section class="gingham relative -mx-4 mt-16 px-4 py-14 sm:mx-0 sm:rounded-[2.5rem]">
            @include('partials.section-heading', ['title' => $text['categories_title'], 'subtitle' => $text['categories_text']])
            <div class="mx-auto grid max-w-4xl gap-5 {{ ['', 'sm:grid-cols-1', 'sm:grid-cols-2', 'sm:grid-cols-3'][min($categories->count(), 3)] }}">
                @foreach ($categories as $category)
                    @php($cover = $category->products()->active()->whereNotNull('image')->latest()->first())
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" class="sticker group block" style="--tilt: {{ $loop->odd ? '-1.5' : '1.5' }}deg">
                        <div class="relative aspect-[4/3] overflow-hidden rounded-[1.4rem] bg-sky-soft">
                            @if ($cover)
                                <x-product-image :product="$cover" class="h-full w-full transition duration-500 group-hover:scale-105" />
                            @endif
                        </div>
                        <p class="py-4 text-center font-display text-2xl">{{ $category->name }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ٣. منتجات مميزة --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="mt-20">
            @include('partials.section-heading', ['title' => $text['featured_title'], 'subtitle' => $text['featured_text']])
            <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-4 md:gap-x-6">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('shop') }}" class="btn-outline" aria-label="كل المنتجات">←</a>
            </div>
        </section>
    @endif

    {{-- ٤. عرض على قماش الدوائر الوردية --}}
    @if ($onSaleProduct)
        <section class="swirl relative -mx-4 mt-20 overflow-hidden px-4 py-14 sm:mx-0 sm:rounded-[2.5rem]">
            <div class="mx-auto grid max-w-4xl items-center gap-10 md:grid-cols-2">
                <div class="glass rounded-[2rem] p-8 text-center">
                    @if (filled($text['offer_badge']))
                        <span class="tape relative! inline-block">{{ $text['offer_badge'] }}</span>
                    @else
                        <span class="tape relative! inline-block">−{{ $onSaleProduct->discountPercent() }}٪</span>
                    @endif
                    <p class="mt-5 font-display text-3xl">{{ $onSaleProduct->name }}</p>
                    <div class="mt-3 flex items-baseline justify-center gap-3">
                        <x-price :amount="$onSaleProduct->price" class="text-3xl font-bold text-brand" />
                        <x-price :amount="$onSaleProduct->compare_at_price" class="text-muted line-through" />
                    </div>
                    @if (filled($text['offer_countdown_label']))
                        <p class="mt-6 text-sm font-bold text-muted">{{ $text['offer_countdown_label'] }}</p>
                    @endif
                    <div class="mt-3 flex justify-center gap-3" data-countdown="{{ now()->endOfWeek()->toIso8601String() }}" aria-label="العداد">
                        @foreach (['days' => 'يوم', 'hours' => 'ساعة', 'minutes' => 'دقيقة'] as $unit => $label)
                            <div class="glass min-w-18 rounded-2xl px-3 py-3">
                                <p class="font-latin text-3xl leading-none font-semibold" data-{{ $unit }}>--</p>
                                <p class="mt-1 text-xs text-muted">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                    <form method="POST" action="{{ route('cart.store', $onSaleProduct) }}" class="mt-8">
                        @csrf
                        <button class="btn-primary">{{ filled($text['offer_button']) ? $text['offer_button'] : 'أضف للسلة' }}</button>
                    </form>
                </div>
                <a href="{{ route('products.show', $onSaleProduct) }}" class="sticker mx-auto block w-full max-w-sm" style="--tilt: 3deg">
                    <x-product-image :product="$onSaleProduct" class="aspect-square w-full rounded-[1.4rem]" />
                </a>
            </div>
        </section>
    @endif

    {{-- ٥. المميزات (تظهر لما تكتبها في config/content.php) --}}
    @if (! empty($text['promises']))
        <section class="mt-20 grid gap-5 {{ ['', 'sm:grid-cols-1', 'sm:grid-cols-2', 'sm:grid-cols-3'][min(count($text['promises']), 3)] }}">
            @foreach ($text['promises'] as [$heading, $body])
                <div class="card text-center">
                    <p class="font-display text-2xl">{{ $heading }}</p>
                    <div class="mx-auto my-3 h-0.5 w-10 bg-brand-dark"></div>
                    <p class="text-sm text-muted">{{ $body }}</p>
                </div>
            @endforeach
        </section>
    @endif
</x-layouts.store>
