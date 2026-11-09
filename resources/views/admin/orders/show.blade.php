<x-layouts.admin :title="'طلب '.$order->number">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold">طلب <span dir="ltr">{{ $order->number }}</span></h1>
            <p class="text-sm text-muted">{{ $order->created_at->translatedFormat('j F Y، g:i a') }}</p>
        </div>
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <label for="status" class="text-sm font-bold">الحالة:</label>
            <select id="status" name="status" class="input w-auto py-2">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected($order->status === $status)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <button class="btn-primary">حفظ</button>
        </form>
    </div>

    @include('partials.order-details')

    <div class="mt-4 flex flex-wrap gap-3">
        <a href="https://wa.me/966{{ substr($order->phone, 1) }}?text={{ urlencode('أهلاً '.$order->customer_name.'، بخصوص طلبك '.$order->number.' من متجر Alo') }}" class="btn-outline">تواصل مع العميل على واتساب</a>
        <a href="{{ route('admin.orders.index') }}" class="btn-outline">رجوع للطلبات</a>
    </div>
</x-layouts.admin>
