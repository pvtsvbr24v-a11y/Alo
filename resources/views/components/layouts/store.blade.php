@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' | ' : '' }}Alo</title>
    <meta name="description" content="{{ $description ?? 'متجر Alo: إكسسوارات بطابع كلاسيكي لعشاق القصص' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-cream font-sans text-ink antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:right-2 focus:rounded focus:bg-white focus:px-3 focus:py-2">تخطَّ للمحتوى</a>

    <div class="bg-ink py-2 text-center text-xs font-bold text-cream">
        شحن مجاني للطلبات فوق <x-price :amount="config('store.free_shipping_from')" />
    </div>

    <header class="sticky top-0 z-30 border-b border-ink/10 bg-cream/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center gap-4 px-4 py-3">
            <button type="button" class="rounded-lg p-2 hover:bg-sand md:hidden" data-menu-toggle="mobile-menu" aria-label="القائمة">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>

            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight">Alo<span class="text-brand">.</span></a>

            <nav class="hidden flex-1 items-center gap-6 text-sm font-bold md:flex" aria-label="الرئيسية">
                <a href="{{ route('shop') }}" class="hover:text-brand">المتجر</a>
                <a href="{{ route('pages.about') }}" class="hover:text-brand">قصتنا</a>
                <a href="{{ route('pages.contact') }}" class="hover:text-brand">تواصل معنا</a>
            </nav>

            <div class="ms-auto flex items-center gap-1">
                <form action="{{ route('shop') }}" method="GET" class="hidden lg:block" role="search">
                    <label for="header-search" class="sr-only">ابحث عن منتج</label>
                    <input id="header-search" type="search" name="q" value="{{ request('q') }}" placeholder="ابحث عن منتج…" class="input w-56 py-2 text-sm">
                </form>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-bold text-brand hover:bg-sand">لوحة التحكم</a>
                    @endif
                    <a href="{{ route('orders.index') }}" class="rounded-lg p-2 hover:bg-sand" aria-label="طلباتي" title="طلباتي">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-bold hover:bg-sand">دخول</a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative rounded-lg p-2 hover:bg-sand" aria-label="السلة ({{ $cartCount }})">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.3 2.3c-.6.6-.2 1.7.7 1.7H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    @if ($cartCount > 0)
                        <span class="absolute -top-0.5 -left-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-brand px-1 text-xs font-bold text-white">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-ink/10 px-4 py-3 md:hidden" aria-label="قائمة الجوال">
            <form action="{{ route('shop') }}" method="GET" class="mb-3" role="search">
                <label for="mobile-search" class="sr-only">ابحث عن منتج</label>
                <input id="mobile-search" type="search" name="q" placeholder="ابحث عن منتج…" class="input text-sm">
            </form>
            <div class="flex flex-col gap-1 font-bold">
                <a href="{{ route('shop') }}" class="rounded-lg px-3 py-2 hover:bg-sand">المتجر</a>
                <a href="{{ route('pages.about') }}" class="rounded-lg px-3 py-2 hover:bg-sand">قصتنا</a>
                <a href="{{ route('pages.contact') }}" class="rounded-lg px-3 py-2 hover:bg-sand">تواصل معنا</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full rounded-lg px-3 py-2 text-start hover:bg-sand">تسجيل الخروج</button></form>
                @endauth
            </div>
        </nav>
    </header>

    <main id="main" class="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
        <x-flash />
        {{ $slot }}
    </main>

    <footer class="mt-12 bg-ink text-cream">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 py-10 sm:grid-cols-3">
            <div>
                <p class="text-2xl font-extrabold">Alo<span class="text-brand">.</span></p>
                <p class="mt-2 text-sm text-cream/70">إكسسوارات بطابع كلاسيكي لعشاق القصص والكتب.</p>
            </div>
            <div>
                <p class="mb-3 font-bold">روابط</p>
                <ul class="space-y-2 text-sm text-cream/80">
                    <li><a href="{{ route('shop') }}" class="hover:text-white">كل المنتجات</a></li>
                    <li><a href="{{ route('pages.policies') }}" class="hover:text-white">الشحن والاستبدال والاسترجاع</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="hover:text-white">تواصل معنا</a></li>
                    @auth
                        <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="hover:text-white">تسجيل الخروج</button></form></li>
                    @endauth
                </ul>
            </div>
            <div>
                <p class="mb-3 font-bold">طرق الدفع</p>
                <div class="flex flex-wrap gap-2 text-xs font-bold">
                    @foreach (\App\Enums\PaymentMethod::cases() as $method)
                        <span class="rounded bg-cream/10 px-2.5 py-1.5">{{ $method->label() }}</span>
                    @endforeach
                </div>
                <p class="mt-4 text-xs text-cream/60">الأسعار شاملة ضريبة القيمة المضافة</p>
            </div>
        </div>
        <p class="border-t border-cream/10 py-4 text-center text-xs text-cream/60">© {{ date('Y') }} Alo. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>
