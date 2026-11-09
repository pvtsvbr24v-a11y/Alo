<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\Cart;
use App\Services\Payments\PaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * العميل يرجع هنا من صفحة الدفع (ميسّر أو تابي أو تمارا).
 */
class PaymentController extends Controller
{
    public function return(Request $request, Order $order, PaymentGateway $payments, Cart $cart): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        if ($order->status !== OrderStatus::AwaitingPayment) {
            return redirect()->route('orders.show', $order);
        }

        if ($payments->confirm($order, $request)) {
            $order->update(['status' => OrderStatus::Pending, 'paid_at' => now()]);

            return redirect()->route('orders.show', $order)->with('status', 'تم الدفع واستلمنا طلبك 🤍');
        }

        self::releaseOrder($order, $cart);

        return redirect()->route('checkout.show')->with('error', 'ما اكتمل الدفع، جرّب مرة ثانية أو اختر طريقة ثانية');
    }

    /**
     * يلغي طلب ما اندفع: يرجع الكميات للمخزون والمنتجات للسلة.
     */
    public static function releaseOrder(Order $order, Cart $cart): void
    {
        DB::transaction(function () use ($order): void {
            foreach ($order->items()->with('product')->get() as $item) {
                $item->product?->increment('stock', $item->quantity);
            }

            $order->update(['status' => OrderStatus::Cancelled]);
        });

        foreach ($order->items()->with('product')->get() as $item) {
            if ($item->product) {
                $cart->add($item->product, $item->quantity);
            }
        }
    }
}
