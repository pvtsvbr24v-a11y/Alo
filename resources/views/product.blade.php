<x-layouts.store :title="$product->name" :description="\Illuminate\Support\Str::limit((string) $product->description, 150)">
    <nav class="mb-6 text-sm text-muted" aria-label="مسار التصفح">
        <a href="{{ route('home') }}" class="hover:text-brand">الرئيسية</a> /
        <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-brand">{{ $product->category->name }}</a> /
        <span class="text-ink">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-8 md:grid-cols-2">
        <div class="aspect-square overflow-hidden rounded-3xl bg-white ring-1 ring-ink/5">
            <x-product-image :product="$product" class="h-full w-full" />
        </div>

        <div>
            <h1 class="text-3xl font-extrabold">{{ $product->name }}</h1>

            <div class="mt-4 flex items-baseline gap-3">
                <x-price :amount="$product->price" class="text-3xl font-bold text-brand" />
                @if ($product->isOnSale())
                    <x-price :amount="$product->compare_at_price" class="text-lg text-muted line-through" />
                    <span class="rounded-full bg-brand/10 px-2.5 py-1 text-xs font-bold text-brand">وفّر {{ $product->discountPercent() }}٪</span>
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
                        <button type="submit" class="btn-primary flex-1 sm:flex-none">أضف للسلة</button>
                        <button type="submit" name="buy_now" value="1" class="btn-outline flex-1 sm:flex-none">اشترِ الآن</button>
                    </div>
                </form>
            @else
                <p class="mt-6 rounded-lg bg-sand px-4 py-3 font-bold">نفدت الكمية، ترجع قريباً إن شاء الله</p>
            @endif

            @if ($product->description)
                <div class="mt-8 border-t border-ink/10 pt-6">
                    <h2 class="mb-2 font-bold">الوصف</h2>
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
            <h2 id="related-title" class="mb-5 text-2xl font-extrabold">منتجات مشابهة</h2>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach ($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.store>
