<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = OrderStatus::tryFrom($request->string('status')->toString());

        return view('admin.orders.index', [
            'orders' => Order::query()
                ->when($status, fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'statuses' => OrderStatus::cases(),
            'activeStatus' => $status,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('items', 'user'), 'statuses' => OrderStatus::cases()]);
    }

    /**
     * إلغاء الطلب يرجع الكميات للمخزون.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $newStatus = OrderStatus::from($request->validate(['status' => ['required', Rule::enum(OrderStatus::class)]])['status']);

        DB::transaction(function () use ($order, $newStatus): void {
            $wasCancelled = $order->status === OrderStatus::Cancelled;
            $isCancelled = $newStatus === OrderStatus::Cancelled;

            if ($wasCancelled !== $isCancelled) {
                foreach ($order->items()->with('product')->get() as $item) {
                    $item->product?->{$isCancelled ? 'increment' : 'decrement'}('stock', $item->quantity);
                }
            }

            $order->update(['status' => $newStatus]);
        });

        return back()->with('status', 'تم تحديث حالة الطلب');
    }
}
