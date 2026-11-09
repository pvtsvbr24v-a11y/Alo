@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' | ' : '' }}Alo</title>
    <meta name="description" content="{{ $description ?? 'متجر Alo: ستيكرات وتشارمز' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col font-sans antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:right-2 focus:z-50 focus:bg-white focus:px-3 focus:py-2">تخطَّ للمحتوى</a>

    <header class="sticky top-0 z-30 border-b border-sand bg-white">
        <div class="mx-auto flex max-w-6xl items-center gap-6 px-6 py-4">
            <button type="button" class="p-1 md:hidden" data-menu-toggle="mobile-menu" aria-label="القائمة">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>

            <a href="{{ route('home') }}" class="text-2xl font-bold">Alo</a>

            <nav class="hidden flex-1 items-center gap-6 text-sm font-bold md:flex" aria-label="الرئيسية">
                <a href="{{ route('home') }}" class="hover:text-muted">الرئيسية</a>
                <a href="{{ route('shop') }}" class="hover:text-muted">المتجر</a>
                <a href="{{ route('pages.contact') }}" class="hover:text-muted">تواصل معنا</a>
            </nav>

            <div class="ms-auto flex items-center gap-2 md:ms-0">
                <form action="{{ route('shop') }}" method="GET" class="hidden lg:block" role="search">
                    <label for="header-search" class="sr-only">ابحث عن منتج</label>
                    <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="ابحث…" class="input w-48 py-2 text-sm">
                </form>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-2 text-sm font-bold hover:underline">لوحة التحكم</a>
                    @endif
                    <a href="{{ route('account') }}" class="p-1" aria-label="حسابي" title="حسابي">
                        <x-avatar :user="auth()->user()" size="h-9 w-9 text-base" />
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-2 text-sm font-bold hover:text-muted">دخول</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative p-2 hover:text-muted" aria-label="السلة ({{ $cartCount }})">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12l1 13H5L6 7zm3 0a3 3 0 016 0"/></svg>
                    @if ($cartCount > 0)
                        <span class="absolute top-0 left-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-ink px-1 text-[11px] font-bold text-white">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-sand bg-white px-6 py-4 md:hidden" aria-label="قائمة الجوال">
            <form action="{{ route('shop') }}" method="GET" class="mb-3" role="search">
                <label for="mobile-search" class="sr-only">ابحث عن منتج</label>
                <input id="mobile-search" type="search" name="q" placeholder="ابحث…" class="input text-sm">
            </form>
            <div class="flex flex-col text-sm font-bold">
                <a href="{{ route('home') }}" class="py-2">الرئيسية</a>
                <a href="{{ route('shop') }}" class="py-2">المتجر</a>
                <a href="{{ route('pages.contact') }}" class="py-2">تواصل معنا</a>
                @auth
                    <a href="{{ route('account') }}" class="py-2">حسابي</a>
                @endauth
            </div>
        </nav>
    </header>

    <main id="main" class="mx-auto w-full max-w-6xl flex-1 px-6 py-10">
        <x-flash />
        {{ $slot }}
    </main>

    <footer class="mt-auto border-t border-sand">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-8 text-sm text-muted">
            <nav class="flex flex-wrap gap-x-6 gap-y-2" aria-label="روابط الفوتر">
                <a href="{{ route('shop') }}" class="hover:text-ink">المتجر</a>
                <a href="{{ route('pages.about') }}" class="hover:text-ink">من نحن</a>
                <a href="{{ route('pages.policies') }}" class="hover:text-ink">السياسات</a>
                <a href="{{ route('pages.contact') }}" class="hover:text-ink">تواصل معنا</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="hover:text-ink">تسجيل الخروج</button></form>
                @endauth
            </nav>
            <div class="flex flex-wrap items-center gap-2">
                @foreach (\App\Enums\PaymentMethod::available() as $method)
                    <span class="rounded border border-sand px-2 py-1 text-xs">{{ $method->label() }}</span>
                @endforeach
            </div>
            <p dir="ltr">© {{ date('Y') }} Alo</p>
        </div>
    </footer>
</body>
</html>
