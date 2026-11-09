@props(['product', 'tilt' => null])
<article class="sticker group relative flex flex-col" style="--tilt: {{ $tilt ?? (($product->id % 3) - 1) * 1.5 }}deg">
    @if ($product->isOnSale())
        <span class="tape -top-2 right-4">خصم {{ $product->discountPercent() }}٪</span>
    @elseif ($product->created_at?->gt(now()->subDays(14)))
        <span class="tape -top-2 right-4" style="background: repeating-linear-gradient(45deg, rgb(143 177 222 / .95) 0 8px, rgb(167 195 232 / .95) 8px 16px)">جديد</span>
    @endif
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden rounded-[1.4rem] bg-sky-soft">
        <x-product-image :product="$product" class="h-full w-full transition duration-500 group-hover:scale-105" />
        @unless ($product->isInStock())
            <span class="absolute inset-x-3 bottom-3 rounded-full bg-white/90 py-1.5 text-center text-xs font-bold text-muted">نفدت الكمية</span>
        @endunless
    </a>
    <div class="flex flex-1 flex-col gap-1.5 px-2 pt-3 pb-1">
        <a href="{{ route('products.show', $product) }}" class="line-clamp-2 text-center font-bold text-ink hover:text-brand">{{ $product->name }}</a>
        <div class="flex items-baseline justify-center gap-2">
            <x-price :amount="$product->price" class="font-bold text-brand" />
            @if ($product->isOnSale())
                <x-price :amount="$product->compare_at_price" class="text-xs text-muted line-through" />
            @endif
        </div>
        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-auto pt-2">
            @csrf
            <button type="submit" class="btn-soft w-full py-2.5" @disabled(! $product->isInStock())>
                @if ($product->isInStock())
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                    أضف للسلة
                @else
                    غير متوفر
                @endif
            </button>
        </form>
    </div>
</article>
