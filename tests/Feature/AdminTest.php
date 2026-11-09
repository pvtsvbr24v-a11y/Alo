<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;

it('keeps customers out of the admin panel', function () {
    $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

it('treats phones in ADMIN_PHONES as admins', function () {
    config(['services.admin_phones' => ['0550000001']]);

    $this->actingAs(User::factory()->create(['phone' => '0550000001']))->get(route('admin.dashboard'))->assertOk();
});

it('lets an admin create a product with an uploaded image', function () {
    $category = Category::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'فاصل جديد',
            'price' => 45,
            'stock' => 7,
            'is_active' => '1',
            'is_featured' => '0',
            'image_file' => UploadedFile::fake()->image('bookmark.jpg', 200, 200),
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->sole();
    expect($product->name)->toBe('فاصل جديد')
        ->and($product->is_active)->toBeTrue()
        ->and($product->image)->toStartWith('data:image/');
});

it('lets an admin edit and delete a product', function () {
    $admin = User::factory()->admin()->create();
    $product = Product::factory()->create();

    $this->actingAs($admin)->put(route('admin.products.update', $product), [
        'category_id' => $product->category_id,
        'name' => 'اسم معدل',
        'price' => 99,
        'compare_at_price' => 120,
        'stock' => 3,
        'is_active' => '0',
    ])->assertRedirect();

    expect($product->fresh())->name->toBe('اسم معدل')->is_active->toBeFalse();

    $this->delete(route('admin.products.destroy', $product))->assertRedirect();
    expect(Product::query()->count())->toBe(0);
});

it('restores stock when an admin cancels an order', function () {
    $product = Product::factory()->create(['stock' => 3]);
    $order = Order::factory()->create();
    $order->items()->create(['product_id' => $product->id, 'product_name' => $product->name, 'unit_price' => 10, 'quantity' => 2, 'line_total' => 20]);

    $this->actingAs(User::factory()->admin()->create())
        ->patch(route('admin.orders.update', $order->refresh()), ['status' => 'cancelled'])
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(OrderStatus::Cancelled)
        ->and($product->fresh()->stock)->toBe(5);
});

it('does not delete a category that still has products', function () {
    $product = Product::factory()->create();

    $this->actingAs(User::factory()->admin()->create())
        ->delete(route('admin.categories.destroy', $product->category))
        ->assertSessionHas('error');

    expect(Category::query()->count())->toBe(1);
});
