<x-layouts.store :title="'طلب '.$order->number">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-4xl">طلب <span dir="ltr">{{ $order->number }}</span></h1>
            <p class="mt-1 text-sm text-muted">{{ $order->created_at->translatedFormat('j F Y، g:i a') }}</p>
        </div>
        <span class="rounded-full px-4 py-1.5 text-sm font-bold {{ $order->status->badgeClasses() }}">{{ $order->status->label() }}</span>
    </div>

    @if ($order->payment_method === \App\Enums\PaymentMethod::BankTransfer && $order->status === \App\Enums\OrderStatus::Pending)
        <div class="mb-6 rounded-lg bg-sky-50 px-4 py-3 text-sm text-sky-900 ring-1 ring-sky-200">
            حوّل مبلغ <x-price :amount="$order->total" class="font-bold" /> واكتب رقم الطلب في ملاحظة التحويل، وبعدها أرسل صورة الإيصال على
            <a href="https://wa.me/{{ config('store.whatsapp') }}?text={{ urlencode('إيصال تحويل للطلب '.$order->number) }}" class="font-bold underline">واتساب</a>.
        </div>
    @endif

    @include('partials.order-details')

    <a href="{{ route('account') }}" class="btn-outline mt-6">← حسابي</a>
</x-layouts.store>
