<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

/**
 * @return array<string, string>
 */
function checkoutData(): array
{
    return [
        'customer_name' => 'أمل',
        'city' => 'الرياض',
        'district' => 'الملقا',
        'address' => 'شارع الأمير ١٢',
        'payment_method' => 'cod',
    ];
}

it('asks guests to log in before checkout', function () {
    $this->get(route('checkout.show'))->assertRedirect(route('login'));
});

it('places an order, reduces stock and clears the cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 60, 'stock' => 5]);

    $this->actingAs($user)->post(route('cart.store', $product), ['quantity' => 2]);
    $response = $this->post(route('checkout.store'), checkoutData());

    $order = Order::query()->sole();
    $response->assertRedirect(route('orders.show', $order));

    expect($order->number)->toBe('ALO-'.(1000 + $order->id))
        ->and($order->status)->toBe(OrderStatus::Pending)
        ->and((float) $order->subtotal)->toBe(120.0)
        ->and((float) $order->shipping_fee)->toBe(25.0)
        ->and((float) $order->total)->toBe(145.0)
        ->and($order->phone)->toBe($user->phone)
        ->and($order->items)->toHaveCount(1)
        ->and($product->fresh()->stock)->toBe(3)
        ->and(session('cart', []))->toBe([]);
});

it('stops the order when stock ran out meanwhile', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 2]);

    $this->actingAs($user)->post(route('cart.store', $product), ['quantity' => 2]);
    $product->update(['stock' => 1]);

    $this->post(route('checkout.store'), checkoutData())->assertRedirect(route('cart.index'))->assertSessionHas('error');

    expect(Order::query()->count())->toBe(0);
});

it('validates the delivery details', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post(route('cart.store', $product));

    $this->post(route('checkout.store'), ['city' => 'مدينة غير موجودة'])
        ->assertSessionHasErrors(['customer_name', 'city', 'address', 'payment_method']);
});

it('only lets customers see their own orders', function () {
    $order = Order::factory()->create();

    $this->actingAs($order->user)->get(route('orders.show', $order))->assertOk()->assertSee($order->number);
    $this->actingAs(User::factory()->create())->get(route('orders.show', $order))->assertNotFound();
});
