@props(['amount'])
@php($value = (float) $amount)
<span {{ $attributes }}>{{ number_format($value, fmod($value, 1) == 0 ? 0 : 2) }} {{ config('store.currency') }}</span>
