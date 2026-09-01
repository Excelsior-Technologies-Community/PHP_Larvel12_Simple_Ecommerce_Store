@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('styles')

<style>

    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--glass-shadow);
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .dashboard-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--glass-shadow);
    }

    .low-stock-item {
        border-bottom: 1px solid rgba(0,0,0,0.08);
        padding: 14px 0;
    }

    .low-stock-item:last-child {
        border-bottom: none;
    }

    .stock-badge {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 20px;
    }

    .report-stat {
        border-radius: 15px;
        padding: 18px;
        background: rgba(255,255,255,0.35);
        border: 1px solid rgba(255,255,255,0.25);
    }

    .report-stat h4 {
        margin-bottom: 0;
        font-weight: 700;
    }

    .product-image-small {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .customer-stat {
        border-radius: 16px;
        padding: 20px;
        background: rgba(255,255,255,0.35);
        border: 1px solid rgba(255,255,255,0.25);
        height: 100%;
    }

    .customer-stat .icon {
        font-size: 25px;
        margin-bottom: 10px;
    }

    .customer-stat h3 {
        font-weight: 700;
        margin-bottom: 3px;
    }

    .customer-progress {
        height: 8px;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(0,0,0,0.08);
    }

    .customer-progress-bar {
        height: 100%;
        border-radius: 10px;
    }

    .customer-row {
        border-bottom: 1px solid rgba(0,0,0,0.08);
        padding: 13px 0;
    }

    .customer-row:last-child {
        border-bottom: none;
    }

    .customer-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        background: rgba(13,110,253,0.12);
        color: #0d6efd;
    }

</style>

@endsection


@section('content')


{{-- ========================================================= --}}
{{-- BASIC STATISTICS --}}
{{-- ========================================================= --}}

<div class="row g-4 mb-4">

    {{-- Revenue --}}
    <div class="col-md-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <p class="text-muted small mb-1">
                        Total Revenue
                    </p>

                    <h3 class="fw-bold mb-0">
                        ₹ {{ number_format($totalRevenue ?? 0, 0) }}
                    </h3>

                </div>

                <div
                    class="stat-icon"
                    style="background: linear-gradient(135deg, #667eea20, #764ba220);"
                >
                    💰
                </div>

            </div>

        </div>

    </div>


    {{-- Orders --}}
    <div class="col-md-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <p class="text-muted small mb-1">
                        Total Orders
                    </p>

                    <h3 class="fw-bold mb-0">
                        {{ $totalOrders ?? 0 }}
                    </h3>

                </div>

                <div
                    class="stat-icon"
                    style="background: linear-gradient(135deg, #f093fb20, #f5576c20);"
                >
                    🛒
                </div>

            </div>

        </div>

    </div>


    {{-- Customers --}}
    <div class="col-md-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <p class="text-muted small mb-1">
                        Total Customers
                    </p>

                    <h3 class="fw-bold mb-0">
                        {{ $totalCustomers ?? 0 }}
                    </h3>

                </div>

                <div
                    class="stat-icon"
                    style="background: linear-gradient(135deg, #4facfe20, #00f2fe20);"
                >
                    👥
                </div>

            </div>

        </div>

    </div>


    {{-- Products --}}
    <div class="col-md-3">

        <div class="stat-card">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <p class="text-muted small mb-1">
                        Total Products
                    </p>

                    <h3 class="fw-bold mb-0">
                        {{ $totalProducts ?? 0 }}
                    </h3>

                </div>

                <div
                    class="stat-icon"
                    style="background: linear-gradient(135deg, #43e97b20, #38f9d720);"
                >
                    📦
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- LOW STOCK ALERT --}}
{{-- ========================================================= --}}

