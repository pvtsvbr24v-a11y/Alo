<x-layouts.store>
    {{-- البنر الرئيسي --}}
    <section class="grid items-center gap-6 overflow-hidden rounded-3xl bg-sand p-6 sm:p-10 md:grid-cols-2">
        <div>
            <span class="rounded-full bg-brand/10 px-3 py-1 text-xs font-bold text-brand">مجموعة الخريف</span>
            <h1 class="mt-4 text-3xl leading-tight font-extrabold sm:text-5xl">تفاصيل صغيرة<br>تحكي قصتك</h1>
            <p class="mt-4 max-w-md text-muted">كفرات وستيكرات معدنية وفواصل كتب بطابع كلاسيكي، مصنوعة لعشاق القراءة والسفر.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('shop') }}" class="btn-primary">تسوّق الآن</a>
                <a href="{{ route('pages.about') }}" class="btn-outline">قصتنا</a>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            @foreach ($newProducts->take(4) as $product)
                <a href="{{ route('products.show', $product) }}" class="aspect-square overflow-hidden rounded-2xl {{ $loop->odd ? 'translate-y-4' : '' }}">
                    <x-product-image :product="$product" class="h-full w-full" />
                </a>
            @endforeach
        </div>
    </section>

    {{-- الأقسام --}}
    @if ($categories->isNotEmpty())
        <section class="mt-14" aria-labelledby="categories-title">
            <h2 id="categories-title" class="mb-5 text-2xl font-extrabold">تسوّق حسب القسم</h2>
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($categories as $category)
                    <a href="{{ route('shop', ['category' => $category->slug]) }}" class="card flex items-center justify-between transition hover:ring-brand/40">
                        <span class="text-lg font-bold">{{ $category->name }}</span>
                        <span class="text-sm text-muted">{{ $category->products_count }} منتج</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- المنتجات المميزة --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="mt-14" aria-labelledby="featured-title">
            <div class="mb-5 flex items-end justify-between">
                <h2 id="featured-title" class="text-2xl font-extrabold">الأكثر مبيعاً</h2>
                <a href="{{ route('shop') }}" class="text-sm font-bold text-brand hover:underline">عرض الكل</a>
            </div>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- بنر العرض مع عداد --}}
    @if ($onSaleProduct)
        <section class="mt-14 grid items-center gap-6 rounded-3xl bg-ink p-6 text-cream sm:p-10 md:grid-cols-2" aria-labelledby="offer-title">
            <div>
                <span class="rounded-full bg-brand px-3 py-1 text-xs font-bold text-white">خصم {{ $onSaleProduct->discountPercent() }}٪</span>
                <h2 id="offer-title" class="mt-4 text-3xl font-extrabold">{{ $onSaleProduct->name }}</h2>
                <div class="mt-3 flex items-baseline gap-3">
                    <x-price :amount="$onSaleProduct->price" class="text-2xl font-bold text-brand" />
                    <x-price :amount="$onSaleProduct->compare_at_price" class="text-cream/60 line-through" />
                </div>
                <div class="mt-5 flex gap-2" data-countdown="{{ now()->endOfWeek()->toIso8601String() }}" aria-label="ينتهي العرض بعد">
                    @foreach (['days' => 'يوم', 'hours' => 'ساعة', 'minutes' => 'دقيقة'] as $unit => $label)
                        <div class="min-w-16 rounded-lg bg-cream/10 px-3 py-2 text-center">
                            <p class="text-xl font-bold" data-{{ $unit }}>--</p>
                            <p class="text-xs text-cream/60">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('cart.store', $onSaleProduct) }}" class="mt-6">
                    @csrf
                    <button class="btn-primary">أضف للسلة</button>
                </form>
            </div>
            <a href="{{ route('products.show', $onSaleProduct) }}" class="block aspect-square overflow-hidden rounded-2xl md:aspect-[4/3]">
                <x-product-image :product="$onSaleProduct" class="h-full w-full" />
            </a>
        </section>
    @endif

    {{-- مميزاتنا --}}
    <section class="mt-14 grid gap-4 sm:grid-cols-3" aria-label="مميزات المتجر">
        @foreach ([['شحن سريع', 'لكل مدن المملكة خلال ٢-٥ أيام عمل'], ['دفع آمن', 'الدفع عند الاستلام أو تحويل بنكي'], ['استبدال سهل', 'خلال ٧ أيام من استلام الطلب']] as [$heading, $text])
            <div class="card text-center">
                <p class="font-bold">{{ $heading }}</p>
                <p class="mt-1 text-sm text-muted">{{ $text }}</p>
            </div>
        @endforeach
    </section>
</x-layouts.store>
