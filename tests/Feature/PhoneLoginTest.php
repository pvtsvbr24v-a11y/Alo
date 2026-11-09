<?php

use App\Models\User;

it('logs in with a phone number and a code', function () {
    $this->post(route('login'), ['phone' => '+966 55 123 4567'])->assertRedirect(route('login.verify'));

    $this->post(route('login.verify'), ['code' => '1234'])->assertRedirect(route('home'));

    $this->assertAuthenticated();
    expect(User::query()->sole()->phone)->toBe('0551234567');
});

it('rejects an invalid phone number', function () {
    $this->post(route('login'), ['phone' => '12345'])->assertSessionHasErrors('phone');
});

it('rejects a wrong code', function () {
    $this->post(route('login'), ['phone' => '0551234567']);

    $this->post(route('login.verify'), ['code' => '9999'])->assertSessionHasErrors('code');

    $this->assertGuest();
});

it('locks the code after too many wrong attempts', function () {
    $this->post(route('login'), ['phone' => '0551234567']);

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login.verify'), ['code' => '0000']);
    }

    $this->post(route('login.verify'), ['code' => '1234'])->assertSessionHasErrors('code');
    $this->assertGuest();
});

it('logs in an existing customer without creating a duplicate', function () {
    User::factory()->create(['phone' => '0551234567']);

    $this->post(route('login'), ['phone' => '0551234567']);
    $this->post(route('login.verify'), ['code' => '1234']);

    expect(User::query()->count())->toBe(1);
});
