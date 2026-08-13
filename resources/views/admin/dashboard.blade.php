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
</style>
@endsection

@section('content')
{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h3 class="fw-bold mb-0" style="color: #1a1a2e;">₹ {{ number_format($totalRevenue ?? 0, 0) }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea20, #764ba220);">💰</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Orders</p>
                    <h3 class="fw-bold mb-0" style="color: #1a1a2e;">{{ $totalOrders ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb20, #f5576c20);">🛒</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Customers</p>
                    <h3 class="fw-bold mb-0" style="color: #1a1a2e;">{{ $totalCustomers ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe20, #00f2fe20);">👥</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Products</p>
                    <h3 class="fw-bold mb-0" style="color: #1a1a2e;">{{ $totalProducts ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b20, #38f9d720);">📦</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent Orders --}}
    <div class="col-lg-8">
        <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Recent Orders</h4>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary-glass">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                <td>₹ {{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <span class="badge {{ $order->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="col-lg-4">
        <div class="glass-card p-4">
            <h4 class="fw-bold mb-3">Top Products</h4>
            @forelse($topProducts ?? [] as $product)
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid rgba(255,255,255,0.3);">
                    <div>
                        <p class="fw-bold mb-0 small">{{ $product->name }}</p>
                        <small class="text-muted">{{ $product->orderItems->count() }} sales</small>
                    </div>
                </div>
            @empty
                <p class="text-muted small">No products sold yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
