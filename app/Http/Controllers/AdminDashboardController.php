<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        $totalRevenue = Order::sum('total_price');
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::where('status', 'active')->count();

        $recentOrders = Order::with('customer')->latest()->take(10)->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'admin',
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'recentOrders',
            'topProducts'
        ));
    }
}
