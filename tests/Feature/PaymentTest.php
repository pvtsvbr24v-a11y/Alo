<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;

/**
 * @return array<string, string>
 */
function deliveryDetails(string $paymentMethod): array
{
    return [
        'customer_name' => 'أمل',
        'city' => 'الرياض',
        'address' => 'حي الملقا',
        'payment_method' => $paymentMethod,
    ];
}

it('only offers online methods once their keys are set', function () {
    config(['payments.moyasar.secret_key' => null, 'payments.tabby.secret_key' => null, 'payments.tamara.secret_key' => null, 'payments.cash_on_delivery' => false]);
    expect(PaymentMethod::available())->toBe([]);

    config(['payments.moyasar.secret_key' => 'sk_test', 'payments.tabby.secret_key' => 'tabby_test']);
    expect(PaymentMethod::available())->toContain(PaymentMethod::Mada, PaymentMethod::ApplePay, PaymentMethod::Tabby)
        ->not->toContain(PaymentMethod::Tamara);
});

it('rejects a payment method that is not enabled', function () {
    config(['payments.tamara.secret_key' => null]);
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('cart.store', Product::factory()->create()));

    $this->post(route('checkout.store'), deliveryDetails('tamara'))->assertSessionHasErrors('payment_method');
});

it('sends mada orders to the Moyasar invoice page and confirms on return', function () {
    config(['payments.moyasar.secret_key' => 'sk_test']);
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 100, 'stock' => 5]);

    Http::fake([
        'api.moyasar.com/v1/invoices' => Http::response(['id' => 'inv_1', 'url' => 'https://checkout.moyasar.test/inv_1']),
        'api.moyasar.com/v1/invoices/inv_1' => Http::response(['id' => 'inv_1', 'status' => 'paid', 'amount' => 12500]),
    ]);

    $this->actingAs($user)->post(route('cart.store', $product));
    $this->post(route('checkout.store'), deliveryDetails('mada'))->assertRedirect('https://checkout.moyasar.test/inv_1');

    $order = Order::query()->sole();
    expect($order->status)->toBe(OrderStatus::AwaitingPayment)
        ->and($order->payment_reference)->toBe('inv_1')
        ->and($product->fresh()->stock)->toBe(4);

    $this->get(route('payments.return', $order))->assertRedirect(route('orders.show', $order));

    expect($order->fresh())
        ->status->toBe(OrderStatus::Pending)
        ->paid_at->not->toBeNull();
});

it('cancels the order and refills the cart when payment does not go through', function () {
    config(['payments.moyasar.secret_key' => 'sk_test']);
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 100, 'stock' => 5]);

    Http::fake([
        'api.moyasar.com/v1/invoices' => Http::response(['id' => 'inv_2', 'url' => 'https://checkout.moyasar.test/inv_2']),
        'api.moyasar.com/v1/invoices/inv_2' => Http::response(['id' => 'inv_2', 'status' => 'initiated', 'amount' => 12500]),
    ]);

    $this->actingAs($user)->post(route('cart.store', $product), ['quantity' => 2]);
    $this->post(route('checkout.store'), deliveryDetails('apple_pay'));

    $order = Order::query()->sole();
    $this->get(route('payments.return', $order))->assertRedirect(route('checkout.show'));

    expect($order->fresh()->status)->toBe(OrderStatus::Cancelled)
        ->and($product->fresh()->stock)->toBe(5)
        ->and(session('cart'))->toBe([$product->id => 2]);
});

it('returns to checkout when the gateway refuses to open a payment page', function () {
    config(['payments.moyasar.secret_key' => 'sk_test']);
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 3]);

    Http::fake(['api.moyasar.com/*' => Http::response(['message' => 'Invalid key'], 401)]);

    $this->actingAs($user)->post(route('cart.store', $product));
    $this->post(route('checkout.store'), deliveryDetails('mada'))
        ->assertRedirect(route('checkout.show'))
        ->assertSessionHas('error');

    expect(Order::query()->sole()->status)->toBe(OrderStatus::Cancelled)
        ->and($product->fresh()->stock)->toBe(3);
});

it('does not let one customer confirm another customer\'s payment', function () {
    $order = Order::factory()->create(['status' => OrderStatus::AwaitingPayment, 'payment_method' => PaymentMethod::Mada]);

    $this->actingAs(User::factory()->create())->get(route('payments.return', $order))->assertNotFound();
});
