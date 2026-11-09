<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * يربط المتجر ببوابات الدفع:
 * ميسّر (مدى و Apple Pay) عن طريق صفحة فاتورة، وتابي وتمارا عن طريق صفحة الدفع حقتهم.
 *
 * مهم: جرّب كل بوابة في وضع الاختبار (sandbox) قبل الإطلاق، لأن تفاصيل الـ API
 * ممكن تختلف حسب نوع حسابك عندهم.
 */
class PaymentGateway
{
    /**
     * يبدأ الدفع ويرجع رابط صفحة الدفع اللي ينتقل لها العميل.
     */
    public function start(Order $order): string
    {
        $order->loadMissing('items', 'user');

        return match ($order->payment_method->gateway()) {
            'moyasar' => $this->startMoyasar($order),
            'tabby' => $this->startTabby($order),
            'tamara' => $this->startTamara($order),
            default => throw new PaymentFailed('طريقة الدفع ما تحتاج بوابة'),
        };
    }

    /**
     * يتأكد من البوابة إن المبلغ اندفع فعلاً، وما يعتمد على كلام الرابط.
     */
    public function confirm(Order $order, Request $request): bool
    {
        return match ($order->payment_method->gateway()) {
            'moyasar' => $this->confirmMoyasar($order),
            'tabby' => $this->confirmTabby($order, $request),
            'tamara' => $this->confirmTamara($order, $request),
            default => false,
        };
    }

    private function startMoyasar(Order $order): string
    {
        $response = $this->moyasar()->asForm()->post('/invoices', [
            'amount' => $this->halalas((float) $order->total),
            'currency' => 'SAR',
            'description' => 'طلب '.$order->number.' من متجر Alo',
            'success_url' => route('payments.return', $order),
            'back_url' => route('payments.return', $order),
            'metadata[order]' => $order->number,
        ]);

        $this->ensure($response->successful() && filled($response->json('url')), 'moyasar', $response->body());
        $order->update(['payment_reference' => $response->json('id')]);

        return $response->json('url');
    }

    private function confirmMoyasar(Order $order): bool
    {
        if (blank($order->payment_reference)) {
            return false;
        }

        $invoice = $this->moyasar()->get('/invoices/'.$order->payment_reference);

        return $invoice->successful()
            && $invoice->json('status') === 'paid'
            && (int) $invoice->json('amount') === $this->halalas((float) $order->total);
    }

    private function startTabby(Order $order): string
    {
        $response = $this->tabby()->post('/checkout', [
            'payment' => [
                'amount' => $this->amount((float) $order->total),
                'currency' => 'SAR',
                'description' => 'طلب '.$order->number,
                'buyer' => [
                    'phone' => $order->phone,
                    'name' => $order->customer_name,
                    'email' => $order->phone.'@alo.invalid',
                ],
                'shipping_address' => [
                    'city' => $order->city,
                    'address' => trim($order->district.' '.$order->address),
                    'zip' => '00000',
                ],
                'order' => [
                    'reference_id' => $order->number,
                    'shipping_amount' => $this->amount((float) $order->shipping_fee),
                    'items' => $order->items->map(fn ($item): array => [
                        'title' => $item->product_name,
                        'quantity' => $item->quantity,
                        'unit_price' => $this->amount((float) $item->unit_price),
                        'category' => 'Accessories',
                    ])->all(),
                ],
                'buyer_history' => [
                    'registered_since' => $order->user->created_at->toIso8601String(),
                    'loyalty_level' => $order->user->orders()->whereNotNull('paid_at')->count(),
                ],
            ],
            'lang' => 'ar',
            'merchant_code' => config('payments.tabby.merchant_code'),
            'merchant_urls' => [
                'success' => route('payments.return', $order),
                'cancel' => route('payments.return', $order),
                'failure' => route('payments.return', $order),
            ],
        ]);

        $url = $response->json('configuration.available_products.installments.0.web_url');
        $this->ensure($response->successful() && filled($url), 'tabby', $response->body());
        $order->update(['payment_reference' => $response->json('payment.id')]);

        return $url;
    }

