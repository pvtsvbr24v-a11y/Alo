@props(['product'])
<article class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-ink/5 transition hover:shadow-md">
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden">
        <x-product-image :product="$product" class="h-full w-full transition duration-300 group-hover:scale-105" />
        @if ($product->isOnSale())
            <span class="absolute top-3 right-3 rounded-full bg-brand px-2.5 py-1 text-xs font-bold text-white">خصم {{ $product->discountPercent() }}٪</span>
        @endif
        @unless ($product->isInStock())
            <span class="absolute inset-x-0 bottom-0 bg-ink/70 py-1.5 text-center text-xs font-bold text-white">نفدت الكمية</span>
        @endunless
    </a>
    <div class="flex flex-1 flex-col gap-2 p-4">
        <a href="{{ route('products.show', $product) }}" class="line-clamp-2 font-bold text-ink hover:text-brand">{{ $product->name }}</a>
        <div class="flex items-baseline gap-2">
            <x-price :amount="$product->price" class="font-bold text-brand" />
            @if ($product->isOnSale())
                <x-price :amount="$product->compare_at_price" class="text-sm text-muted line-through" />
            @endif
        </div>
        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-auto pt-1">
            @csrf
            <button type="submit" class="btn-outline w-full" @disabled(! $product->isInStock())>
                {{ $product->isInStock() ? 'أضف للسلة' : 'غير متوفر' }}
            </button>
        </form>
    </div>
</article>
