<x-layouts.admin title="الطلبات">
    <h1 class="mb-4 text-2xl font-extrabold">الطلبات</h1>
    <nav class="mb-4 flex flex-wrap gap-2 text-sm font-bold" aria-label="فلترة حسب الحالة">
        <a href="{{ route('admin.orders.index') }}" class="rounded-full px-3 py-1.5 {{ $activeStatus ? 'bg-white ring-1 ring-ink/10' : 'bg-ink text-cream' }}">الكل</a>
        @foreach ($statuses as $status)
            <a href="{{ route('admin.orders.index', ['status' => $status->value]) }}" class="rounded-full px-3 py-1.5 {{ $activeStatus === $status ? 'bg-ink text-cream' : 'bg-white ring-1 ring-ink/10' }}">{{ $status->label() }}</a>
        @endforeach
    </nav>
    <div class="card">
        @include('admin.orders.table')
        <div class="mt-4">{{ $orders->links() }}</div>
    </div>
</x-layouts.admin>
