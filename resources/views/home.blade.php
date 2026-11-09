@php($text = config('content.home'))
@php($placeholder = 'block bg-sand/80')
<x-layouts.store :overlay="true">
    {{-- ١. البنر المقلّم (Frame 2) --}}
    <section class="stripes relative flex min-h-[460px] items-center justify-center overflow-hidden rounded-b-[18px] px-6 pt-24 pb-16 sm:min-h-[min(72vh,680px)]">
        <div class="text-center">
            <h1 class="font-latin text-5xl leading-none font-bold text-wine sm:text-[60px]" dir="ltr">ALO</h1>
            @if (filled($text['hero_subtitle']))
                <p class="mt-5 font-display text-lg tracking-[3px] text-white drop-shadow-[0_1px_3px_rgb(0_0_0/0.35)] sm:text-2xl">{{ $text['hero_subtitle'] }}</p>
            @endif
            @if (filled($text['hero_button_outline']) || filled($text['hero_button_solid']))
                <div class="mt-8 flex flex-wrap justify-center gap-6">
                    @if (filled($text['hero_button_outline']))
                        <a href="{{ route('intro') }}" class="btn-outline-light w-[150px] px-0">{{ $text['hero_button_outline'] }}</a>
                    @endif
                    @if (filled($text['hero_button_solid']))
                        <a href="{{ route('shop') }}" class="btn-primary w-[150px] px-0">{{ $text['hero_button_solid'] }}</a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    {{-- ٢. الحكاية (Frame 12): القطة فوق يمين العنوان مثل Figma --}}
    <section class="relative overflow-hidden bg-cream px-6 pt-36 pb-20 sm:pt-40">
        <div class="relative mx-auto max-w-[1000px] text-center">
            <img src="{{ asset('images/alo/cat-sticker.png') }}" alt="" class="pointer-events-none absolute -top-28 right-0 w-36 sm:-top-32 sm:right-[4%] sm:w-52 lg:w-60" loading="lazy">
            @if (filled($text['history_title']))
                <h2 class="squiggle text-center font-story text-[30px] leading-tight text-ink sm:text-[34px]" dir="ltr">{{ $text['history_title'] }}</h2>
            @endif
            @if (filled($text['history_arabic']))
                <p class="mx-auto mt-4 max-w-[700px] font-arabic-serif text-[15px] leading-[27px] whitespace-pre-line text-body">{{ $text['history_arabic'] }}</p>
            @endif
            @if (filled($text['history_english']))
                <p class="mx-auto mt-3 max-w-[700px] font-story text-[15px] leading-[25px] font-bold whitespace-pre-line text-black" dir="ltr">{{ $text['history_english'] }}</p>
            @endif

            <div class="mx-auto mt-12 grid max-w-[900px] grid-cols-[1fr_2fr_1fr] items-end gap-3 sm:gap-4" dir="ltr">
                <img src="{{ asset('images/alo/history-1.jpg') }}" alt="" class="aspect-[288/233] w-full object-cover" loading="lazy">
                <img src="{{ asset('images/alo/history-2.jpg') }}" alt="" class="aspect-[570/347] w-full object-cover" loading="lazy">
                <img src="{{ asset('images/alo/history-3.jpg') }}" alt="" class="aspect-[288/233] w-full object-cover" loading="lazy">
            </div>
        </div>
    </section>

    {{-- ٣. البنر الوردي (Frame 13) --}}
    <section class="swirl relative flex items-center justify-center px-6 {{ filled($text['banner_text']) ? 'min-h-[340px]' : 'min-h-[220px]' }}">
        <div class="absolute inset-0 bg-[rgb(226_222_222/0.5)]" aria-hidden="true"></div>
        <div class="relative max-w-[600px] text-center">
            @if (filled($text['banner_text']))
                <p class="font-display text-2xl leading-[44px] font-bold text-white sm:text-[28px]">{{ $text['banner_text'] }}</p>
            @endif
            @if (filled($text['banner_button']))
                <a href="{{ route('shop') }}" class="btn-outline-light mt-6 h-10 min-h-10 w-[180px] px-0 font-display font-bold tracking-normal">{{ $text['banner_button'] }}</a>
            @endif
        </div>
    </section>

    {{-- ٤. منتجات مميزة (Frame 15: Awesome Team) --}}
    <section class="bg-white px-6 py-20">
        @include('partials.section-heading', ['title' => $text['featured_title'], 'subtitle' => $text['featured_text']])
        <div class="mx-auto mt-12 grid max-w-[1000px] grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-4">
            @forelse ($featuredProducts as $product)
                <article class="group text-center">
                    <div class="relative aspect-square overflow-hidden bg-cream">
                        <x-product-image :product="$product" class="h-full w-full" />
                        <div class="absolute inset-0 flex items-center justify-center gap-3 bg-[rgb(231_76_60/0.6)] opacity-0 transition duration-300 group-hover:opacity-100 group-focus-within:opacity-100">
                            <a href="{{ route('products.show', $product) }}" class="flex h-9 w-9 items-center justify-center bg-white text-ink hover:text-brand" aria-label="عرض {{ $product->name }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            @if ($product->isInStock())
                                <form method="POST" action="{{ route('cart.store', $product) }}">
                                    @csrf
                                    <button class="flex h-9 w-9 items-center justify-center bg-white text-ink hover:text-brand" aria-label="أضف {{ $product->name }} للسلة">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('products.show', $product) }}" class="mt-5 block text-[13px] leading-[17px] font-bold text-ink-soft uppercase hover:text-brand">{{ $product->name }}</a>
                    <x-price :amount="$product->price" class="mt-1.5 block text-[11px] font-bold text-muted" />
                </article>
            @empty
                @foreach (range(1, 4) as $slot)
                    <div class="text-center" aria-hidden="true">
                        <div class="aspect-square bg-cream"></div>
                        <span class="{{ $placeholder }} mx-auto mt-5 h-2.5 w-24"></span>
                        <span class="{{ $placeholder }} mx-auto mt-2 h-2 w-14"></span>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>

    {{-- ٥. آراء العملاء على قماش المربعات (Frame 16) --}}
    <section class="gingham relative flex min-h-[420px] items-center justify-center px-6 py-20">
        <div class="relative w-full max-w-[560px] text-center" data-slider>
            @if (filled($text['testimonials_title']))
                <h2 class="squiggle text-center text-[30px] leading-tight text-ink sm:text-[34px]">{{ $text['testimonials_title'] }}</h2>
            @else
                <div class="mx-auto h-0.5 w-[90px] bg-brand" aria-hidden="true"></div>
            @endif
            @forelse ($text['testimonials'] as [$quote, $name, $role])
                <figure class="mt-12 {{ $loop->first ? '' : 'hidden' }}" data-slide>
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-full bg-white font-display text-2xl text-brand">{{ mb_substr($name, 0, 1) }}</div>
                    <blockquote class="mt-6 text-sm leading-6 text-ink">“{{ $quote }}”</blockquote>
                    <figcaption class="mt-4">
                        <p class="text-[15px] text-ink uppercase">{{ $name }}</p>
                        <p class="mt-1.5 text-[11px] text-body uppercase">{{ $role }}</p>
                    </figcaption>
                </figure>
            @empty
                <div class="mt-12" aria-hidden="true">
                    <div class="mx-auto h-[72px] w-[72px] rounded-full bg-white/90"></div>
                    <span class="mx-auto mt-6 block h-2.5 w-72 max-w-full bg-white/90"></span>
                    <span class="mx-auto mt-2 block h-2.5 w-56 max-w-full bg-white/90"></span>
                    <span class="mx-auto mt-5 block h-2.5 w-24 bg-white/90"></span>
                </div>
            @endforelse
            @if (count($text['testimonials']) > 1)
                <div class="mt-8 flex justify-center gap-2" aria-label="الآراء">
                    @foreach ($text['testimonials'] as $index => $testimonial)
                        <button type="button" class="h-[5px] w-[5px] rounded-full {{ $loop->first ? 'bg-brand' : 'bg-ink/40' }}" data-slide-to="{{ $index }}" aria-label="رأي {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ٦. المنتجات حسب القسم (Frame 19: Recent Work) --}}
    <section class="bg-white px-6 py-20" data-filter-group>
        @include('partials.section-heading', ['title' => $text['work_title'], 'subtitle' => $text['work_text']])
        @if ($categories->isNotEmpty())
            <nav class="mt-10 flex flex-wrap justify-center gap-x-6 gap-y-2" aria-label="الأقسام">
                <button type="button" class="chip-active" data-filter="all">الكل</button>
                @foreach ($categories as $category)
                    <button type="button" class="chip" data-filter="{{ $category->id }}">{{ $category->name }}</button>
                @endforeach
            </nav>
        @endif
        <div class="mx-auto mt-10 grid max-w-[1000px] grid-cols-2 lg:grid-cols-4">
            @forelse ($gridProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="group relative block aspect-square overflow-hidden bg-cream" data-category="{{ $product->category_id }}">
                    <x-product-image :product="$product" class="h-full w-full transition duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/80 px-4 text-center opacity-0 transition duration-300 group-hover:opacity-100 group-focus-visible:opacity-100">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full border border-white text-white" aria-hidden="true">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                        </span>
                        <span class="mt-5 text-base leading-5 font-bold text-white uppercase">{{ $product->name }}</span>
                        <span class="my-2.5 h-0.5 w-[35px] bg-brand" aria-hidden="true"></span>
                        <span class="text-[11px] font-bold text-muted uppercase">{{ $product->category->name }}</span>
                    </div>
                </a>
            @empty
                @foreach (range(1, 8) as $slot)
                    <div class="aspect-square {{ $slot % 2 ? 'bg-cream' : 'bg-sand/60' }}" aria-hidden="true"></div>
                @endforeach
            @endforelse
        </div>
        <div class="mt-14 text-center">
            <a href="{{ route('shop') }}" class="btn-outline h-12 min-h-12 w-[183px] px-0" @if (blank($text['work_button'])) aria-label="المتجر" @endif>{{ filled($text['work_button']) ? $text['work_button'] : '←' }}</a>
        </div>
    </section>

    {{-- ٧. المميزات (Frame 22) --}}
    <section class="grid bg-white lg:grid-cols-2">
        <div class="px-6 py-20 sm:px-16">
            @if (filled($text['features_title']))
                <h2 class="text-[30px] leading-tight font-bold text-black sm:text-[34px]" style="font-family: var(--font-sans)">{{ $text['features_title'] }}</h2>
            @else
                <span class="{{ $placeholder }} h-4 w-56" aria-hidden="true"></span>
            @endif
            @if (filled($text['features_text']))
                <p class="mt-5 max-w-[520px] text-sm leading-6 text-black">{{ $text['features_text'] }}</p>
            @endif
            <ul class="mt-12 space-y-7">
                @forelse ($text['features'] as [$heading, $body])
                    <li class="flex gap-5">
                        @include('partials.feature-icon')
                        <div>
                            <p class="text-[13px] leading-5 font-bold text-ink-soft uppercase">{{ $heading }}</p>
                            <p class="mt-1.5 max-w-[460px] text-[15px] leading-[25px] font-bold text-muted">{{ $body }}</p>
                        </div>
                    </li>
                @empty
                    @foreach (range(1, 4) as $slot)
                        <li class="flex items-center gap-5" aria-hidden="true">
                            @include('partials.feature-icon')
                            <div class="flex-1">
                                <span class="{{ $placeholder }} h-2.5 w-28"></span>
                                <span class="{{ $placeholder }} mt-3 h-2 w-full max-w-[420px]"></span>
                                <span class="{{ $placeholder }} mt-2 h-2 w-3/4 max-w-[320px]"></span>
                            </div>
                        </li>
                    @endforeach
                @endforelse
            </ul>
        </div>
        <div class="min-h-[360px] bg-cream bg-cover bg-center" style="background-image: url('{{ asset('images/alo/history-2.jpg') }}')" aria-hidden="true"></div>
    </section>

    {{-- ٨. العروض على الخلفية الداكنة (Frame 23: Pricing Tables) --}}
    <section class="relative bg-ink bg-cover bg-center px-6 py-20" style="background-image: url('{{ asset('images/alo/dark-texture.jpg') }}')">
        <div class="absolute inset-0 bg-black/40" aria-hidden="true"></div>
        <div class="relative">
            @include('partials.section-heading', ['title' => $text['offers_title'], 'subtitle' => $text['offers_text'], 'light' => true])
            <div class="mx-auto mt-14 grid max-w-[1040px] gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($offerProducts as $product)
                    <article class="flex flex-col bg-white text-center">
                        <p class="flex h-[52px] items-center justify-center px-4 text-base leading-5 font-bold text-white uppercase {{ $loop->index === 2 ? 'bg-brand' : 'bg-ink-soft' }}">{{ $product->name }}</p>
                        <a href="{{ route('products.show', $product) }}" class="mx-auto mt-5 block aspect-square w-28 overflow-hidden">
                            <x-product-image :product="$product" class="h-full w-full" />
                        </a>
                        <p class="mt-3 text-[48px] leading-[60px] text-black" dir="ltr">
                            <span class="align-top text-xl leading-10 text-ink-soft">{{ config('store.currency') }}</span>{{ rtrim(rtrim(number_format((float) $product->price, 2), '0'), '.') }}
                        </p>
                        @if ($product->isOnSale())
                            <x-price :amount="$product->compare_at_price" class="text-xs text-muted line-through" />
                        @else
                            <span class="text-xs text-muted">{{ $product->category->name }}</span>
                        @endif
                        @if ($product->description)
                            <p class="mx-auto mt-4 max-w-[200px] text-[13px] leading-6 text-muted">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                        @endif
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-auto pt-6 pb-6">
                            @csrf
                            <button class="btn-soft h-[35px] min-h-[35px] w-[130px] px-0" @disabled(! $product->isInStock())>أضف للسلة</button>
                        </form>
                    </article>
                @empty
                    @foreach (range(1, 4) as $slot)
                        <div class="flex flex-col items-center bg-white pb-8" aria-hidden="true">
                            <div class="h-[52px] w-full {{ $slot === 3 ? 'bg-brand' : 'bg-ink-soft' }}"></div>
                            <span class="{{ $placeholder }} mt-8 h-10 w-24"></span>
                            <span class="{{ $placeholder }} mt-3 h-2 w-14"></span>
                            @foreach (range(1, 5) as $line)
                                <span class="{{ $placeholder }} mt-4 h-2 w-28"></span>
                            @endforeach
                            <div class="mt-8 h-[35px] w-[130px] bg-ink-soft"></div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.store>
