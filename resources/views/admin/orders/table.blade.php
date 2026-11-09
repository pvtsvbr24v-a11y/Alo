@if ($orders->isEmpty())
    <p class="py-6 text-center text-sm text-muted">ما فيه طلبات.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-right text-muted">
                <tr><th class="py-2 font-bold">الطلب</th><th class="py-2 font-bold">العميل</th><th class="py-2 font-bold">المدينة</th><th class="py-2 font-bold">الإجمالي</th><th class="py-2 font-bold">الحالة</th></tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @foreach ($orders as $order)
                    <tr>
                        <td class="py-2"><a href="{{ route('admin.orders.show', $order) }}" class="font-bold text-brand hover:underline" dir="ltr">{{ $order->number }}</a><p class="text-xs text-muted">{{ $order->created_at->diffForHumans() }}</p></td>
                        <td class="py-2">{{ $order->customer_name }}</td>
                        <td class="py-2">{{ $order->city }}</td>
                        <td class="py-2"><x-price :amount="$order->total" /></td>
                        <td class="py-2"><span class="rounded-full px-2.5 py-1 text-xs font-bold whitespace-nowrap {{ $order->status->badgeClasses() }}">{{ $order->status->label() }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
