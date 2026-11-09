<div class="grid gap-6 lg:grid-cols-3">
    <section class="card lg:col-span-2" aria-labelledby="order-items-title">
        <h2 id="order-items-title" class="mb-3 text-lg font-bold">المنتجات</h2>
        <ul class="divide-y divide-ink/10">
            @foreach ($order->items as $item)
                <li class="flex justify-between gap-3 py-3">
                    <div>
                        <p class="font-bold">{{ $item->product_name }}</p>
                        <p class="text-sm text-muted"><x-price :amount="$item->unit_price" /> × {{ $item->quantity }}</p>
                    </div>
                    <x-price :amount="$item->line_total" class="font-bold" />
                </li>
            @endforeach
        </ul>
        <div class="mt-3 space-y-2 border-t border-ink/10 pt-3 text-sm">
            <div class="flex justify-between"><span class="text-muted">المجموع</span><x-price :amount="$order->subtotal" /></div>
            <div class="flex justify-between"><span class="text-muted">الشحن</span>@if ((float) $order->shipping_fee > 0)<x-price :amount="$order->shipping_fee" />@else<span>مجاني</span>@endif</div>
            <div class="flex justify-between text-base font-bold"><span>الإجمالي</span><x-price :amount="$order->total" /></div>
        </div>
    </section>

    <section class="card h-fit space-y-2 text-sm" aria-labelledby="delivery-title">
        <h2 id="delivery-title" class="mb-1 text-lg font-bold">التوصيل</h2>
        <p class="font-bold">{{ $order->customer_name }}</p>
        <p dir="ltr" class="text-right">{{ $order->phone }}</p>
        <p>{{ $order->city }}{{ $order->district ? '، '.$order->district : '' }}</p>
        <p>{{ $order->address }}</p>
        @if ($order->notes)<p class="text-muted">ملاحظات: {{ $order->notes }}</p>@endif
        <p class="border-t border-ink/10 pt-2"><span class="text-muted">الدفع:</span> {{ $order->payment_method->label() }}</p>
    </section>
</div>
