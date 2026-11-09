<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alo | حكايات صغيرة صنعناها لك</title>
    <meta name="description" content="Alo: حكايات صغيرة صنعتها أختان بأيديهما. كفرات وستيكرات وفواصل كتب بطابع كلاسيكي.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        :root { --blue: #8fb1de; --linen: #f4f1ea; --ink: #2b2118; --rose: #b8574a; --night: #12204a; --dawn: #f6dcc0; }
        html { scroll-behavior: smooth; }
        body { background: var(--linen); color: var(--ink); }
        .serif-ar { font-family: 'Amiri', serif; }
        .serif-en { font-family: 'Cormorant Garamond', serif; }
        .stripes { background: repeating-linear-gradient(90deg, var(--blue) 0 34px, var(--linen) 34px 72px); }

        .scene { position: relative; height: 260vh; }
        .stage { position: sticky; top: 0; height: 100vh; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .reveal { opacity: clamp(0, calc((var(--p, 0) - var(--at)) * 6), 1); transform: translateY(calc((1 - clamp(0, calc((var(--p, 0) - var(--at)) * 6), 1)) * 24px)); }

        .letter { display: inline-block; opacity: 0; transform: translateY(40px) rotate(-8deg); animation: drop .9s cubic-bezier(.2,.9,.3,1.3) forwards; }
        @keyframes drop { to { opacity: 1; transform: none; } }
        .fade-in { opacity: 0; animation: fade 1.2s ease forwards; }
        @keyframes fade { to { opacity: 1; } }
        .bob { animation: bob 2s ease-in-out infinite; }
        @keyframes bob { 50% { transform: translateY(8px); } }
        .peek { transform: translateX(-30%) rotate(8deg); animation: peek 1.4s 1.6s cubic-bezier(.2,.9,.3,1.2) both; }
        @keyframes peek { from { transform: translateX(-110%) rotate(20deg); } }

        .hand { transition: transform .1s linear; }
        .thread { stroke-dasharray: 420; stroke-dashoffset: calc(420 - var(--p, 0) * 1.6 * 420); }

        .sky { background: color-mix(in srgb, var(--dawn) calc(clamp(0, (var(--p, 0) - .2) * 1.6, 1) * 100%), var(--night)); }
        .star { opacity: calc(1 - var(--p, 0) * 2); }
        .moon { transform: translateY(calc(var(--p, 0) * 70vh)); }
        .sun { transform: translateY(calc((1 - var(--p, 0)) * 60vh)); }
        .sky-text { color: color-mix(in srgb, var(--ink) calc(clamp(0, (var(--p, 0) - .35) * 3, 1) * 100%), var(--linen)); }

        .story-card { --q: clamp(0, calc((var(--p, 0) - var(--i) * .07) * 2.4), 1);
            transform: translate(calc(var(--x0) * (1 - var(--q))), calc(var(--y0) * (1 - var(--q)))) rotate(calc(var(--r0) * (1 - var(--q)) + var(--r1)));
            opacity: calc(var(--q) * 1.5); }

        .progress { transform-origin: right; transform: scaleX(var(--page, 0)); }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .scene { height: auto; }
            .stage { position: relative; min-height: 100vh; height: auto; padding: 6rem 0; }
            .scene, .stage { --p: 1 !important; }
            .letter, .fade-in, .bob, .peek { animation: none; opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="antialiased">
    <div class="fixed inset-x-0 top-0 z-50 h-1 bg-transparent"><div class="progress h-full bg-[var(--rose)]"></div></div>

    <header class="fixed inset-x-0 top-0 z-40 flex items-center justify-between px-5 py-4">
        <a href="{{ route('intro') }}" class="serif-en text-3xl font-semibold">Alo</a>
        <a href="{{ route('home') }}" class="rounded-full bg-white/90 px-4 py-2 text-sm font-bold shadow-sm ring-1 ring-black/5 backdrop-blur hover:bg-white">تخطَّ إلى المتجر ←</a>
    </header>

    <main>
        {{-- ١. الافتتاحية --}}
        <section class="stripes relative flex min-h-screen items-center justify-center overflow-hidden px-5" aria-labelledby="intro-title">
            <div class="relative w-full max-w-xl rounded-[2rem] bg-[var(--linen)] px-8 py-14 text-center shadow-[0_30px_60px_-30px_rgb(18_32_74/.45)]">
                <h1 id="intro-title" class="serif-en text-8xl leading-none font-semibold sm:text-9xl" aria-label="Alo">
                    @foreach (['A', 'l', 'o'] as $letter)
                        <span class="letter" style="animation-delay: {{ 0.2 + $loop->index * 0.18 }}s" aria-hidden="true">{{ $letter }}</span>
                    @endforeach
                </h1>
                <div class="fade-in mx-auto mt-6 h-px w-24 bg-[var(--rose)]" style="animation-delay: .9s"></div>
                <p class="fade-in serif-ar mt-6 text-2xl" style="animation-delay: 1.1s">حكاياتٌ صغيرة، صنعتها أختان</p>
                <p class="fade-in serif-en mt-2 text-lg italic opacity-70" dir="ltr" style="animation-delay: 1.3s">Little stories, made by two sisters</p>
                <a href="#story" class="fade-in mt-10 inline-flex flex-col items-center gap-1 text-sm font-bold" style="animation-delay: 1.6s">
                    ابدأ الحكاية
                    <svg class="bob h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </a>

                {{-- قطة تطل من طرف البطاقة --}}
                <svg class="peek absolute -bottom-6 -left-6 h-28 w-28" viewBox="0 0 120 120" aria-hidden="true">
                    <path d="M18 52 L28 14 L50 38 Q60 34 70 38 L92 14 L102 52 Q110 90 60 100 Q10 90 18 52 Z" fill="#1f1d1d" stroke="#f4f1ea" stroke-width="5" stroke-linejoin="round"/>
                    <ellipse cx="44" cy="62" rx="8" ry="9" fill="#f2c94c"/><ellipse cx="76" cy="62" rx="8" ry="9" fill="#f2c94c"/>
                    <ellipse cx="45" cy="63" rx="2.5" ry="7" fill="#1f1d1d"/><ellipse cx="77" cy="63" rx="2.5" ry="7" fill="#1f1d1d"/>
                    <path d="M56 76 L64 76 L60 81 Z" fill="#f4f1ea"/>
                    <path d="M30 78 L12 74 M30 84 L12 86 M90 78 L108 74 M90 84 L108 86" stroke="#f4f1ea" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </section>

        {{-- ٢. يد أختي في يدي --}}
        <section id="story" class="scene" data-scene aria-label="حكايتنا">
            <div class="stage flex-col bg-[var(--linen)] px-6 text-center">
                <svg class="mb-10 h-40 w-full max-w-md" viewBox="0 0 400 160" aria-hidden="true">
                    <path class="thread" d="M60 80 C 140 10, 260 150, 340 80" fill="none" stroke="var(--rose)" stroke-width="3" stroke-linecap="round"/>
                    <g class="hand" style="transform: translateX(calc(var(--p, 0) * 110px))">
                        <circle cx="60" cy="80" r="26" fill="var(--blue)"/>
                        <circle cx="60" cy="80" r="26" fill="none" stroke="var(--ink)" stroke-width="2" stroke-dasharray="4 5"/>
                    </g>
                    <g class="hand" style="transform: translateX(calc(var(--p, 0) * -110px))">
                        <circle cx="340" cy="80" r="26" fill="#e9b7ad"/>
                        <circle cx="340" cy="80" r="26" fill="none" stroke="var(--ink)" stroke-width="2" stroke-dasharray="4 5"/>
                    </g>
                </svg>
                <p class="serif-en reveal text-4xl" style="--at: .05">Our History</p>
                <div class="reveal mx-auto my-5 h-px w-20 bg-[var(--rose)]" style="--at: .1"></div>
                <p class="serif-ar reveal max-w-2xl text-3xl leading-[1.9] sm:text-4xl" style="--at: .2">سنينَ ويدُ أختي في يدي…</p>
                <p class="serif-en reveal mt-4 max-w-xl text-xl italic opacity-75" dir="ltr" style="--at: .4">For years my sister's hand held mine…</p>
            </div>
        </section>

        {{-- ٣. نطفئ الحلم ليلاً ونوقده كل صباح --}}
        <section class="scene" data-scene aria-label="الحلم">
            <div class="stage sky flex-col px-6 text-center">
                @foreach ([[12, 18], [24, 30], [38, 12], [55, 22], [70, 10], [82, 28], [90, 16], [18, 44], [64, 40], [46, 34]] as [$left, $top])
                    <span class="star absolute h-1.5 w-1.5 rounded-full bg-white" style="left: {{ $left }}%; top: {{ $top }}%" aria-hidden="true"></span>
                @endforeach
                <div class="moon absolute top-[12%] left-[18%] h-20 w-20 rounded-full bg-[#f4f1ea] shadow-[inset_-14px_-6px_0_0_#d8d2c4]" aria-hidden="true"></div>
                <div class="sun absolute right-[16%] bottom-[18%] h-28 w-28 rounded-full bg-[#f2a65a]" aria-hidden="true"></div>

                <div class="sky-text relative">
                    <p class="serif-ar reveal text-3xl leading-[1.9] sm:text-5xl" style="--at: .05">نُطفئُ الحلمَ ليلاً…</p>
                    <p class="serif-ar reveal mt-4 text-3xl leading-[1.9] sm:text-5xl" style="--at: .5">ونوقدُه كلَّ صباح</p>
                    <p class="serif-en reveal mt-6 text-xl italic opacity-80" dir="ltr" style="--at: .6">we dimmed the dream at night and relit it every dawn</p>
                </div>
            </div>
        </section>

        {{-- ٤. صار حكايات صغيرة --}}
        <section class="scene" data-scene aria-label="منتجاتنا">
            <div class="stage stripes flex-col px-4">
                <div class="relative z-10 mb-8 rounded-2xl bg-[var(--linen)]/95 px-6 py-4 text-center shadow-sm">
                    <p class="serif-ar reveal text-2xl leading-[1.9] sm:text-4xl" style="--at: .02">واليومَ صار حكاياتٍ صغيرة، صنعناها لك</p>
                    <p class="serif-en reveal text-lg italic opacity-75" dir="ltr" style="--at: .08">Today it became little stories, made by our hands, for you</p>
                </div>
                <div class="grid w-full max-w-3xl grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-5">
                    @php($offsets = [[-60, -40, -18], [50, -55, 14], [-45, 50, 22], [65, 45, -12], [-70, 10, 10], [55, -10, -20]])
                    @foreach ($products as $product)
                        @php([$x, $y, $r] = $offsets[$loop->index % 6])
                        <a href="{{ route('products.show', $product) }}" class="story-card block overflow-hidden rounded-2xl bg-white p-2 shadow-lg ring-1 ring-black/5"
                           style="--i: {{ $loop->index }}; --x0: {{ $x }}vw; --y0: {{ $y }}vh; --r0: {{ $r }}deg; --r1: {{ $loop->even ? -2 : 2 }}deg">
                            <x-product-image :product="$product" class="aspect-square w-full rounded-xl" />
                            <p class="mt-2 truncate px-1 text-center text-sm font-bold" style="font-family: Tajawal, sans-serif">{{ $product->name }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ٥. ادخل المتجر --}}
        <section class="flex min-h-screen flex-col items-center justify-center bg-[var(--linen)] px-6 py-24 text-center" aria-labelledby="enter-title">
            <p class="serif-ar text-2xl opacity-70">لكل حكايةٍ بداية…</p>
            <h2 id="enter-title" class="serif-ar mt-2 text-4xl sm:text-5xl">وحكايتك تبدأ من هنا</h2>
            <a href="{{ route('home') }}" class="mt-10 inline-flex items-center gap-3 rounded-full bg-[var(--ink)] px-10 py-4 text-lg font-bold text-[var(--linen)] transition hover:scale-105" style="font-family: Tajawal, sans-serif">
                ادخل المتجر
                <span aria-hidden="true">←</span>
            </a>
            @if ($categories->isNotEmpty())
                <nav class="mt-8 flex flex-wrap justify-center gap-2" aria-label="الأقسام" style="font-family: Tajawal, sans-serif">
                    @foreach ($categories as $category)
                        <a href="{{ route('shop', ['category' => $category->slug]) }}" class="rounded-full px-4 py-2 text-sm font-bold ring-1 ring-[var(--ink)]/20 hover:bg-[var(--blue)]/30">{{ $category->name }}</a>
                    @endforeach
                </nav>
            @endif
            <p class="serif-en mt-16 text-lg italic opacity-60" dir="ltr">— with love, two sisters &amp; Alo</p>
        </section>
    </main>

    <script>
        (() => {
            const scenes = [...document.querySelectorAll('[data-scene]')];
            const root = document.documentElement;
            let ticking = false;

            const update = () => {
                const viewport = window.innerHeight;
                scenes.forEach((scene) => {
                    const rect = scene.getBoundingClientRect();
                    const distance = scene.offsetHeight - viewport;
                    const progress = distance > 0 ? Math.min(Math.max(-rect.top / distance, 0), 1) : 1;
                    scene.style.setProperty('--p', progress.toFixed(3));
                });
                const max = root.scrollHeight - viewport;
                root.style.setProperty('--page', max > 0 ? (window.scrollY / max).toFixed(3) : 0);
                ticking = false;
            };

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                scenes.forEach((scene) => scene.style.setProperty('--p', 1));
                return;
            }

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    ticking = true;
                    requestAnimationFrame(update);
                }
            }, { passive: true });
            window.addEventListener('resize', update);
            update();
        })();
    </script>
</body>
</html>
