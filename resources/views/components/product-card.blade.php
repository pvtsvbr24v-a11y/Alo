@props(['product'])
{{-- كرت المنتج: صورة بولارويد مع شريط لاصق، وتميل شوي يمين أو يسار --}}
@php($tilt = ['-rotate-1', 'rotate-1', 'rotate-0', '-rotate-[0.6deg]'][$product->id % 4])
<article class="sticker {{ $tilt }} group relative flex flex-col text-center">
    <span class="washi -top-3 left-1/2 -translate-x-1/2 {{ $product->id % 2 ? 'rotate-3' : '-rotate-2' }}" aria-hidden="true"></span>
    @if ($product->isOnSale())
        <span class="tape -top-2 -right-2">−{{ $product->discountPercent() }}٪</span>
    @endif
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden bg-cream">
        <x-product-image :product="$product" class="h-full w-full transition duration-500 group-hover:scale-105" />
        @unless ($product->isInStock())
            <span class="absolute inset-x-0 bottom-0 bg-ink/75 py-2 text-xs font-bold text-paper">نفدت الكمية</span>
        @endunless
    </a>
    <a href="{{ route('products.show', $product) }}" class="mt-4 line-clamp-2 px-1 text-[15px] leading-5 font-bold text-ink hover:text-brand">{{ $product->name }}</a>
    <div class="mt-1.5 flex items-baseline justify-center gap-2 font-mono text-xs font-bold">
        <x-price :amount="$product->price" class="{{ $product->isOnSale() ? 'text-brand' : 'text-body' }}" />
        @if ($product->isOnSale())
            <x-price :amount="$product->compare_at_price" class="text-muted line-through" />
        @endif
    </div>
    <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-3 px-1">
        @csrf
        <button type="submit" class="btn-soft h-9 min-h-9 w-full px-0 text-[13px]" @disabled(! $product->isInStock())>
            {{ $product->isInStock() ? 'أضف للسلة' : 'غير متوفر' }}
        </button>
    </form>
</article>
