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
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:wght@500;700&family=Lato:wght@400;700&family=Sansation:wght@400;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col font-sans antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:right-2 focus:z-50 focus:bg-white focus:px-3 focus:py-2">تخطَّ للمحتوى</a>

    @php($announcements = array_values(array_filter(config('content.announcements', []))))
    @if ($announcements)
        <div class="marquee relative z-40 overflow-hidden bg-[#fbe1e6] py-2.5 text-[13px] font-bold text-[#5a3a42]" role="region" aria-label="إعلانات">
            <p class="sr-only">{{ implode(' · ', $announcements) }}</p>
            <div class="marquee-track flex w-max" dir="ltr" aria-hidden="true">
                @foreach (range(1, 2) as $copy)
                    <div class="flex shrink-0">
                        @foreach (range(1, max(1, (int) ceil(20 / count($announcements)))) as $repeat)
                            @foreach ($announcements as $announcement)
                                <span class="flex items-center gap-8 px-8 whitespace-nowrap" dir="rtl">{{ $announcement }}<span class="text-[10px] text-[#e7a3b2]">✦</span></span>
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- الهيدر: فوق البنر في الرئيسية (مثل Figma)، وأبيض في باقي الصفحات --}}
    <header class="{{ $overlay ? 'absolute inset-x-0 top-0' : 'sticky top-0 border-b border-sand bg-white' }} z-30">
        <div class="mx-auto flex max-w-[1240px] items-center gap-6 px-6 py-4">
            <button type="button" class="p-1 md:hidden" data-menu-toggle="mobile-menu" aria-label="القائمة">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>

            <a href="{{ route('home') }}" class="font-latin text-[28px] leading-none font-bold text-wine" dir="ltr">ALO</a>

            <nav class="hidden flex-1 items-center justify-center gap-9 text-[13px] leading-4 font-bold text-ink md:flex" aria-label="الرئيسية">
                <a href="{{ route('home') }}" class="hover:text-brand">الرئيسية</a>
                <a href="{{ route('shop') }}" class="hover:text-brand">المتجر</a>
                @foreach (\App\Models\Category::query()->orderBy('sort_order')->get() as $navCategory)
                    <a href="{{ route('shop', ['category' => $navCategory->slug]) }}" class="hover:text-brand" dir="ltr">{{ $navCategory->name }}</a>
                @endforeach
                <a href="{{ route('shop', ['offers' => 1]) }}" class="text-brand hover:underline" dir="ltr">Offers</a>
                <a href="{{ route('pages.contact') }}" class="hover:text-brand">تواصل معنا</a>
            </nav>

            <div class="ms-auto flex items-center gap-2 md:ms-0">
                <form action="{{ route('shop') }}" method="GET" class="hidden lg:block" role="search">
                    <label for="header-search" class="sr-only">ابحث عن منتج</label>
                    <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="ابحث…" class="input w-44 bg-white/80 py-2 text-sm">
                </form>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-2 text-[13px] font-bold text-brand hover:underline">لوحة التحكم</a>
                    @endif
                    <a href="{{ route('account') }}" class="p-1" aria-label="حسابي" title="حسابي">
                        <x-avatar :user="auth()->user()" size="h-9 w-9 text-base" />
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-2 text-[13px] font-bold hover:text-brand">دخول</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative p-2 hover:text-brand" aria-label="السلة ({{ $cartCount }})">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12l1 13H5L6 7zm3 0a3 3 0 016 0"/></svg>
                    @if ($cartCount > 0)
                        <span class="absolute top-0 left-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-brand px-1 text-[11px] font-bold text-white">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-sand bg-white px-6 py-4 md:hidden" aria-label="قائمة الجوال">
            <form action="{{ route('shop') }}" method="GET" class="mb-3" role="search">
                <label for="mobile-search" class="sr-only">ابحث عن منتج</label>
                <input id="mobile-search" type="search" name="q" placeholder="ابحث…" class="input text-sm">
            </form>
            <div class="flex flex-col text-[13px] font-bold">
                <a href="{{ route('home') }}" class="py-2 hover:text-brand">الرئيسية</a>
                <a href="{{ route('shop') }}" class="py-2 hover:text-brand">المتجر</a>
                @foreach (\App\Models\Category::query()->orderBy('sort_order')->get() as $navCategory)
                    <a href="{{ route('shop', ['category' => $navCategory->slug]) }}" class="py-2 hover:text-brand">{{ $navCategory->name }}</a>
                @endforeach
                <a href="{{ route('shop', ['offers' => 1]) }}" class="py-2 text-brand">Offers</a>
                <a href="{{ route('pages.contact') }}" class="py-2 hover:text-brand">تواصل معنا</a>
                @auth
                    <a href="{{ route('account') }}" class="py-2 hover:text-brand">حسابي</a>
                @endauth
            </div>
        </nav>
    </header>

    <main id="main" class="{{ $overlay || $full ? 'w-full flex-1' : 'mx-auto w-full max-w-[1240px] flex-1 px-6 py-10' }}">
        @if ($overlay || $full)
            <div class="absolute inset-x-0 top-24 z-20 mx-auto max-w-[1240px] px-6"><x-flash /></div>
        @else
            <x-flash />
        @endif
        {{ $slot }}
    </main>

    {{-- الفوتر (Frame 30) --}}
    <footer class="mt-auto bg-white">
        <div class="mx-auto max-w-[717px] px-6 pt-24 pb-20 text-center">
            <p class="font-latin text-[44px] leading-none font-bold text-wine" dir="ltr">ALO</p>
            @if (filled(config('content.footer_text')))
                <p class="mt-9 text-[15px] leading-6 text-body">{{ config('content.footer_text') }}</p>
            @endif
            @php($social = array_filter(config('content.social', [])))
            @if ($social)
                <div class="mt-9 flex justify-center gap-4" dir="ltr">
                    @foreach ($social as $network => $url)
                        <a href="{{ $url }}" class="flex h-[35px] w-[35px] items-center justify-center rounded-full bg-[#3a3a3a] text-white transition hover:bg-brand" aria-label="{{ $network }}" rel="noopener" target="_blank">
                            @include('partials.social-icon', ['network' => $network])
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="bg-ink-soft">
            <div class="mx-auto flex max-w-[1240px] flex-wrap items-center justify-between gap-4 px-6 py-6 text-xs leading-[14px] text-[#f7f7f7]">
                <nav class="flex flex-wrap gap-x-6 gap-y-2" aria-label="روابط الفوتر">
                    <a href="{{ route('home') }}" class="hover:text-white">الرئيسية</a>
                    <a href="{{ route('shop') }}" class="hover:text-white">المتجر</a>
                    <a href="{{ route('pages.policies') }}" class="hover:text-white">السياسات</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-white">تواصل معنا</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="hover:text-white">تسجيل الخروج</button></form>
                    @endauth
                </nav>
                <div class="flex flex-wrap items-center gap-2">
                    @foreach (\App\Enums\PaymentMethod::cases() as $method)
                        <span class="border border-white/20 px-2 py-1 text-[11px]">{{ $method->label() }}</span>
                    @endforeach
                </div>
                <p dir="ltr">© {{ date('Y') }} ALO</p>
            </div>
        </div>
    </footer>
</body>
</html>
