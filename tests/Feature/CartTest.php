<?php

use App\Models\Product;

it('adds a product to the cart and shows the totals', function () {
    $product = Product::factory()->create(['price' => 50, 'stock' => 5]);

    $this->post(route('cart.store', $product), ['quantity' => 2])->assertRedirect();

    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee('100 ر.س')
        ->assertSee('125 ر.س');
});

it('gives free shipping above the threshold', function () {
    config(['store.free_shipping_from' => 200]);
    $product = Product::factory()->create(['price' => 120, 'stock' => 5]);

    $this->post(route('cart.store', $product), ['quantity' => 2]);

    $this->get(route('cart.index'))->assertSee('مجاني')->assertSee('240 ر.س');
});

it('never puts more than the available stock in the cart', function () {
    $product = Product::factory()->create(['stock' => 2]);

    $this->post(route('cart.store', $product), ['quantity' => 5]);

    expect(session('cart'))->toBe([$product->id => 2]);
});

it('refuses out of stock products', function () {
    $product = Product::factory()->outOfStock()->create();

    $this->post(route('cart.store', $product))->assertSessionHas('error');

    expect(session('cart', []))->toBe([]);
});

it('updates and removes cart items', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $this->post(route('cart.store', $product));

    $this->patch(route('cart.update', $product), ['quantity' => 3]);
    expect(session('cart'))->toBe([$product->id => 3]);

    $this->delete(route('cart.destroy', $product));
    expect(session('cart'))->toBe([]);
});
