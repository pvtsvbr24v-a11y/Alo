@props(['product'])
<article class="group relative flex flex-col">
    @if ($product->isOnSale())
        <span class="tape top-2 right-2">−{{ $product->discountPercent() }}٪</span>
    @endif
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden rounded-lg bg-cream">
        <x-product-image :product="$product" class="h-full w-full" />
        @unless ($product->isInStock())
            <span class="absolute inset-x-0 bottom-0 bg-black/70 py-1.5 text-center text-xs font-bold text-white">نفدت الكمية</span>
        @endunless
    </a>
    <a href="{{ route('products.show', $product) }}" class="mt-3 line-clamp-2 text-sm font-bold hover:underline">{{ $product->name }}</a>
    <div class="mt-1 flex items-baseline gap-2 text-sm">
        <x-price :amount="$product->price" class="font-bold" />
        @if ($product->isOnSale())
            <x-price :amount="$product->compare_at_price" class="text-xs text-muted line-through" />
        @endif
    </div>
    <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-3">
        @csrf
        <button type="submit" class="btn-outline w-full" @disabled(! $product->isInStock())>
            {{ $product->isInStock() ? 'أضف للسلة' : 'غير متوفر' }}
        </button>
    </form>
</article>
