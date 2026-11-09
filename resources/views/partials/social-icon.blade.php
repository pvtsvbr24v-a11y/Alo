@switch($network)
    @case('instagram')
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
        @break
    @case('tiktok')
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3v12a4 4 0 11-4-4M14 3c.5 2.5 2.5 4.5 5 5"/></svg>
        @break
    @case('x')
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M4 4l16 16M20 4L4 20"/></svg>
        @break
    @default
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linejoin="round" d="M12 3c3 0 5 2.2 5 5.2v2.3l2 1-2 1.3c-.6 2.4-2.6 4.2-5 4.2s-4.4-1.8-5-4.2L5 11.5l2-1V8.2C7 5.2 9 3 12 3z"/></svg>
@endswitch
