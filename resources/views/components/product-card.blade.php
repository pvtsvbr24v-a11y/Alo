@props(['product'])
<article class="sticker group relative flex flex-col text-center">
    @if ($product->isOnSale())
        <span class="tape top-3 right-3">−{{ $product->discountPercent() }}٪</span>
    @endif
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden bg-cream">
        <x-product-image :product="$product" class="h-full w-full transition duration-500 group-hover:scale-105" />
        <span class="absolute inset-0 bg-[rgb(231_76_60/0.6)] opacity-0 transition duration-300 group-hover:opacity-100" aria-hidden="true"></span>
        @unless ($product->isInStock())
            <span class="absolute inset-x-0 bottom-0 bg-black/70 py-2 text-xs font-bold text-white">نفدت الكمية</span>
        @endunless
    </a>
    <a href="{{ route('products.show', $product) }}" class="mt-5 line-clamp-2 text-sm leading-[17px] font-bold text-ink-soft uppercase hover:text-brand">{{ $product->name }}</a>
    <div class="mt-2 flex items-baseline justify-center gap-2 text-[11px] font-bold">
        <x-price :amount="$product->price" class="text-muted" />
        @if ($product->isOnSale())
            <x-price :amount="$product->compare_at_price" class="text-muted/60 line-through" />
        @endif
    </div>
    <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-4">
        @csrf
        <button type="submit" class="btn-soft h-[35px] min-h-[35px] w-full px-0" @disabled(! $product->isInStock())>
            {{ $product->isInStock() ? 'أضف للسلة' : 'غير متوفر' }}
        </button>
    </form>
</article>
