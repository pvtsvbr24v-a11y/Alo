{{-- عنوان قسم في الوسط مع خط أحمر قصير، مثل تصميم فيقما. إذا العنوان فاضي يظهر الخط بس --}}
<div class="mb-10 text-center">
    @if (filled($title ?? null))
        <h2 class="squiggle text-center text-4xl">{{ $title }}</h2>
    @else
        <div class="mx-auto h-0.5 w-18 bg-brand-dark" aria-hidden="true"></div>
    @endif
    @if (filled($subtitle ?? null))
        <p class="mx-auto mt-5 max-w-2xl leading-8 text-muted">{{ $subtitle }}</p>
    @endif
</div>
