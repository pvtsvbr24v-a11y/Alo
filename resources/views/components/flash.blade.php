@if (session('status'))
    <div class="mb-6 flex items-center gap-2 rounded-full bg-sky-soft px-5 py-3 text-sm font-bold text-ink ring-2 ring-sky/30" role="status"><span aria-hidden="true">🤍</span>{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="mb-6 flex items-center gap-2 rounded-full bg-rose-soft px-5 py-3 text-sm font-bold text-brand-dark ring-2 ring-brand/30" role="alert"><span aria-hidden="true">✿</span>{{ session('error') }}</div>
@endif