    private function confirmTabby(Order $order, Request $request): bool
    {
        $paymentId = $order->payment_reference ?: $request->string('payment_id')->toString();

        if (blank($paymentId)) {
            return false;
        }

        $payment = $this->tabby()->get('/payments/'.$paymentId);

        if (! $payment->successful() || $payment->json('order.reference_id') !== $order->number) {
            return false;
        }

        if ($payment->json('status') === 'AUTHORIZED') {
            $capture = $this->tabby()->post('/payments/'.$paymentId.'/captures', [
                'amount' => $this->amount((float) $order->total),
            ]);

            return $capture->successful();
        }

        return $payment->json('status') === 'CLOSED';
    }

    private function startTamara(Order $order): string
    {
        $money = fn (float $value): array => ['amount' => round($value, 2), 'currency' => 'SAR'];
        [$firstName, $lastName] = array_pad(explode(' ', trim($order->customer_name), 2), 2, '-');
        $international = '+966'.substr($order->phone, 1);

        $response = $this->tamara()->post('/checkout', [
            'order_reference_id' => $order->number,
            'order_number' => $order->number,
            'total_amount' => $money((float) $order->total),
            'shipping_amount' => $money((float) $order->shipping_fee),
            'tax_amount' => $money(0),
            'description' => 'طلب '.$order->number.' من متجر Alo',
            'country_code' => 'SA',
            'payment_type' => 'PAY_BY_INSTALMENTS',
            'locale' => 'ar_SA',
            'items' => $order->items->map(fn ($item): array => [
                'reference_id' => (string) $item->id,
                'type' => 'Physical',
                'name' => $item->product_name,
                'sku' => (string) ($item->product_id ?? $item->id),
                'quantity' => $item->quantity,
                'unit_price' => $money((float) $item->unit_price),
                'total_amount' => $money((float) $item->line_total),
            ])->all(),
            'consumer' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $international,
                'email' => $order->phone.'@alo.invalid',
            ],
            'shipping_address' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'line1' => trim($order->district.' '.$order->address),
                'city' => $order->city,
                'country_code' => 'SA',
                'phone_number' => $international,
            ],
            'merchant_url' => [
                'success' => route('payments.return', $order),
                'failure' => route('payments.return', $order),
                'cancel' => route('payments.return', $order),
                'notification' => route('payments.return', $order),
            ],
        ]);

        $this->ensure($response->successful() && filled($response->json('checkout_url')), 'tamara', $response->body());
        $order->update(['payment_reference' => $response->json('order_id')]);

        return $response->json('checkout_url');
    }

    private function confirmTamara(Order $order, Request $request): bool
    {
        if (blank($order->payment_reference) || $request->string('paymentStatus')->toString() !== 'approved') {
            return false;
        }

        $authorise = $this->tamara()->post('/orders/'.$order->payment_reference.'/authorise');

        return $authorise->successful() && in_array($authorise->json('status'), ['authorised', 'fully_captured'], true);
    }

    private function moyasar(): PendingRequest
    {
        return Http::baseUrl(config('payments.moyasar.base_url'))
            ->withBasicAuth((string) config('payments.moyasar.secret_key'), '')
            ->acceptJson()
            ->timeout(20);
    }

    private function tabby(): PendingRequest
    {
        return Http::baseUrl(config('payments.tabby.base_url'))
            ->withToken((string) config('payments.tabby.secret_key'))
            ->acceptJson()
            ->timeout(20);
    }

    private function tamara(): PendingRequest
    {
        return Http::baseUrl(config('payments.tamara.base_url'))
            ->withToken((string) config('payments.tamara.secret_key'))
            ->acceptJson()
            ->timeout(20);
    }

    private function halalas(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function amount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    private function ensure(bool $condition, string $gateway, string $body): void
    {
        if (! $condition) {
            report(new PaymentFailed("{$gateway}: ".Str::limit($body, 500)));

            throw new PaymentFailed('ما قدرنا نفتح صفحة الدفع، جرّب طريقة ثانية');
        }
    }
}
