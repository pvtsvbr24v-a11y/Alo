@if (session('status'))
    <div class="mb-6 border-s-4 border-brand bg-cream px-5 py-3 text-sm font-bold text-ink" role="status">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="mb-6 border-s-4 border-brand bg-rose-soft px-5 py-3 text-sm font-bold text-brand-dark" role="alert">{{ session('error') }}</div>
@endif
