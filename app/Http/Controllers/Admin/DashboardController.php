<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'pendingOrdersCount' => Order::query()->where('status', OrderStatus::Pending)->count(),
            'todaySales' => (float) Order::query()->whereDate('created_at', today())->where('status', '!=', OrderStatus::Cancelled)->sum('total'),
            'monthSales' => (float) Order::query()->where('created_at', '>=', now()->startOfMonth())->where('status', '!=', OrderStatus::Cancelled)->sum('total'),
            'lowStockProducts' => Product::query()->where('stock', '<=', 3)->orderBy('stock')->take(10)->get(),
            'latestOrders' => Order::query()->latest()->take(8)->get(),
        ]);
    }
}
