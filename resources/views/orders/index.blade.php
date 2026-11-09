<x-layouts.store title="طلباتي">
    <h1 class="mb-6 text-2xl">طلباتي</h1>

    @if ($orders->isEmpty())
        <div class="card py-16 text-center">
            <p class="text-lg font-bold">ما عندك طلبات للحين</p>
            <a href="{{ route('shop') }}" class="btn-primary mt-5">ابدأ التسوّق</a>
        </div>
    @else
        <ul class="space-y-3">
            @foreach ($orders as $order)
                <li>
                    <a href="{{ route('orders.show', $order) }}" class="card flex flex-wrap items-center justify-between gap-3 transition hover:ring-brand/40">
                        <div>
                            <p class="font-bold" dir="ltr">{{ $order->number }}</p>
                            <p class="text-sm text-muted">{{ $order->created_at->translatedFormat('j F Y') }} · {{ $order->items_count }} منتجات</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->status->badgeClasses() }}">{{ $order->status->label() }}</span>
                        <x-price :amount="$order->total" class="font-bold" />
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</x-layouts.store>
