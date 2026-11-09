<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private Cart $cart) {}

    public function index(): View
    {
        $subtotal = $this->cart->subtotal();
        $shippingFee = $this->cart->shippingFee($subtotal);

        return view('cart', [
            'lines' => $this->cart->lines(),
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $subtotal + $shippingFee,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:'.Cart::MAX_QUANTITY_PER_ITEM]]);
        $quantity = max($request->integer('quantity', 1), 1);

        if (! $product->is_active || ! $product->isInStock()) {
            return back()->with('error', 'المنتج غير متوفر حالياً');
        }

        $this->cart->add($product, $quantity);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout.show');
        }

        return back()->with('status', 'تمت إضافة «'.$product->name.'» للسلة');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:'.Cart::MAX_QUANTITY_PER_ITEM]]);

        $this->cart->update($product, (int) $validated['quantity']);

        return redirect()->route('cart.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return redirect()->route('cart.index')->with('status', 'تم حذف المنتج من السلة');
    }
}
