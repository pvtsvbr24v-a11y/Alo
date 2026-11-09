@props(['product'])
@if ($product->image)
    <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy" {{ $attributes->merge(['class' => 'object-cover']) }}>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center bg-cream font-display text-5xl text-sand']) }} role="img" aria-label="{{ $product->name }}">
        {{ mb_substr($product->name, 0, 1) }}
    </div>
@endif
