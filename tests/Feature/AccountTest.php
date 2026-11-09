<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;

it('asks guests to log in to see the account page', function () {
    $this->get(route('account'))->assertRedirect(route('login'));
});

it('shows the customer info and latest orders', function () {
    $user = User::factory()->create(['name' => 'أمل', 'phone' => '0551234567']);
    $order = Order::factory()->for($user)->create();

    $this->actingAs($user)->get(route('account'))
        ->assertOk()
        ->assertSee('أمل')
        ->assertSee('0551234567')
        ->assertSee($order->refresh()->number);
});

it('updates the name and delivery address', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->patch(route('account.update'), [
        'name' => 'ريم',
        'city' => 'جدة',
        'district' => 'الروضة',
        'address' => 'شارع ٥',
    ])->assertRedirect()->assertSessionHas('status');

    expect($user->fresh())
        ->name->toBe('ريم')
        ->city->toBe('جدة')
        ->address->toBe('شارع ٥');
});

it('rejects a city outside the delivery list', function () {
    $this->actingAs(User::factory()->create())
        ->patch(route('account.update'), ['name' => 'ريم', 'city' => 'لندن'])
        ->assertSessionHasErrors('city');
});

it('sets a preset avatar, uploads a photo, and removes it', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('account.avatar'), ['preset' => '🐈‍⬛']);
    expect($user->fresh()->avatar)->toBe('🐈‍⬛');

    $this->post(route('account.avatar'), ['avatar_file' => UploadedFile::fake()->image('me.png', 100, 100)]);
    expect($user->fresh()->hasImageAvatar())->toBeTrue();

    $this->delete(route('account.avatar.destroy'));
    expect($user->fresh()->avatar)->toBeNull();
});

it('refuses avatars that are not in the preset list', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('account.avatar'), ['preset' => '<script>'])
        ->assertSessionHasErrors('preset');
});

it('saves the first order address to the account', function () {
    $user = User::factory()->create(['name' => null]);
    $product = Product::factory()->create();

    $this->actingAs($user)->post(route('cart.store', $product));
    $this->post(route('checkout.store'), [
        'customer_name' => 'سارة',
        'city' => 'الرياض',
        'address' => 'حي الملقا',
        'payment_method' => 'cod',
    ]);

    expect($user->fresh())->name->toBe('سارة')->city->toBe('الرياض')->address->toBe('حي الملقا');
});
