@if (session('status'))
    <div class="mb-6 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800 ring-1 ring-emerald-200" role="status">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="mb-6 rounded-lg bg-rose-50 px-4 py-3 text-sm font-bold text-rose-800 ring-1 ring-rose-200" role="alert">{{ session('error') }}</div>
@endif
