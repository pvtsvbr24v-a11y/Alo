{{-- عنوان قسم مثل Figma: Sansation 36 في الوسط وتحته خط أحمر ٩٠، ونص Lato 16. إذا العنوان فاضي يظهر الخط بس --}}
@php($light = $light ?? false)
<div class="mx-auto max-w-[832px] text-center">
    @if (filled($title ?? null))
        <h2 class="squiggle text-center text-[30px] leading-tight sm:text-[34px] {{ $light ? 'text-white' : 'text-ink' }}">{{ $title }}</h2>
    @else
        <div class="mx-auto h-0.5 w-[90px] bg-brand" aria-hidden="true"></div>
    @endif
    @if (filled($subtitle ?? null))
        <p class="mt-8 text-[15px] leading-[26px] {{ $light ? 'text-white' : 'text-body' }}">{{ $subtitle }}</p>
    @endif
</div>
