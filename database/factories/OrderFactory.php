<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'phone' => '05'.fake()->numerify('########'),
            'city' => 'الرياض',
            'district' => 'النرجس',
            'address' => 'شارع ١٢',
            'payment_method' => PaymentMethod::CashOnDelivery,
            'status' => OrderStatus::Pending,
            'subtotal' => 100,
            'shipping_fee' => 25,
            'total' => 125,
        ];
    }
}
