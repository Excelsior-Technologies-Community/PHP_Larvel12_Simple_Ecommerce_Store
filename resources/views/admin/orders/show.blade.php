@extends('layouts.admin')

@section('title', 'Order #'.$order->id)

@section('content')
<a href="{{ route('admin.orders.index') }}" class="btn btn-glass mb-3">← Back to Orders</a>

<div class="row">
    <div class="col-lg-8">
        {{-- Order Details --}}
        <div class="glass-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h2 class="fw-bold mb-2">Order #{{ $order->id }}</h2>
                    <p class="text-muted">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select glass-form-control">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="on_the_way" {{ $order->status == 'on_the_way' ? 'selected' : '' }}>On the Way</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                        <button type="submit" class="btn btn-primary-glass">Update</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="glass-card p-4 mb-4">
            <h4 class="fw-bold mb-3">Order Items</h4>
            @foreach($order->items as $item)
                <div class="d-flex gap-3 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.3);">
                     <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('images/'.$item->product->image) }}"
                         alt="{{ $item->product->name }}"
                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px;"
                         onerror="this.src='https://via.placeholder.com/80x80?text=N/A'">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                        <small class="text-muted">
                            @if($item->size_id)Size: {{ $item->size->size_name ?? 'N/A' }} @endif
                            @if($item->color_id)Color: {{ $item->color->color_name ?? 'N/A' }} @endif
                        </small>
                        <p class="mb-0">Qty: {{ $item->quantity }} × ₹ {{ number_format($item->price, 2) }}</p>
                    </div>
                    <div class="text-end">
                        <p class="fw-bold mb-0">₹ {{ number_format($item->total, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Customer Info --}}
        <div class="glass-card p-4 mb-3">
            <h4 class="fw-bold mb-3">Customer Info</h4>
            <p class="mb-1"><strong>Name:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
            <p class="mb-1"><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
            <a href="{{ route('admin.customers.show', $order->customer) }}" class="btn btn-sm btn-glass">View Customer</a>
        </div>

        {{-- Shipping Address --}}
        <div class="glass-card p-4 mb-3">
            <h4 class="fw-bold mb-3">Shipping Address</h4>
            <p class="mb-0">
                {{ $order->address->full_name ?? 'N/A' }}<br>
                {{ $order->address->address ?? '' }}<br>
                {{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }} - {{ $order->address->pincode ?? '' }}
            </p>
        </div>

        {{-- Payment Info --}}
        <div class="glass-card p-4 mb-3">
            <h4 class="fw-bold mb-3">Payment Info</h4>
            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span>₹ {{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Discount</span>
                    <span>- ₹ {{ number_format($order->discount_amount, 2) }}</span>
                </div>
            @endif
            <hr style="border-color: rgba(255,255,255,0.3);">
            <div class="d-flex justify-content-between">
                <span class="fw-bold">Total</span>
                <span class="fw-bold" style="color: #667eea;">₹ {{ number_format($order->total_price, 2) }}</span>
            </div>
            <p class="mt-2 mb-0"><strong>Method:</strong> {{ $order->payment_method }}</p>
        </div>

        <a href="{{ route('orders.invoice', $order) }}" class="btn btn-primary-glass w-100" target="_blank">
            Download Invoice
        </a>
    </div>
</div>
@endsection
