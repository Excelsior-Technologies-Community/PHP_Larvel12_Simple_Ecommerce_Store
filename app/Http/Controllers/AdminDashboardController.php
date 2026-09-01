<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | BASIC DASHBOARD STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalRevenue = Order::whereNotIn('status', ['cancelled'])
            ->sum('total_price');

        $totalOrders = Order::count();

        $totalCustomers = Customer::count();

        $totalProducts = Product::where('status', 'active')->count();

        /*
        |--------------------------------------------------------------------------
        | RECENT ORDERS
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with('customer')
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTS
        |--------------------------------------------------------------------------
        */

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | LOW STOCK PRODUCTS
        |--------------------------------------------------------------------------
        */

        $lowStockProducts = Product::where('status', 'active')
            ->where('track_stock', true)
            ->where(function ($query) {
                $query->where(
                    'stock_quantity',
                    '<=',
                    DB::raw('low_stock_threshold')
                )
                ->orWhere(function ($q) {
                    $q->whereHas('activeVariants', function ($variantQuery) {
                        $variantQuery->whereColumn(
                            'product_variants.stock_quantity',
                            '<=',
                            'product_variants.low_stock_threshold'
                        );
                    });
                });
            })
            ->with('variants')
            ->orderBy('stock_quantity', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | OUT OF STOCK PRODUCTS
        |--------------------------------------------------------------------------
        */

        $outOfStockProducts = Product::where('status', 'active')
            ->where('track_stock', true)
            ->where(function ($query) {

                $query->where('stock_quantity', '<=', 0)

                    ->orWhere(function ($q) {

                        $q->whereHas('variants')
                            ->whereDoesntHave(
                                'activeVariants',
                                function ($variantQuery) {
                                    $variantQuery->where(
                                        'stock_quantity',
                                        '>',
                                        0
                                    );
                                }
                            );
                    });
            })
            ->with('variants')
            ->orderBy('stock_quantity', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SALES REPORT FILTER
        |--------------------------------------------------------------------------
        */

        $period = $request->get('period', '30');

        $startDate = null;

        $endDate = Carbon::today()->endOfDay();

        if ($period === 'today') {

            $startDate = Carbon::today()->startOfDay();

        } elseif ($period === '7') {

            $startDate = Carbon::today()
                ->subDays(6)
                ->startOfDay();

        } elseif ($period === '30') {

            $startDate = Carbon::today()
                ->subDays(29)
                ->startOfDay();

        } elseif ($period === '90') {

            $startDate = Carbon::today()
                ->subDays(89)
                ->startOfDay();

        } elseif ($period === 'custom') {

            if ($request->filled('start_date')) {

                $startDate = Carbon::parse(
                    $request->start_date
                )->startOfDay();

            } else {

                $startDate = Carbon::today()
                    ->subDays(29)
                    ->startOfDay();
            }

            if ($request->filled('end_date')) {

                $endDate = Carbon::parse(
                    $request->end_date
                )->endOfDay();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SALES ORDERS
        |--------------------------------------------------------------------------
        */

        $salesOrdersQuery = Order::whereBetween(
            'created_at',
            [$startDate, $endDate]
        )
        ->whereNotIn('status', ['cancelled']);

        $salesOrders = $salesOrdersQuery->count();

        /*
        |--------------------------------------------------------------------------
        | SALES REVENUE
        |--------------------------------------------------------------------------
        */

        $salesRevenue = (float) $salesOrdersQuery
            ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | ITEMS SOLD
        |--------------------------------------------------------------------------
        */

        $itemsSold = DB::table('order_items')
            ->join(
                'orders',
                'orders.id',
                '=',
                'order_items.order_id'
            )
            ->whereBetween(
                'orders.created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn(
                'orders.status',
                ['cancelled']
            )
            ->sum('order_items.quantity');

        /*
        |--------------------------------------------------------------------------
        | AVERAGE ORDER VALUE
        |--------------------------------------------------------------------------
        */

        $averageOrderValue = $salesOrders > 0
            ? $salesRevenue / $salesOrders
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TOP SELLING PRODUCTS
        |--------------------------------------------------------------------------
        */

        $topSellingProducts = Product::select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw(
                    'SUM(order_items.quantity) as total_quantity'
                ),
                DB::raw(
                    'SUM(order_items.total) as total_sales'
                )
            )
            ->join(
                'order_items',
                'products.id',
                '=',
                'order_items.product_id'
            )
            ->join(
                'orders',
                'orders.id',
                '=',
                'order_items.order_id'
            )
            ->whereBetween(
                'orders.created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn(
                'orders.status',
                ['cancelled']
            )
            ->groupBy(
                'products.id',
                'products.name',
                'products.image'
            )
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DAILY SALES
        |--------------------------------------------------------------------------
        */

        $dailySales = Order::select(
                DB::raw('DATE(created_at) as sale_date'),
                DB::raw('SUM(total_price) as total_sales'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn(
                'status',
                ['cancelled']
            )
            ->groupBy(
                DB::raw('DATE(created_at)')
            )
            ->orderBy(
                DB::raw('DATE(created_at)')
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER INSIGHTS
        |--------------------------------------------------------------------------
        |
        | Customers are calculated using the same selected sales period.
        |
        */

        /*
        | Customers who placed at least one order
        */

        $activeCustomerIds = Order::whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn('status', ['cancelled'])
            ->whereNotNull('customer_id')
            ->distinct()
            ->pluck('customer_id');

        $activeCustomers = $activeCustomerIds->count();

        /*
        | New customers created during selected period
        */

        $newCustomers = Customer::whereBetween(
            'created_at',
            [$startDate, $endDate]
        )->count();

        /*
        | Repeat customers
        |
        | Customers having 2 or more valid orders
        | during the selected period.
        */

        $repeatCustomers = Order::whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn('status', ['cancelled'])
            ->whereNotNull('customer_id')
            ->select(
                'customer_id',
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('customer_id')
            ->havingRaw('COUNT(*) >= 2')
            ->get()
            ->count();

        /*
        | One-time customers
        */

        $oneTimeCustomers = max(
            0,
            $activeCustomers - $repeatCustomers
        );

        /*
        | Revenue from repeat customers
        */

        $repeatCustomerIds = Order::whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn('status', ['cancelled'])
            ->whereNotNull('customer_id')
            ->select(
                'customer_id',
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('customer_id')
            ->havingRaw('COUNT(*) >= 2')
            ->pluck('customer_id');

        $repeatCustomerRevenue = 0;

        if ($repeatCustomerIds->isNotEmpty()) {

            $repeatCustomerRevenue = (float) Order::whereBetween(
                    'created_at',
                    [$startDate, $endDate]
                )
                ->whereNotIn('status', ['cancelled'])
                ->whereIn('customer_id', $repeatCustomerIds)
                ->sum('total_price');
        }

        /*
        | Average orders per active customer
        */

        $averageOrdersPerCustomer = $activeCustomers > 0
            ? $salesOrders / $activeCustomers
            : 0;

        /*
        | Repeat customer percentage
        */

        $repeatCustomerPercentage = $activeCustomers > 0
            ? ($repeatCustomers / $activeCustomers) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TOP CUSTOMERS
        |--------------------------------------------------------------------------
        */

        $topCustomers = Customer::select(
                'customers.id',
                'customers.name',
                'customers.email',
                DB::raw(
                    'COUNT(orders.id) as total_orders'
                ),
                DB::raw(
                    'SUM(orders.total_price) as total_spent'
                )
            )
            ->join(
                'orders',
                'customers.id',
                '=',
                'orders.customer_id'
            )
            ->whereBetween(
                'orders.created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn(
                'orders.status',
                ['cancelled']
            )
            ->groupBy(
                'customers.id',
                'customers.name',
                'customers.email'
            )
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER INSIGHTS CHART DATA
        |--------------------------------------------------------------------------
        */

        $customerDailyData = Order::select(
                DB::raw('DATE(created_at) as sale_date'),
                DB::raw(
                    'COUNT(DISTINCT customer_id) as customers'
                )
            )
            ->whereBetween(
                'created_at',
                [$startDate, $endDate]
            )
            ->whereNotIn(
                'status',
                ['cancelled']
            )
            ->whereNotNull('customer_id')
            ->groupBy(
                DB::raw('DATE(created_at)')
            )
            ->orderBy(
                DB::raw('DATE(created_at)')
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard',
            compact(

                // Basic statistics
                'admin',
                'totalRevenue',
                'totalOrders',
                'totalCustomers',
                'totalProducts',

                // Recent orders
                'recentOrders',

                // Products
                'topProducts',

                // Stock
                'lowStockProducts',
                'outOfStockProducts',

                // Sales report
                'period',
                'startDate',
                'endDate',
                'salesOrders',
                'salesRevenue',
                'itemsSold',
                'averageOrderValue',
                'topSellingProducts',
                'dailySales',

                // Customer insights
                'activeCustomers',
                'newCustomers',
                'repeatCustomers',
                'oneTimeCustomers',
                'repeatCustomerRevenue',
                'averageOrdersPerCustomer',
                'repeatCustomerPercentage',
                'topCustomers',
                'customerDailyData'
            )
        );
    }
}

