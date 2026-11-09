@props(['title' => 'لوحة التحكم'])
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title }} | Alo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:ital,wght@0,600;1,500&family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream font-sans text-ink antialiased">
    <header class="bg-ink text-cream">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-6 gap-y-2 px-4 py-3">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-extrabold">Alo<span class="text-brand">.</span> <span class="text-sm font-normal text-cream/70">لوحة التحكم</span></a>
            <nav class="flex flex-wrap gap-1 text-sm font-bold" aria-label="لوحة التحكم">
                @foreach (['admin.dashboard' => 'الرئيسية', 'admin.orders.index' => 'الطلبات', 'admin.products.index' => 'المنتجات', 'admin.categories.index' => 'الأقسام'] as $route => $label)
                    <a href="{{ route($route) }}" class="rounded-lg px-3 py-1.5 {{ request()->routeIs($route === 'admin.dashboard' ? $route : \Illuminate\Support\Str::beforeLast($route, '.').'.*') ? 'bg-cream/15' : 'hover:bg-cream/10' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <a href="{{ route('home') }}" class="ms-auto text-sm text-cream/70 hover:text-cream">عرض المتجر ←</a>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-4 py-8">
        <x-flash />
        {{ $slot }}
    </main>
</body>
</html>