<div class="row g-4 mb-4">

    <div class="col-lg-8">

        <div class="dashboard-card p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h4 class="fw-bold mb-1">
                        ⚠️ Low Stock Alerts
                    </h4>

                    <small class="text-muted">
                        Products that need stock attention
                    </small>

                </div>

                <a
                    href="{{ route('admin.stock.index') }}"
                    class="btn btn-sm btn-primary"
                >
                    Manage Stock
                </a>

            </div>


            @forelse($lowStockProducts ?? [] as $product)

                <div class="low-stock-item">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="fw-bold">
                                {{ $product->name }}
                            </div>

                            <small class="text-muted">
                                SKU:
                                {{ $product->sku ?? 'N/A' }}
                            </small>

                        </div>


                        <div class="text-end">

                            @if($product->stock_quantity <= 0)

                                <span class="badge bg-danger stock-badge">
                                    Out of Stock
                                </span>

                            @else

                                <span class="badge bg-warning text-dark stock-badge">
                                    {{ $product->stock_quantity }}
                                    left
                                </span>

                            @endif

                            <div>

                                <small class="text-muted">
                                    Threshold:
                                    {{ $product->low_stock_threshold }}
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-4">

                    <div style="font-size:40px;">
                        ✅
                    </div>

                    <h6 class="fw-bold mt-2">
                        Stock is healthy
                    </h6>

                    <p class="text-muted small mb-0">
                        No products are currently below their stock threshold.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    {{-- OUT OF STOCK --}}

    <div class="col-lg-4">

        <div class="dashboard-card p-4">

            <h4 class="fw-bold mb-1">
                🚨 Out of Stock
            </h4>

            <small class="text-muted">
                Products currently unavailable
            </small>


            <div class="mt-3">

                @forelse($outOfStockProducts ?? [] as $product)

                    <div class="low-stock-item">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="fw-bold small">
                                    {{ $product->name }}
                                </div>

                                <small class="text-muted">
                                    SKU:
                                    {{ $product->sku ?? 'N/A' }}
                                </small>

                            </div>

                            <span class="badge bg-danger stock-badge">
                                0
                            </span>

                        </div>

                    </div>

                @empty

                    <p class="text-muted small mt-3 mb-0">
                        No out-of-stock products.
                    </p>

                @endforelse

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SALES REPORT --}}
{{-- ========================================================= --}}

