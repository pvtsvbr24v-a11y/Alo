<?php

use App\Models\Category;
use App\Models\Product;

it('shows the interactive intro with products and a link to the store', function () {
    $product = Product::factory()->featured()->create(['name' => 'فاصل النجمة']);

    $this->get(route('intro'))
        ->assertOk()
        ->assertSee('فاصل النجمة')
        ->assertSee(route('home'));
});

it('shows the store home page with featured products', function () {
    $product = Product::factory()->featured()->create(['name' => 'كفر الخريطة']);

    $this->get(route('home'))->assertOk()->assertSee('كفر الخريطة');
});

it('filters the shop by category and hides inactive products', function () {
    $cases = Category::factory()->create(['slug' => 'cases']);
    Product::factory()->for($cases)->create(['name' => 'كفر ظاهر']);
    Product::factory()->for($cases)->inactive()->create(['name' => 'كفر مخفي']);
    Product::factory()->create(['name' => 'فاصل من قسم ثاني']);

    $this->get(route('shop', ['category' => 'cases']))
        ->assertOk()
        ->assertSee('كفر ظاهر')
        ->assertDontSee('كفر مخفي')
        ->assertDontSee('فاصل من قسم ثاني');
});

it('searches products by name', function () {
    Product::factory()->create(['name' => 'ستيكر القمر']);
    Product::factory()->create(['name' => 'فاصل الريشة']);

    $this->get(route('shop', ['q' => 'القمر']))->assertSee('ستيكر القمر')->assertDontSee('فاصل الريشة');
});

it('shows an active product page and 404s for an inactive one', function () {
    $active = Product::factory()->create();
    $inactive = Product::factory()->inactive()->create();

    $this->get(route('products.show', $active))->assertOk()->assertSee($active->name);
    $this->get(route('products.show', $inactive))->assertNotFound();
});

it('renders the static pages', function (string $routeName) {
    $this->get(route($routeName))->assertOk();
})->with(['pages.about', 'pages.policies', 'pages.contact']);
