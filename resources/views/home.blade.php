<x-layouts.store>
    {{-- البنر الرئيسي --}}
    <section class="stripes relative overflow-hidden rounded-[2.5rem] p-3 sm:p-5">
        <div class="grid items-center gap-8 rounded-[2rem] bg-cream p-7 sm:p-12 md:grid-cols-2">
            <div>
                <span class="inline-block -rotate-2 rounded-full bg-rose-soft px-4 py-1.5 text-xs font-bold text-brand-dark">✿ مجموعة الخريف</span>
                <h1 class="mt-5 text-4xl leading-snug sm:text-6xl">تفاصيلُ صغيرة<br><span class="squiggle">تحكي قصتك</span></h1>
                <p class="mt-5 max-w-md leading-8 text-muted">ستيكرات وتشارمز صغيرة بطابع كلاسيكي، صنعتها أختان بأيديهما لكل من يحب الحكايات.</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('shop') }}" class="btn-primary">تسوّق الآن 🤍</a>
                    <a href="{{ route('intro') }}" class="btn-outline">حكايتنا</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 p-2">
                @foreach ($newProducts->take(4) as $product)
                    <a href="{{ route('products.show', $product) }}" class="sticker block {{ $loop->odd ? 'translate-y-5' : '' }}" style="--tilt: {{ $loop->odd ? '-3' : '3' }}deg">
                        <x-product-image :product="$product" class="aspect-square w-full rounded-[1.4rem]" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- الأقسام --}}
    @if ($categories->isNotEmpty())
        <section class="mt-16" aria-labelledby="categories-title">
            <h2 id="categories-title" class="squiggle mb-7 text-3xl">تسوّق حسب القسم</h2>
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($categories as $category)
                    @php($tones = ['bg-sky-soft', 'bg-rose-soft', 'bg-butter'])
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" class="group relative flex items-center justify-between overflow-hidden rounded-[1.75rem] {{ $tones[$loop->index % 3] }} px-6 py-7 transition hover:-translate-y-1">
                        <span>
                            <span class="block font-display text-2xl">{{ $category->name }}</span>
                            <span class="text-sm text-muted">{{ $category->products_count }} حكاية</span>
                        </span>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-lg transition group-hover:-translate-x-1" aria-hidden="true">←</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- الأكثر حباً --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="mt-16" aria-labelledby="featured-title">
            <div class="mb-8 flex items-end justify-between">
                <h2 id="featured-title" class="squiggle text-3xl">الأكثر حباً</h2>
                <a href="{{ route('shop') }}" class="rounded-full px-4 py-2 text-sm font-bold text-brand hover:bg-rose-soft">عرض الكل ←</a>
            </div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-4 md:gap-x-6">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- بنر العرض مع عداد --}}
    @if ($onSaleProduct)
        <section class="relative mt-16 grid items-center gap-8 overflow-hidden rounded-[2.5rem] bg-butter p-7 sm:p-12 md:grid-cols-2" aria-labelledby="offer-title">
            <div>
                <span class="tape relative! inline-block">خصم {{ $onSaleProduct->discountPercent() }}٪</span>
                <h2 id="offer-title" class="mt-5 text-4xl">{{ $onSaleProduct->name }}</h2>
                <div class="mt-3 flex items-baseline gap-3">
                    <x-price :amount="$onSaleProduct->price" class="text-3xl font-bold text-brand" />
                    <x-price :amount="$onSaleProduct->compare_at_price" class="text-muted line-through" />
                </div>
                <p class="mt-6 text-sm font-bold text-muted">ينتهي العرض بعد</p>
                <div class="mt-2 flex gap-3" data-countdown="{{ now()->endOfWeek()->toIso8601String() }}">
                    @foreach (['days' => 'يوم', 'hours' => 'ساعة', 'minutes' => 'دقيقة'] as $unit => $label)
                        <div class="min-w-18 rounded-2xl bg-white px-3 py-3 text-center" style="box-shadow: 0 4px 0 var(--color-sand)">
                            <p class="font-latin text-3xl leading-none font-semibold" data-{{ $unit }}>--</p>
                            <p class="mt-1 text-xs text-muted">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('cart.store', $onSaleProduct) }}" class="mt-8">
                    @csrf
                    <button class="btn-primary">أضف للسلة 🤍</button>
                </form>
            </div>
            <a href="{{ route('products.show', $onSaleProduct) }}" class="sticker mx-auto block w-full max-w-sm" style="--tilt: 3deg">
                <x-product-image :product="$onSaleProduct" class="aspect-square w-full rounded-[1.4rem]" />
            </a>
        </section>
    @endif

    {{-- وعودنا --}}
    <section class="mt-16 grid gap-4 sm:grid-cols-3" aria-label="مميزات المتجر">
        @foreach ([['📦', 'تغليف بحب', 'كل طلب يوصلك مغلّف بعناية مع بطاقة شكر'], ['🚚', 'شحن لكل المملكة', 'خلال ٢ إلى ٥ أيام عمل'], ['🔁', 'استبدال سهل', 'خلال ٧ أيام من استلام طلبك']] as [$icon, $heading, $text])
            <div class="card text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-sky-soft text-2xl" aria-hidden="true">{{ $icon }}</span>
                <p class="mt-3 font-display text-xl">{{ $heading }}</p>
                <p class="mt-1 text-sm text-muted">{{ $text }}</p>
            </div>
        @endforeach
    </section>
</x-layouts.store>
