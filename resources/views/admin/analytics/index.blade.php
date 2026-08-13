@extends('layouts.admin')

@section('title', 'Analytics')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h3 class="fw-bold mb-0">₹ {{ number_format($totalRevenue ?? 0, 0) }}</h3>
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
                    <h3 class="fw-bold mb-0">{{ $totalOrders ?? 0 }}</h3>
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
                    <h3 class="fw-bold mb-0">{{ $totalCustomers ?? 0 }}</h3>
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
                    <h3 class="fw-bold mb-0">{{ $totalProducts ?? 0 }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b20, #38f9d720);">📦</div>
            </div>
        </div>
    </div>
</div>

{{-- Sales Chart --}}
<div class="glass-card p-4 mb-4">
    <h4 class="fw-bold mb-4">Sales Analytics (Last 30 Days)</h4>
    <canvas id="salesChart" height="100"></canvas>
</div>

<div class="row g-4">
    {{-- Top Products --}}
    <div class="col-lg-6">
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

    {{-- Recent Orders --}}
    <div class="col-lg-6">
        <div class="glass-card p-4">
            <h4 class="fw-bold mb-3">Recent Orders</h4>
            @forelse($recentOrders ?? [] as $order)
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid rgba(255,255,255,0.3);">
                    <div>
                        <p class="fw-bold mb-0 small">Order #{{ $order->id }}</p>
                        <small class="text-muted">{{ $order->customer->name ?? 'N/A' }}</small>
                    </div>
                    <span class="fw-bold">₹ {{ number_format($order->total_price, 2) }}</span>
                </div>
            @empty
                <p class="text-muted small">No orders yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels ?? []) !!},
        datasets: [{
            label: 'Sales (₹)',
            data: {!! json_encode($chartValues ?? []) !!},
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#667eea'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});
</script>
@endsection
