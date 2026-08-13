<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::sum('total_price');
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::where('status', 'active')->count();

        $recentOrders = Order::with('customer')->latest()->take(10)->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $salesData->pluck('date');
        $chartValues = $salesData->pluck('total');

        return view('admin.analytics.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'recentOrders',
            'topProducts',
            'chartLabels',
            'chartValues'
        ));
    }
}
