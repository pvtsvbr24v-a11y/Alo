@if (session('status'))
    <div class="mb-6 rounded-md border border-sand bg-cream px-4 py-3 text-sm font-bold" role="status">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700" role="alert">{{ session('error') }}</div>
@endif
