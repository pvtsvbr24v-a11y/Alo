@props(['method', 'size' => 'h-8'])
{{--
| شعار طريقة الدفع. إذا حطّيت الشعار الرسمي في public/images/payments/{value}.svg (أو .png)
| يظهر هو، وإلا يظهر شعار نصي بألوان الشركة.
--}}
@php
    $file = collect(['svg', 'png'])->map(fn ($ext) => "images/payments/{$method->value}.{$ext}")->first(fn ($path) => file_exists(public_path($path)));
    $styles = [
        'mada' => ['bg-white ring-1 ring-black/10', '<span style="color:#84b740">ma</span><span style="color:#259bd6">da</span>'],
        'apple_pay' => ['bg-black text-white', '<svg viewBox="0 0 17 20" class="me-1 h-3.5 w-3 fill-current" aria-hidden="true"><path d="M14.1 10.6c0-2.6 2.1-3.8 2.2-3.9-1.2-1.8-3.1-2-3.7-2-1.6-.2-3.1.9-3.9.9-.8 0-2-.9-3.4-.9-1.7 0-3.3 1-4.2 2.6-1.8 3.1-.5 7.7 1.3 10.2.9 1.2 1.9 2.6 3.2 2.6 1.3-.1 1.8-.8 3.3-.8 1.6 0 2 .8 3.4.8 1.4 0 2.3-1.3 3.1-2.5 1-1.4 1.4-2.8 1.4-2.9 0 0-2.7-1-2.7-4.1zM11.6 3c.7-.9 1.2-2 1-3.2-1 0-2.3.7-3 1.6-.7.8-1.2 2-1.1 3.1 1.2.1 2.3-.6 3.1-1.5z"/></svg>Pay'],
        'tabby' => ['bg-[#3bffc1] text-black', 'tabby'],
        'tamara' => ['bg-gradient-to-l from-[#f6c58a] via-[#f3a6b8] to-[#c8a2f0] text-black', 'tamara'],
    ];
    [$classes, $label] = $styles[$method->value] ?? ['bg-white ring-1 ring-black/10', e($method->label())];
@endphp
@if ($file)
    <img src="{{ asset($file) }}" alt="{{ $method->label() }}" {{ $attributes->merge(['class' => "$size w-auto"]) }}>
@else
    <span {{ $attributes->merge(['class' => "$size inline-flex min-w-14 items-center justify-center rounded-md px-2.5 text-[13px] font-bold tracking-tight $classes"]) }} role="img" aria-label="{{ $method->label() }}" dir="ltr">{!! $label !!}</span>
@endif
