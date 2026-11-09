<x-layouts.store :title="$product->name" :description="\Illuminate\Support\Str::limit((string) $product->description, 150)">
    <nav class="mb-6 text-sm text-muted" aria-label="مسار التصفح">
        <a href="{{ route('home') }}" class="hover:text-brand">الرئيسية</a> /
        <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-brand">{{ $product->category->name }}</a> /
        <span class="text-ink">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-8 md:grid-cols-2">
        <div class="sticker relative" style="--tilt: -1.5deg">
            @if ($product->isOnSale())<span class="tape -top-2 right-6">وفّر {{ $product->discountPercent() }}٪</span>@endif
            <x-product-image :product="$product" class="aspect-square w-full rounded-[1.4rem]" />
        </div>

        <div>
            <p class="text-sm font-bold text-sky-dark">{{ $product->category->name }}</p>
            <h1 class="mt-1 text-4xl leading-snug">{{ $product->name }}</h1>

            <div class="mt-4 flex items-baseline gap-3">
                <x-price :amount="$product->price" class="text-3xl font-bold text-brand" />
                @if ($product->isOnSale())
                    <x-price :amount="$product->compare_at_price" class="text-lg text-muted line-through" />
                @endif
            </div>
            <p class="mt-1 text-xs text-muted">شامل ضريبة القيمة المضافة</p>

            @if ($product->isInStock())
                <p class="mt-4 text-sm font-bold {{ $product->stock <= 3 ? 'text-rose-600' : 'text-emerald-700' }}">
                    {{ $product->stock <= 3 ? 'باقي '.$product->stock.' فقط!' : 'متوفر' }}
                </p>

                <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-6 space-y-4">
                    @csrf
                    <div class="w-32">
                        <label for="quantity" class="label">الكمية</label>
                        <select id="quantity" name="quantity" class="input">
                            @foreach (range(1, min(\App\Services\Cart::MAX_QUANTITY_PER_ITEM, $product->stock)) as $quantity)
                                <option value="{{ $quantity }}">{{ $quantity }}</option>
                            @endforeach
                        </select>
                        @error('quantity')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="btn-primary flex-1 sm:flex-none">أضف للسلة 🤍</button>
                        <button type="submit" name="buy_now" value="1" class="btn-outline flex-1 sm:flex-none">اشترِ الآن</button>
                    </div>
                </form>
            @else
                <p class="mt-6 rounded-full bg-rose-soft px-5 py-3 font-bold text-brand-dark">نفدت الكمية، ترجع قريباً إن شاء الله</p>
            @endif

            @if ($product->description)
                <div class="mt-8 border-t-2 border-dashed border-sky/40 pt-6">
                    <h2 class="mb-2 text-xl">الحكاية</h2>
                    <p class="leading-8 whitespace-pre-line text-muted">{{ $product->description }}</p>
                </div>
            @endif

            <ul class="mt-6 space-y-1 text-sm text-muted">
                <li>• شحن لكل مدن المملكة، ومجاني فوق <x-price :amount="config('store.free_shipping_from')" /></li>
                <li>• استبدال خلال ٧ أيام من الاستلام</li>
            </ul>
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-16" aria-labelledby="related-title">
            <h2 id="related-title" class="squiggle mb-8 text-3xl">حكايات قريبة</h2>
            <div class="grid grid-cols-2 gap-x-4 gap-y-8 md:grid-cols-4 md:gap-x-6">
                @foreach ($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.store>
