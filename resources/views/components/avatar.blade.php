@props(['user', 'size' => 'h-10 w-10 text-lg'])
@if ($user->hasImageAvatar())
    <img src="{{ $user->avatar }}" alt="{{ $user->name ?? 'أفتار' }}" {{ $attributes->merge(['class' => "$size shrink-0 rounded-full object-cover ring-2 ring-white"]) }}>
@else
    <span {{ $attributes->merge(['class' => "$size glass flex shrink-0 items-center justify-center rounded-full font-display text-ink"]) }} role="img" aria-label="{{ $user->name ?? 'أفتار' }}">{{ $user->avatar ?: $user->initial() }}</span>
@endif
