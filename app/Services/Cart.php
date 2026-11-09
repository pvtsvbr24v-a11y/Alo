<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * سلة المشتريات محفوظة في الجلسة: [product_id => quantity].
 */
class Cart
{
    private const SESSION_KEY = 'cart';

    public const MAX_QUANTITY_PER_ITEM = 10;

    public function __construct(private Session $session) {}

    /**
     * @return array<int, int>
     */
    public function quantities(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $quantities = $this->quantities();
        $quantities[$product->id] = ($quantities[$product->id] ?? 0) + $quantity;

        $this->put($product, $quantities);
    }

    public function update(Product $product, int $quantity): void
    {
        $quantities = $this->quantities();

        if ($quantity <= 0) {
            unset($quantities[$product->id]);
            $this->session->put(self::SESSION_KEY, $quantities);

            return;
        }

        $quantities[$product->id] = $quantity;
        $this->put($product, $quantities);
    }

    public function remove(Product $product): void
    {
        $this->update($product, 0);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum($this->quantities());
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * منتجات السلة مع الكمية والمجموع، وتشيل المنتجات المحذوفة أو المخفية.
     *
     * @return Collection<int, array{product: Product, quantity: int, line_total: float}>
     */
    public function lines(): Collection
    {
        $quantities = $this->quantities();

        if ($quantities === []) {
            return collect();
        }

        $products = Product::query()->active()->whereKey(array_keys($quantities))->get()->keyBy('id');

        return collect($quantities)
            ->filter(fn (int $quantity, int $productId): bool => $products->has($productId))
            ->map(fn (int $quantity, int $productId): array => [
                'product' => $products[$productId],
                'quantity' => $quantity,
                'line_total' => round((float) $products[$productId]->price * $quantity, 2),
            ])
            ->values();
    }

    public function subtotal(): float
    {
        return round($this->lines()->sum('line_total'), 2);
    }

    public function shippingFee(float $subtotal): float
    {
        if ($subtotal <= 0 || $subtotal >= config('store.free_shipping_from')) {
            return 0.0;
        }

        return (float) config('store.shipping_fee');
    }

    /**
     * @param  array<int, int>  $quantities
     */
    private function put(Product $product, array $quantities): void
    {
        $quantities[$product->id] = min($quantities[$product->id], self::MAX_QUANTITY_PER_ITEM, max($product->stock, 0));

        if ($quantities[$product->id] <= 0) {
            unset($quantities[$product->id]);
        }

        $this->session->put(self::SESSION_KEY, $quantities);
    }
}
