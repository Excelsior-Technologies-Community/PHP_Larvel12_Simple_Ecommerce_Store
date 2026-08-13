@extends('layouts.customer')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <h1 class="display-6 fw-bold mb-4" style="color: #1a1a2e;">My Orders</h1>

    {{-- Filters --}}
    <div class="glass-card p-4 mb-4">
        <form method="GET" action="{{ route('customer.orders') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control glass-form-control" placeholder="Search orders...">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select glass-form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="on_the_way" {{ ($status ?? '') == 'on_the_way' ? 'selected' : '' }}>On the Way</option>
                    <option value="shipped" {{ ($status ?? '') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ ($status ?? '') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="price_sort" class="form-select glass-form-control">
                    <option value="">Sort by Price</option>
                    <option value="high" {{ ($priceSort ?? '') == 'high' ? 'selected' : '' }}>High to Low</option>
                    <option value="low" {{ ($priceSort ?? '') == 'low' ? 'selected' : '' }}>Low to High</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary-glass w-100">Apply</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('customer.orders') }}" class="btn btn-glass w-100">Reset</a>
            </div>
        </form>
    </div>

    {{-- Orders List --}}
    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="glass-card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold mb-1">Order #{{ $order->id }}</h5>
                        <p class="text-muted small mb-1">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                        <span class="badge badge-glass">
                            @php
                                $badgeClass = match($order->status) {
                                    'pending' => 'bg-warning',
                                    'on_the_way' => 'bg-info',
                                    'shipped' => 'bg-primary',
                                    'delivered' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        </span>
                    </div>
                    <div class="text-end">
                        <p class="fw-bold mb-0" style="font-size: 18px; color: #667eea;">₹ {{ number_format($order->total_price, 2) }}</p>
                        <span class="text-muted small">{{ $order->items->count() }} items</span>
                    </div>
                </div>

                <hr style="border-color: rgba(255,255,255,0.3); margin: 16px 0;">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-muted mb-0">Payment: {{ $order->payment_method }}</p>
                        <p class="small text-muted mb-0">
                            @if($order->tracking_number)
                                Tracking: <strong>{{ $order->tracking_number }}</strong>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-primary-glass btn-sm">
                        View Details
                    </a>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="glass-card p-5 text-center">
            <p style="font-size: 64px; margin-bottom: 16px;">📦</p>
            <h4 class="text-muted">No orders yet</h4>
            <a href="{{ route('customer.products') }}" class="btn btn-primary-glass mt-3">Start Shopping</a>
        </div>
    @endif
</div>
@endsection
