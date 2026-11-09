<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\Cart;
use App\Services\Payments\PaymentFailed;
use App\Services\Payments\PaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private Cart $cart, private PaymentGateway $payments) {}

    public function show(Request $request): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $this->cart->subtotal();
        $shippingFee = $this->cart->shippingFee($subtotal);
        $lastOrder = $request->user()->orders()->first();

        return view('checkout', [
            'lines' => $this->cart->lines(),
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $subtotal + $shippingFee,
            'paymentMethods' => PaymentMethod::available(),
            'cities' => config('store.cities'),
            'lastOrder' => $lastOrder,
        ]);
    }

    /**
     * ينشئ الطلب وينقص المخزون في عملية وحدة، عشان ما ينباع منتج خلص.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $lines = $this->cart->lines();

        if ($lines->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $paymentMethod = PaymentMethod::from($request->validated('payment_method'));

        $order = DB::transaction(function () use ($request, $lines, $paymentMethod): ?Order {
            $products = Product::query()->whereKey($lines->pluck('product.id'))->lockForUpdate()->get()->keyBy('id');

            foreach ($lines as $line) {
                if ($products[$line['product']->id]->stock < $line['quantity']) {
                    return null;
                }
            }

            $subtotal = round($lines->sum('line_total'), 2);
            $shippingFee = $this->cart->shippingFee($subtotal);

            $order = Order::query()->create([
                ...$request->validated(),
                'user_id' => $request->user()->id,
                'phone' => $request->user()->phone,
                'status' => $paymentMethod->isOnline() ? OrderStatus::AwaitingPayment : OrderStatus::Pending,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $subtotal + $shippingFee,
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'unit_price' => $line['product']->price,
                    'quantity' => $line['quantity'],
                    'line_total' => $line['line_total'],
                ]);

                $products[$line['product']->id]->decrement('stock', $line['quantity']);
            }

            return $order;
        });

        if ($order === null) {
            return redirect()->route('cart.index')->with('error', 'بعض المنتجات ما عاد فيها كمية كافية، راجع السلة');
        }

        $this->rememberDeliveryDetails($request, $order);
        $this->cart->clear();
        $order->refresh();

        if ($paymentMethod->isOnline()) {
            try {
                return redirect()->away($this->payments->start($order));
            } catch (PaymentFailed $exception) {
                PaymentController::releaseOrder($order, $this->cart);

                return redirect()->route('checkout.show')->with('error', $exception->getMessage());
            }
        }

        return redirect()->route('orders.show', $order)->with('status', 'تم استلام طلبك بنجاح');
    }

    /**
     * أول طلب يعبّي معلومات حساب العميل الفاضية، عشان المرة الجاية تكون جاهزة.
     */
    private function rememberDeliveryDetails(CheckoutRequest $request, Order $order): void
    {
        $user = $request->user();

        $user->fill(array_filter([
            'name' => blank($user->name) ? $order->customer_name : null,
            'city' => blank($user->city) ? $order->city : null,
            'district' => blank($user->district) ? $order->district : null,
            'address' => blank($user->address) ? $order->address : null,
        ]))->save();
    }
}
