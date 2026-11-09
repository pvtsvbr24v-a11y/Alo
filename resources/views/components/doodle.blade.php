@props(['name', 'text' => 'made with love · alo ·'])
{{-- ستيكرات زينة مرسومة بالكود (حادة على كل الشاشات). كلها زينة فقط فمخفية عن قارئ الشاشة --}}
@switch($name)
    @case('heart-bubble')
        <svg {{ $attributes->merge(['viewBox' => '0 0 48 44', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg">
            <path d="M6 2h36a4 4 0 0 1 4 4v22a4 4 0 0 1-4 4H22l-9 9v-9H6a4 4 0 0 1-4-4V6a4 4 0 0 1 4-4z" fill="#f2c4d3"/>
            <path d="M24 25s-8-4.6-8-9.6A4.4 4.4 0 0 1 24 13a4.4 4.4 0 0 1 8 2.4c0 5-8 9.6-8 9.6z" fill="#fbf7ee"/>
        </svg>
        @break
    @case('pixel-heart')
        <svg {{ $attributes->merge(['viewBox' => '0 0 9 8', 'aria-hidden' => 'true', 'shape-rendering' => 'crispEdges']) }} xmlns="http://www.w3.org/2000/svg" fill="#8e1b2c">
            <path d="M1 0h2v1H1zM6 0h2v1H6zM0 1h4v1H0zM5 1h4v1H5zM0 2h9v2H0zM1 4h7v1H1zM2 5h5v1H2zM3 6h3v1H3zM4 7h1v1H4z"/>
            <path d="M1 1h1v1H1z" fill="#f2c4d3"/>
        </svg>
        @break
    @case('cursor')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 28', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg">
            <path d="M3 2l17 15.5-7.6.6 4.4 8.2-3.4 1.7-4.3-8.3L3 25z" fill="#fbf7ee" stroke="#2b1714" stroke-width="1.8" stroke-linejoin="round"/>
        </svg>
        @break
    @case('sparkles')
        <svg {{ $attributes->merge(['viewBox' => '0 0 40 36', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg" fill="#f6ecc6">
            <path d="M14 4c1.2 7.6 3.4 9.8 11 11-7.6 1.2-9.8 3.4-11 11-1.2-7.6-3.4-9.8-11-11 7.6-1.2 9.8-3.4 11-11z"/>
            <path d="M31 2c.6 3.8 1.7 4.9 5.5 5.5-3.8.6-4.9 1.7-5.5 5.5-.6-3.8-1.7-4.9-5.5-5.5 3.8-.6 4.9-1.7 5.5-5.5z"/>
        </svg>
        @break
    @case('star')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg">
            <path d="M12 1.5l3 7.1 7.5.6-5.7 5 1.8 7.4L12 17.6l-6.6 4 1.8-7.4-5.7-5 7.5-.6z" fill="#e3b448" stroke="#b98a22" stroke-width=".8" stroke-linejoin="round"/>
        </svg>
        @break
    @case('cherries')
        <svg {{ $attributes->merge(['viewBox' => '0 0 48 48', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg">
            <path d="M26 6c-3 9-9 16-13 24M26 6c2 9 6 16 12 22" fill="none" stroke="#5a2a1e" stroke-width="2" stroke-linecap="round"/>
            <path d="M26 6c6-3 12-2 15 2-6 2-11 1-15-2z" fill="#6b7d3a"/>
            <circle cx="13" cy="34" r="9" fill="#a3182c"/>
            <circle cx="37" cy="33" r="9" fill="#a3182c"/>
            <circle cx="10" cy="31" r="2.2" fill="#f2c4d3" opacity=".8"/>
            <circle cx="34" cy="30" r="2.2" fill="#f2c4d3" opacity=".8"/>
        </svg>
        @break
    @case('badge')
        {{-- ختم دائري بنص يدور حوله (مثل "always find the good") --}}
        <svg {{ $attributes->merge(['viewBox' => '0 0 100 100', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="48" fill="#f6ecc6" stroke="#b98a22" stroke-width="1.5"/>
            <defs><path id="badge-ring-{{ $attributes->get('id', 'a') }}" d="M50 50m-34 0a34 34 0 1 1 68 0a34 34 0 1 1-68 0"/></defs>
            <g class="spin-slow" style="transform-origin:50px 50px">
                <text font-family="'Space Mono', monospace" font-size="10.5" font-weight="700" letter-spacing="2.2" fill="#66101d"><textPath href="#badge-ring-{{ $attributes->get('id', 'a') }}">{{ $text }}</textPath></text>
            </g>
            <path d="M50 60s-10-5.8-10-12a5.5 5.5 0 0 1 10-3 5.5 5.5 0 0 1 10 3c0 6.2-10 12-10 12z" fill="none" stroke="#8e1b2c" stroke-width="2.4"/>
        </svg>
        @break
    @case('flower')
        <svg {{ $attributes->merge(['viewBox' => '0 0 20 20', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.4">
            <circle cx="10" cy="10" r="2.2"/>
            <path d="M10 7.8c-1.8-3-1.8-5.6 0-5.6s1.8 2.6 0 5.6zm0 4.4c-1.8 3-1.8 5.6 0 5.6s1.8-2.6 0-5.6zM7.8 10c-3-1.8-5.6-1.8-5.6 0s2.6 1.8 5.6 0zm4.4 0c3-1.8 5.6-1.8 5.6 0s-2.6 1.8-5.6 0z"/>
        </svg>
        @break
    @case('cloud')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 16', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
            <path d="M6 14.5h12a4 4 0 0 0 .6-8 6 6 0 0 0-11.5-1.4A4.6 4.6 0 0 0 6 14.5z"/>
        </svg>
        @break
    @case('outline-star')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
            <path d="M12 2.5l2.8 6.4 7 .7-5.3 4.7 1.6 6.9L12 17.6l-6.1 3.6 1.6-6.9-5.3-4.7 7-.7z"/>
        </svg>
        @break
    @case('chat')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round">
            <path d="M12 3.5c5 0 9 3.3 9 7.5s-4 7.5-9 7.5c-1 0-2-.1-2.9-.4L4.5 20l1.2-3.9A7.1 7.1 0 0 1 3 11c0-4.2 4-7.5 9-7.5z"/>
        </svg>
        @break
@endswitch
