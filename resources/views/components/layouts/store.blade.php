@props(['title' => null, 'description' => null, 'overlay' => false, 'full' => false])
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' | ' : '' }}Alo</title>
    <meta name="description" content="{{ $description ?? 'متجر Alo: ستيكرات وتشارمز صغيرة صنعتها أختان' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Aref+Ruqaa:wght@400;700&family=Gloock&family=Lato:wght@400;700&family=Pinyon+Script&family=Space+Mono:wght@400;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col font-sans antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:right-2 focus:z-50 focus:bg-white focus:px-3 focus:py-2">تخطَّ للمحتوى</a>

    @php($announcements = array_values(array_filter(config('content.announcements', []))))
    @if ($announcements)
        <div class="marquee relative z-40 overflow-hidden bg-olive py-2 text-[12px] font-bold text-paper" role="region" aria-label="إعلانات">
            <p class="sr-only">{{ implode(' · ', $announcements) }}</p>
            <div class="marquee-track flex w-max" dir="ltr" aria-hidden="true">
                @foreach (range(1, 2) as $copy)
                    <div class="flex shrink-0">
                        @foreach (range(1, max(1, (int) ceil(20 / count($announcements)))) as $repeat)
                            @foreach ($announcements as $announcement)
                                <span class="flex items-center gap-8 px-8 whitespace-nowrap" dir="rtl">{{ $announcement }}<span class="text-[10px] text-butter">✦</span></span>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- الهيدر: روابط يمين، الشعار بالنص، والسلة يسار. تحته خط منقّط مثل التصميم --}}
    @php($navCategories = \App\Models\Category::query()->orderBy('sort_order')->get())
    <header class="sticky top-0 z-30 border-b border-dashed border-ink/30 bg-paper/95 backdrop-blur">
        <div class="mx-auto grid max-w-[1240px] grid-cols-[1fr_auto_1fr] items-center gap-4 px-5 py-3.5">
            <div class="flex items-center gap-7">
                <button type="button" class="p-1 md:hidden" data-menu-toggle="mobile-menu" aria-label="القائمة">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <nav class="hidden items-center gap-6 text-[13px] font-bold md:flex" aria-label="الرئيسية">
                    <a href="{{ route('shop') }}" class="hover:text-brand">المتجر</a>
                    @foreach ($navCategories as $navCategory)
                        <a href="{{ route('shop', ['category' => $navCategory->slug]) }}" class="hover:text-brand" dir="ltr">{{ $navCategory->name }}</a>
                    @endforeach
                    <a href="{{ route('shop', ['offers' => 1]) }}" class="font-script text-lg leading-none text-brand" dir="ltr">Offers</a>
                </nav>
            </div>

            <a href="{{ route('home') }}" class="flex items-baseline gap-1 leading-none" dir="ltr" aria-label="Alo الرئيسية">
                <span class="font-display text-[30px] text-ink">alo</span>
                <x-doodle name="pixel-heart" class="h-3 w-3.5" />
            </a>

            <div class="flex items-center justify-end gap-2">
                <form action="{{ route('shop') }}" method="GET" class="hidden lg:block" role="search">
                    <label for="header-search" class="sr-only">ابحث عن منتج</label>
                    <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="ابحث…" class="input w-40 rounded-full py-1.5 text-sm">
                </form>
                <a href="{{ route('pages.about') }}" class="hidden px-2 text-[13px] font-bold hover:text-brand md:inline">من نحن</a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden px-2 text-[13px] font-bold text-brand hover:underline sm:inline">لوحة التحكم</a>
                    @endif
                    <a href="{{ route('account') }}" class="p-1" aria-label="حسابي" title="حسابي">
                        <x-avatar :user="auth()->user()" size="h-8 w-8 text-sm" />
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden px-2 text-[13px] font-bold hover:text-brand sm:inline">دخول</a>
                @endauth
                <a href="{{ route('cart.index') }}" class="inline-flex h-8 items-center gap-1.5 rounded-full border border-dashed border-ink/50 bg-paper px-3.5 text-[13px] font-bold transition hover:border-solid hover:border-brand hover:text-brand" aria-label="السلة ({{ $cartCount }})">
                    <span>السلة</span>
                    <span class="flex h-5 min-w-5 items-center justify-center rounded-full {{ $cartCount > 0 ? 'bg-brand text-paper' : 'bg-cream text-muted' }} px-1 text-[11px]">{{ $cartCount }}</span>
                </a>
            </div>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-dashed border-ink/20 bg-paper px-6 py-4 md:hidden" aria-label="قائمة الجوال">
            <form action="{{ route('shop') }}" method="GET" class="mb-3" role="search">
                <label for="mobile-search" class="sr-only">ابحث عن منتج</label>
                <input id="mobile-search" type="search" name="q" placeholder="ابحث…" class="input rounded-full text-sm">
            </form>
            <div class="flex flex-col text-sm font-bold">
                <a href="{{ route('home') }}" class="py-2 hover:text-brand">الرئيسية</a>
                <a href="{{ route('shop') }}" class="py-2 hover:text-brand">المتجر</a>
                @foreach ($navCategories as $navCategory)
                    <a href="{{ route('shop', ['category' => $navCategory->slug]) }}" class="py-2 hover:text-brand">{{ $navCategory->name }}</a>
                @endforeach
                <a href="{{ route('shop', ['offers' => 1]) }}" class="py-2 text-brand">Offers</a>
                <a href="{{ route('pages.about') }}" class="py-2 hover:text-brand">من نحن</a>
                <a href="{{ route('pages.contact') }}" class="py-2 hover:text-brand">تواصل معنا</a>
                @auth
                    <a href="{{ route('account') }}" class="py-2 hover:text-brand">حسابي</a>
                @else
                    <a href="{{ route('login') }}" class="py-2 hover:text-brand">دخول</a>
                @endauth
            </div>
        </nav>
    </header>

    <main id="main" class="{{ $overlay || $full ? 'w-full flex-1' : 'mx-auto w-full max-w-[1240px] flex-1 px-6 py-10' }}">
        @if ($overlay || $full)
            <div class="absolute inset-x-0 z-20 mx-auto mt-4 max-w-[1240px] px-6"><x-flash /></div>
        @else
            <x-flash />
        @endif
        {{ $slot }}
    </main>

    {{-- الفوتر: وردي مع اسم المتجر كبير (مثل "guide me social") --}}
    <footer class="mt-auto overflow-hidden bg-blush text-ink">
        <div class="mx-auto max-w-[1240px] px-6 pt-16">
            <div class="flex flex-wrap items-start justify-between gap-10">
                <div class="max-w-sm">
                    <p class="kicker text-brand">stickers · charms · bookmarks</p>
                    @if (filled(config('content.footer_text')))
                        <p class="mt-4 text-[15px] leading-7 text-body">{{ config('content.footer_text') }}</p>
                    @endif
                    @php($social = array_filter(config('content.social', [])))
                    @if ($social)
                        <div class="mt-6 flex gap-3" dir="ltr">
                            @foreach ($social as $network => $url)
                                <a href="{{ $url }}" class="flex h-9 w-9 items-center justify-center rounded-full border border-dashed border-wine/50 text-wine transition hover:border-solid hover:bg-wine hover:text-paper" aria-label="{{ $network }}" rel="noopener" target="_blank">
                                    @include('partials.social-icon', ['network' => $network])
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <nav class="grid grid-cols-2 gap-x-14 gap-y-3 text-sm font-bold" aria-label="روابط الفوتر">
                    <a href="{{ route('home') }}" class="hover:text-brand">الرئيسية</a>
                    <a href="{{ route('pages.about') }}" class="hover:text-brand">من نحن</a>
                    <a href="{{ route('shop') }}" class="hover:text-brand">المتجر</a>
                    <a href="{{ route('pages.policies') }}" class="hover:text-brand">السياسات</a>
                    <a href="{{ route('shop', ['offers' => 1]) }}" class="hover:text-brand">العروض</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-brand">تواصل معنا</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="font-bold hover:text-brand">تسجيل الخروج</button></form>
                    @endauth
                </nav>
            </div>

            <div class="mt-12 flex flex-wrap items-center justify-between gap-4 border-t border-dashed border-wine/30 py-5 text-xs text-body">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach (array_filter(\App\Enums\PaymentMethod::cases(), fn ($method) => $method->isOnline()) as $method)
                        <x-payment-badge :method="$method" size="h-7" />
                    @endforeach
                </div>
                <p class="font-mono" dir="ltr">© {{ date('Y') }} alo · made with love</p>
            </div>
        </div>

        <p class="-mb-[0.22em] flex items-baseline justify-center gap-[0.04em] text-center leading-[0.8] text-wine select-none" dir="ltr" aria-hidden="true">
            <span class="font-display text-[clamp(7rem,30vw,22rem)]">alo</span>
            <span class="font-script text-[clamp(5rem,22vw,16rem)]">store</span>
        </p>
    </footer>
</body>
</html>
