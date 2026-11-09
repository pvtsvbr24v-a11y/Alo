@props(['product'])
@if ($product->image)
    <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy" {{ $attributes->merge(['class' => 'object-cover']) }}>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center bg-sand text-4xl font-bold text-muted/60']) }} role="img" aria-label="{{ $product->name }}">
        {{ mb_substr($product->name, 0, 1) }}
    </div>
@endif
