<aside class="card h-fit space-y-3 lg:sticky lg:top-24" aria-label="ملخص الطلب">
    <h2 class="text-2xl">ملخص الطلب</h2>
    <div class="flex justify-between text-sm"><span class="text-muted">المجموع</span><x-price :amount="$subtotal" /></div>
    <div class="flex justify-between text-sm">
        <span class="text-muted">الشحن</span>
        @if ($shippingFee > 0)
            <x-price :amount="$shippingFee" />
        @else
            <span class="font-bold text-emerald-700">مجاني</span>
        @endif
    </div>
    @if ($shippingFee > 0)
        <p class="rounded-2xl bg-butter px-4 py-2.5 text-xs">🚚 
            أضف بـ <x-price :amount="config('store.free_shipping_from') - $subtotal" class="font-bold" /> وخذ الشحن مجاناً
        </p>
    @endif
    <div class="flex justify-between border-t-2 border-dashed border-sky/40 pt-3 text-lg font-bold"><span>الإجمالي</span><x-price :amount="$total" /></div>
    <p class="text-xs text-muted">شامل ضريبة القيمة المضافة</p>
    @if ($action ?? false)
        <a href="{{ route('checkout.show') }}" class="btn-primary w-full">إتمام الطلب</a>
        <a href="{{ route('shop') }}" class="block text-center text-sm font-bold text-muted hover:text-ink">كمّل التسوّق</a>
    @endif
</aside>
