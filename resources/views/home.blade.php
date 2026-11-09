<x-layouts.store :full="true">
    @php($hero = config('content.hero'))
    @php($story = config('content.story'))
    @php($steps = config('content.steps'))

    {{-- ١. البنر المنقّط: عنوان سيريف مع كلمات بخط اليد وستيكرات حوله --}}
    <section class="dots relative overflow-hidden border-b border-dashed border-ink/25">
        <div class="relative mx-auto flex min-h-[460px] max-w-[1240px] flex-col items-center justify-center px-6 py-20 text-center sm:min-h-[560px]">
            <div class="relative rounded-[28px] bg-paper/85 px-6 py-8 sm:px-12 sm:py-10">
                @if (filled($hero['note'] ?? null))
                    <p class="-rotate-3 font-script text-xl text-ink sm:text-2xl" dir="ltr">{{ $hero['note'] }}</p>
                @endif
                @if (filled($hero['kicker'] ?? null))
                    <p class="kicker mt-2 text-ink">({{ $hero['kicker'] }})</p>
                @endif

                <h1 class="mt-5 text-[clamp(2.2rem,6.4vw,4.6rem)] leading-[1.15] text-ink">
                    @foreach ($hero['lines'] ?? [] as $line)
                        <span class="block"><x-script-text :text="$line" />@if ($loop->last) <x-doodle name="pixel-heart" class="inline-block h-[0.42em] w-[0.5em] align-middle" />@endif</span>
                    @endforeach
                </h1>

                @if (filled($hero['button'] ?? null))
                    <a href="{{ route('shop') }}" class="btn-soft mt-8 min-h-10 px-8">{{ $hero['button'] }}</a>
                @endif

                {{-- ستيكرات حول العنوان --}}
                <x-doodle name="heart-bubble" class="float-slow absolute -top-4 left-[12%] h-10 w-11 [--r:-6deg] sm:h-12 sm:w-14" />
                @if (filled($hero['tag'] ?? null))
                    <span class="absolute top-1/2 -right-3 -rotate-6 rounded-full bg-sky px-3.5 py-1 text-xs font-bold text-ink shadow-sm sm:-right-10" dir="ltr">{{ $hero['tag'] }}</span>
                @endif
                <x-doodle name="cursor" class="absolute bottom-[28%] -left-2 h-7 w-6 sm:-left-8" />
            </div>

            <img src="{{ asset('images/alo/cat-sticker.png') }}" alt="" class="pointer-events-none absolute bottom-0 left-4 w-[24%] max-w-[190px] min-w-[96px] translate-y-[38%] -rotate-6 drop-shadow-md sm:left-10" width="392" height="288">
            <x-doodle name="star" class="float-slow absolute top-12 right-[8%] hidden h-8 w-8 [--r:12deg] sm:block" />
            <x-doodle name="star" class="absolute top-24 right-[13%] hidden h-5 w-5 rotate-12 sm:block" />
        </div>
    </section>

    {{-- ٢. الشريط الأزرق المتحرك بأسماء الأقسام --}}
    @php($ribbonWords = $categories->pluck('name')->merge(config('content.ribbon', []))->filter()->values())
    @if ($ribbonWords->isNotEmpty())
        @php($ribbonIcons = ['flower', 'cloud', 'outline-star', 'chat'])
        <div class="overflow-hidden border-b border-dashed border-ink/25 bg-sky py-2.5 text-sm text-ink" aria-hidden="true">
            <div class="marquee-track flex w-max" dir="ltr">
                @foreach (range(1, 2) as $copy)
                    <div class="flex shrink-0">
                        @foreach (range(1, max(1, (int) ceil(16 / $ribbonWords->count()))) as $repeat)
                            @foreach ($ribbonWords as $word)
                                <span class="flex items-center gap-3 px-4 font-display whitespace-nowrap" dir="auto">
                                    <x-doodle :name="$ribbonIcons[$loop->index % 4]" class="h-4 w-4 text-ink/70" />{{ $word }}
                                </span>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ٣. المنتجات المميزة --}}
    <section class="px-6 pt-20 pb-20">
        <div class="mx-auto max-w-[1100px]">
            <div class="mb-10 text-center">
                <p class="kicker text-brand">new on the table</p>
                <h2 class="mt-3 text-[clamp(2rem,4.5vw,3.2rem)] leading-tight"><span class="script-accent text-brand">Fresh</span> من الطاولة</h2>
            </div>
            @if ($categories->isNotEmpty())
                <nav class="mb-12 flex flex-wrap justify-center gap-2.5" aria-label="الأقسام">
                    <a href="{{ route('shop') }}" class="chip-active">الكل</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('shop', ['category' => $category->slug]) }}" class="chip" dir="auto">{{ $category->name }}</a>
                    @endforeach
                    <a href="{{ route('shop', ['offers' => 1]) }}" class="chip">Offers</a>
                </nav>
            @endif
            <div class="grid grid-cols-2 gap-x-5 gap-y-12 lg:grid-cols-4 lg:gap-x-8">
                @forelse ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    @foreach (range(1, 4) as $slot)
                        <div class="sticker"><div class="aspect-square bg-cream"></div></div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- ٤. البنر البني: الحكاية مع صور بولارويد --}}
    @php($polaroids = $gridProducts->filter(fn ($p) => filled($p->image))->take(3))
    @php
        $polaroidStyles = [
            ['rotate' => '-rotate-6', 'bg' => 'bg-paper', 'shift' => 'translate-y-6'],
            ['rotate' => 'rotate-3', 'bg' => 'bg-butter', 'shift' => '-translate-y-2'],
            ['rotate' => '-rotate-2', 'bg' => 'bg-blush', 'shift' => 'translate-y-8'],
        ];
    @endphp
    <section class="relative overflow-hidden bg-cocoa px-6 pt-20 pb-28 text-paper">
        <div class="mx-auto max-w-[1100px] text-center">
            @if (filled($story['stamp'] ?? null))
                <span class="stamp absolute top-10 right-[6%] hidden -rotate-12 sm:inline-block" dir="ltr">{{ $story['stamp'] }}</span>
            @endif
            <x-doodle name="cherries" class="absolute top-16 left-[8%] h-12 w-12 rotate-12 sm:h-16 sm:w-16" />
            <x-doodle name="sparkles" class="absolute top-[52%] right-[5%] h-9 w-10 sm:right-[9%]" />

            @if (filled($story['kicker'] ?? null))
                <p class="kicker text-paper/70">{{ $story['kicker'] }}</p>
            @endif
            <h2 class="mt-4 text-[clamp(2rem,5vw,3.4rem)] leading-tight text-paper"><x-script-text :text="$story['title'] ?? ''" /></h2>
            @if (filled($story['text'] ?? null))
                <p class="mx-auto mt-5 max-w-xl text-[15px] leading-8 text-paper/75">{{ $story['text'] }}</p>
            @endif

            <div class="relative mx-auto mt-14 flex max-w-[860px] items-start justify-center gap-4 sm:gap-8">
                @foreach ($polaroids->isNotEmpty() ? $polaroids : collect([null, null, null]) as $item)
                    @php($style = $polaroidStyles[$loop->index])
                    <figure class="polaroid {{ $style['bg'] }} {{ $style['rotate'] }} {{ $style['shift'] }} relative w-1/3 max-w-[240px] transition duration-300 hover:z-10 hover:rotate-0 {{ $loop->index === 1 ? '' : 'hidden min-[480px]:block' }} {{ $loop->index === 1 ? 'max-[479px]:w-2/3' : '' }}">
                        <span class="washi -top-3 left-1/2 -translate-x-1/2 -rotate-3" aria-hidden="true"></span>
                        <div class="aspect-[4/5] overflow-hidden bg-cream">
                            @if ($item)
                                <a href="{{ route('products.show', $item) }}"><x-product-image :product="$item" class="h-full w-full" /></a>
                            @endif
                        </div>
                        <figcaption class="absolute inset-x-0 bottom-3 font-script text-xl text-ink" dir="ltr">{{ $story['caption'] ?? '' }}</figcaption>
                    </figure>
                @endforeach
                <x-doodle name="badge" id="story" class="absolute -top-8 -left-2 h-20 w-20 rotate-12 sm:-left-6 sm:h-24 sm:w-24" />
            </div>

            @if (filled($story['button'] ?? null))
                <a href="{{ route('pages.about') }}" class="btn-outline-light mt-20">{{ $story['button'] }}</a>
            @endif
        </div>
    </section>

    {{-- ٥. بنر العروض الوردي (مثل غلاف الكولاج) --}}
    @if ($offerProducts->isNotEmpty())
        @php($offers = config('content.offers'))
        @php($maxDiscount = $offerProducts->max(fn ($p) => $p->discountPercent()))
        <section class="relative overflow-hidden bg-blush px-6 py-20">
            <x-doodle name="star" class="absolute top-8 left-[6%] h-8 w-8 -rotate-12" />
            <x-doodle name="star" class="absolute top-16 left-[11%] h-5 w-5 rotate-6" />
            <x-doodle name="star" class="absolute top-6 left-[15%] h-6 w-6" />
            <x-doodle name="star" class="absolute right-[5%] bottom-10 h-7 w-7 rotate-12" />
            <div class="mx-auto max-w-[1100px]">
                <div class="flex flex-wrap items-end justify-between gap-8">
                    <div>
                        <h2 class="font-script text-[clamp(4.5rem,13vw,9rem)] leading-[0.8] text-wine" dir="ltr">{{ $offers['title'] ?? 'Offers' }}</h2>
                        @if (filled($offers['subtitle'] ?? null))
                            <p class="mt-5 text-base text-body">{{ $offers['subtitle'] }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <div class="rounded-full bg-white px-7 py-3 text-center shadow-sm">
                            <p class="text-xs text-muted">خصم حتى</p>
                            <p class="font-display text-2xl leading-tight text-ink">{{ $maxDiscount }}٪</p>
                        </div>
                        <div class="-rotate-2 rounded-full bg-white px-7 py-3 text-center shadow-sm">
                            <p class="text-xs text-muted">قطع بالعرض</p>
                            <p class="font-display text-2xl leading-tight text-ink">{{ $offerProducts->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-14 grid grid-cols-2 gap-x-5 gap-y-12 lg:grid-cols-4 lg:gap-x-8">
                    @foreach ($offerProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
                @if (filled($offers['button'] ?? null))
                    <div class="mt-12 text-center">
                        <a href="{{ route('shop', ['offers' => 1]) }}" class="btn-primary">{{ $offers['button'] }}</a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ٦. البنر المقلّم أزرق وكريمي: ثلاث خطوات --}}
    @if (! empty($steps['items']))
        <section class="stripes px-6 py-20">
            <div class="mx-auto max-w-[1000px] rounded-[28px] border border-dashed border-ink/25 bg-paper px-6 py-12 text-center sm:px-12">
                @if (filled($steps['kicker'] ?? null))
                    <p class="kicker text-brand">{{ $steps['kicker'] }}</p>
                @endif
                <h2 class="mt-3 text-[clamp(1.9rem,4.2vw,3rem)] leading-tight"><x-script-text :text="$steps['title'] ?? ''" /></h2>
                <ol class="mt-10 grid gap-6 text-start sm:grid-cols-3">
                    @foreach ($steps['items'] as $step)
                        <li class="rounded-2xl bg-white p-6">
                            <span class="flex h-14 w-14 items-center justify-center rounded-xl bg-blush font-script text-3xl text-wine" dir="ltr">{{ $loop->iteration }}</span>
                            <h3 class="mt-5 text-xl">{{ $step[0] ?? '' }}</h3>
                            <p class="mt-2 text-sm leading-7 text-body">{{ $step[1] ?? '' }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- ٧. منتجات إضافية --}}
    @php($moreProducts = $gridProducts->whereNotIn('id', $featuredProducts->modelKeys())->take(4))
    @if ($moreProducts->isNotEmpty())
        <section class="dots-soft px-6 py-20">
            <div class="mx-auto max-w-[1100px]">
                <div class="mb-12 flex flex-wrap items-end justify-between gap-4">
                    <h2 class="text-[clamp(1.9rem,4vw,2.8rem)] leading-tight">قطع <span class="script-accent text-brand">جديدة</span></h2>
                    <a href="{{ route('shop') }}" class="btn-outline min-h-10">كل المنتجات ←</a>
                </div>
                <div class="grid grid-cols-2 gap-x-5 gap-y-12 lg:grid-cols-4 lg:gap-x-8">
                    @foreach ($moreProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ٨. بنر المجتمع --}}
    @php($community = config('content.community'))
    @php($instagram = config('content.social.instagram'))
    @if (filled($community['title'] ?? null))
        <section class="bg-paper px-6 py-20">
            <div class="relative mx-auto flex max-w-[1100px] flex-wrap items-center justify-between gap-8 overflow-hidden rounded-[28px] bg-ink px-8 py-12 text-paper sm:px-14">
                <div class="max-w-lg">
                    <h2 class="text-[clamp(2rem,4.5vw,3.2rem)] leading-tight text-paper"><x-script-text :text="$community['title']" /></h2>
                    @if (filled($community['text'] ?? null))
                        <p class="mt-4 text-[15px] leading-7 text-paper/70">{{ $community['text'] }}</p>
                    @endif
                </div>
                <a href="{{ $instagram ?: route('pages.contact') }}" @if ($instagram) target="_blank" rel="noopener" @endif class="btn-soft">{{ $community['button'] ?? 'تابعينا' }}</a>
                <x-doodle name="badge" id="community" text="join the club · alo ·" class="absolute -bottom-6 left-[40%] hidden h-24 w-24 -rotate-12 md:block" />
            </div>
        </section>
    @endif
</x-layouts.store>
