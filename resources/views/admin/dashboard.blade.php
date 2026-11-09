<x-layouts.admin>
    <div class="grid gap-4 sm:grid-cols-3">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="card hover:ring-brand/40">
            <p class="text-sm text-muted">طلبات بانتظار التأكيد</p>
            <p class="mt-1 text-3xl font-extrabold text-brand">{{ $pendingOrdersCount }}</p>
        </a>
        <div class="card">
            <p class="text-sm text-muted">مبيعات اليوم</p>
            <x-price :amount="$todaySales" class="mt-1 block text-3xl font-extrabold" />
        </div>
        <div class="card">
            <p class="text-sm text-muted">مبيعات هذا الشهر</p>
            <x-price :amount="$monthSales" class="mt-1 block text-3xl font-extrabold" />
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="card lg:col-span-2" aria-labelledby="latest-title">
            <div class="mb-3 flex items-center justify-between">
                <h2 id="latest-title" class="text-lg font-bold">آخر الطلبات</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-bold text-brand">الكل</a>
            </div>
            @include('admin.orders.table', ['orders' => $latestOrders])
        </section>

        <section class="card h-fit" aria-labelledby="stock-title">
            <h2 id="stock-title" class="mb-3 text-lg font-bold">مخزون قليل</h2>
            @forelse ($lowStockProducts as $product)
                <a href="{{ route('admin.products.edit', $product) }}" class="flex justify-between border-b border-ink/5 py-2 text-sm last:border-0 hover:text-brand">
                    <span>{{ $product->name }}</span>
                    <span class="font-bold {{ $product->stock === 0 ? 'text-rose-600' : 'text-amber-700' }}">{{ $product->stock }}</span>
                </a>
            @empty
                <p class="text-sm text-muted">كل المنتجات كمياتها كافية.</p>
            @endforelse
        </section>
    </div>
</x-layouts.admin>