<div class="dashboard-card p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                📊 Sales Report
            </h4>

            <small class="text-muted">
                Track your store's sales performance
            </small>

        </div>

    </div>


    {{-- FILTER --}}

    <form
        method="GET"
        action="{{ route('admin.dashboard') }}"
        class="row g-3 mb-4"
    >

        <div class="col-md-3">

            <label class="form-label fw-semibold">
                Period
            </label>

            <select
                name="period"
                id="salesPeriod"
                class="form-select"
            >

                <option
                    value="today"
                    {{ $period === 'today' ? 'selected' : '' }}
                >
                    Today
                </option>

                <option
                    value="7"
                    {{ $period === '7' ? 'selected' : '' }}
                >
                    Last 7 Days
                </option>

                <option
                    value="30"
                    {{ $period === '30' ? 'selected' : '' }}
                >
                    Last 30 Days
                </option>

                <option
                    value="90"
                    {{ $period === '90' ? 'selected' : '' }}
                >
                    Last 90 Days
                </option>

                <option
                    value="custom"
                    {{ $period === 'custom' ? 'selected' : '' }}
                >
                    Custom Range
                </option>

            </select>

        </div>


        <div
            class="col-md-3 custom-date"
            style="{{ $period === 'custom' ? '' : 'display:none;' }}"
        >

            <label class="form-label fw-semibold">
                Start Date
            </label>

            <input
                type="date"
                name="start_date"
                class="form-control"
                value="{{ request('start_date') }}"
            >

        </div>


        <div
            class="col-md-3 custom-date"
            style="{{ $period === 'custom' ? '' : 'display:none;' }}"
        >

            <label class="form-label fw-semibold">
                End Date
            </label>

            <input
                type="date"
                name="end_date"
                class="form-control"
                value="{{ request('end_date') }}"
            >

        </div>


        <div class="col-md-3 d-flex align-items-end">

            <button
                type="submit"
                class="btn btn-primary w-100"
            >
                Apply Report
            </button>

        </div>

    </form>


    {{-- REPORT STATISTICS --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="report-stat">

                <small class="text-muted">
                    Sales Revenue
                </small>

                <h4>
                    ₹ {{ number_format($salesRevenue ?? 0, 2) }}
                </h4>

            </div>

        </div>


        <div class="col-md-3">

            <div class="report-stat">

                <small class="text-muted">
                    Orders
                </small>

                <h4>
                    {{ $salesOrders ?? 0 }}
                </h4>

            </div>

        </div>


        <div class="col-md-3">

            <div class="report-stat">

                <small class="text-muted">
                    Items Sold
                </small>

                <h4>
                    {{ $itemsSold ?? 0 }}
                </h4>

            </div>

        </div>


        <div class="col-md-3">

            <div class="report-stat">

                <small class="text-muted">
                    Average Order Value
                </small>

                <h4>
                    ₹ {{ number_format($averageOrderValue ?? 0, 2) }}
                </h4>

            </div>

        </div>

    </div>


    {{-- TOP SELLING PRODUCTS + DAILY SALES --}}

    <div class="row g-4">

        <div class="col-lg-7">

            <h5 class="fw-bold mb-3">
                Top Selling Products
            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>
                                Product
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Sales
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($topSellingProducts ?? [] as $product)

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        @if($product->image)

                                            <img
                                                src="{{ asset('images/' . $product->image) }}"
                                                class="product-image-small"
                                                alt="{{ $product->name }}"
                                                onerror="this.style.display='none';"
                                            >

                                        @endif

                                        <span class="fw-semibold">
                                            {{ $product->name }}
                                        </span>

                                    </div>

                                </td>

                                <td>
                                    {{ $product->total_quantity }}
                                </td>

                                <td>
                                    ₹ {{ number_format($product->total_sales, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center text-muted py-4"
                                >
                                    No sales found for this period.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="col-lg-5">

            <h5 class="fw-bold mb-3">
                Daily Sales
            </h5>

            <div class="table-responsive">

                <table class="table table-sm table-hover">

                    <thead>

                        <tr>

                            <th>
                                Date
                            </th>

                            <th>
                                Orders
                            </th>

                            <th>
                                Sales
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($dailySales ?? [] as $sale)

                            <tr>

                                <td>
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M') }}
                                </td>

                                <td>
                                    {{ $sale->total_orders }}
                                </td>

                                <td>
                                    ₹ {{ number_format($sale->total_sales, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center text-muted py-4"
                                >
                                    No sales data.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- CUSTOMER INSIGHTS --}}
{{-- ========================================================= --}}

<div class="dashboard-card p-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                👥 Customer Insights
            </h4>

            <small class="text-muted">
                Understand customer activity and purchasing behaviour
            </small>

        </div>

        <span class="badge bg-primary">
            {{ ucfirst($period === 'custom' ? 'Custom Range' : $period . ' Days') }}
        </span>

    </div>


    {{-- CUSTOMER STATISTICS --}}

    <div class="row g-3 mb-4">

        {{-- Active Customers --}}

        <div class="col-md-3">

            <div class="customer-stat">

                <div class="icon">
                    👥
                </div>

                <small class="text-muted">
                    Active Customers
                </small>

                <h3>
                    {{ $activeCustomers ?? 0 }}
                </h3>

                <small class="text-muted">
                    Customers who placed orders
                </small>

            </div>

        </div>


        {{-- New Customers --}}

        <div class="col-md-3">

            <div class="customer-stat">

                <div class="icon">
                    🆕
                </div>

                <small class="text-muted">
                    New Customers
                </small>

                <h3>
                    {{ $newCustomers ?? 0 }}
                </h3>

                <small class="text-muted">
                    Registered during this period
                </small>

            </div>

        </div>


        {{-- Repeat Customers --}}

        <div class="col-md-3">

            <div class="customer-stat">

                <div class="icon">
                    🔁
                </div>

                <small class="text-muted">
                    Repeat Customers
                </small>

                <h3>
                    {{ $repeatCustomers ?? 0 }}
                </h3>

                <small class="text-muted">
                    {{ number_format($repeatCustomerPercentage ?? 0, 1) }}%
                    of active customers
                </small>

            </div>

        </div>


        {{-- Repeat Revenue --}}

        <div class="col-md-3">

            <div class="customer-stat">

                <div class="icon">
                    💰
                </div>

                <small class="text-muted">
                    Repeat Customer Revenue
                </small>

                <h3>
                    ₹ {{ number_format($repeatCustomerRevenue ?? 0, 0) }}
                </h3>

                <small class="text-muted">
                    Revenue from repeat buyers
                </small>

            </div>

        </div>

    </div>


    {{-- CUSTOMER BREAKDOWN + TOP CUSTOMERS --}}

    <div class="row g-4">

        {{-- CUSTOMER BREAKDOWN --}}

        <div class="col-lg-5">

            <h5 class="fw-bold mb-3">
                Customer Breakdown
            </h5>

            <div class="p-3 rounded-4 border">

                {{-- Repeat Customers --}}

                <div class="d-flex justify-content-between mb-2">

                    <span>
                        🔁 Repeat Customers
                    </span>

                    <strong>
                        {{ $repeatCustomers ?? 0 }}
                    </strong>

                </div>

                <div class="customer-progress mb-3">

                    @php
                        $repeatWidth = ($activeCustomers ?? 0) > 0
                            ? (($repeatCustomers ?? 0) / $activeCustomers) * 100
                            : 0;
                    @endphp

                    <div
                        class="customer-progress-bar bg-success"
                        style="width: {{ min(100, $repeatWidth) }}%;"
                    ></div>

                </div>


                {{-- One Time Customers --}}

                <div class="d-flex justify-content-between mb-2">

                    <span>
                        🛒 One-Time Customers
                    </span>

                    <strong>
                        {{ $oneTimeCustomers ?? 0 }}
                    </strong>

                </div>

                <div class="customer-progress mb-3">

                    @php
                        $oneTimeWidth = ($activeCustomers ?? 0) > 0
                            ? (($oneTimeCustomers ?? 0) / $activeCustomers) * 100
                            : 0;
                    @endphp

                    <div
                        class="customer-progress-bar bg-warning"
                        style="width: {{ min(100, $oneTimeWidth) }}%;"
                    ></div>

                </div>


                {{-- Average Orders --}}

                <div class="d-flex justify-content-between mt-4">

                    <span>
                        📦 Avg. Orders / Customer
                    </span>

                    <strong>
                        {{ number_format($averageOrdersPerCustomer ?? 0, 2) }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- TOP CUSTOMERS --}}

        <div class="col-lg-7">

            <h5 class="fw-bold mb-3">
                🏆 Top Customers
            </h5>

            @forelse($topCustomers ?? [] as $customer)

                <div class="customer-row">

                    <div class="d-flex align-items-center justify-content-between">

                        <div class="d-flex align-items-center gap-3">

                            <div class="customer-avatar">

                                {{ strtoupper(
                                    substr($customer->name ?? 'C', 0, 1)
                                ) }}

                            </div>

                            <div>

                                <div class="fw-bold">
                                    {{ $customer->name ?? 'N/A' }}
                                </div>

                                <small class="text-muted">
                                    {{ $customer->email ?? 'No email' }}
                                </small>

                            </div>

                        </div>


                        <div class="text-end">

                            <div class="fw-bold">
                                ₹ {{ number_format($customer->total_spent ?? 0, 2) }}
                            </div>

                            <small class="text-muted">
                                {{ $customer->total_orders ?? 0 }}
                                orders
                            </small>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted py-4">

                    <div style="font-size:40px;">
                        👥
                    </div>

                    <p class="mb-0">
                        No customer activity found for this period.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- RECENT ORDERS + TOP PRODUCTS --}}
{{-- ========================================================= --}}

<div class="row g-4">

    {{-- Recent Orders --}}

    <div class="col-lg-8">

        <div class="glass-card p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="fw-bold mb-0">
                    Recent Orders
                </h4>

                <a
                    href="{{ route('admin.orders.index') }}"
                    class="btn btn-sm btn-primary-glass"
                >
                    View All
                </a>

            </div>


            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>
                                Order ID
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($recentOrders ?? [] as $order)

                            <tr>

                                <td>
                                    #{{ $order->id }}
                                </td>

                                <td>
                                    {{ $order->customer->name ?? 'N/A' }}
                                </td>

                                <td>
                                    ₹ {{ number_format($order->total_price, 2) }}
                                </td>

                                <td>

                                    <span
                                        class="badge {{ $order->status_badge }}"
                                    >
                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $order->status
                                            )
                                        ) }}
                                    </span>

                                </td>

                                <td>
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted"
                                >
                                    No orders yet
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Top Products --}}

    <div class="col-lg-4">

        <div class="glass-card p-4">

            <h4 class="fw-bold mb-3">
                Top Products
            </h4>

            @forelse($topProducts ?? [] as $product)

                <div
                    class="d-flex justify-content-between align-items-center py-2"
                    style="border-bottom: 1px solid rgba(255,255,255,0.3);"
                >

                    <div>

                        <p class="fw-bold mb-0 small">
                            {{ $product->name }}
                        </p>

                        <small class="text-muted">
                            {{ $product->order_items_count }}
                            sales
                        </small>

                    </div>

                </div>

            @empty

                <p class="text-muted small">
                    No products sold yet
                </p>

            @endforelse

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const period = document.getElementById('salesPeriod');

        const customDates =
            document.querySelectorAll('.custom-date');


        function toggleCustomDates() {

            if (period.value === 'custom') {

                customDates.forEach(function (element) {

                    element.style.display = '';

                });

            } else {

                customDates.forEach(function (element) {

                    element.style.display = 'none';

                });

            }

        }


        period.addEventListener(
            'change',
            toggleCustomDates
        );

        toggleCustomDates();

    });

</script>

@endsection

