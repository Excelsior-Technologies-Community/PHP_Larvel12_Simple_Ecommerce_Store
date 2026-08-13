@extends('layouts.customer')

@section('title', 'Order #'.$order->id)

@section('content')
<div class="container">
    <a href="{{ route('customer.orders') }}" class="btn btn-glass mb-3">← Back to Orders</a>

    {{-- Order Header --}}
    <div class="glass-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-2">Order #{{ $order->id }}</h2>
                <p class="text-muted mb-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                <span class="badge {{ $order->status_badge }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <div class="text-end">
                <p class="fw-bold mb-0" style="font-size: 24px; color: #667eea;">₹ {{ number_format($order->total_price, 2) }}</p>
                <p class="text-muted small">{{ $order->payment_method }} Payment</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Order Tracking Timeline --}}
            <div class="glass-card p-4 mb-4">
                <h4 class="fw-bold mb-4">Order Tracking</h4>
                <div class="timeline">
                    @php
                        $timeline = $order->tracking_timeline;
                    @endphp
                    @foreach($timeline as $index => $event)
                        <div class="timeline-item {{ $event['completed'] ? 'completed' : '' }} {{ $event['active'] ? 'active' : '' }}">
                            <h6 class="fw-bold mb-0">{{ $event['status'] }}</h6>
                            <small class="text-muted">{{ $event['date'] ? \Carbon\Carbon::parse($event['date'])->format('M d, Y h:i A') : 'Pending' }}</small>
                        </div>
                    @endforeach
                </div>

                @if($order->tracking_number)
                    <div class="mt-3 p-3" style="background: rgba(102,126,234,0.05); border-radius: 12px;">
                        <p class="mb-1"><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
                        @if($order->courier_name)
                            <p class="mb-1"><strong>Courier:</strong> {{ $order->courier_name }}</p>
                        @endif
                        @if($order->tracking_url)
                            <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-sm btn-primary-glass">Track Package</a>
                        @endif
                    </div>
                @endif
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
                                @if($item->category_id)Category: {{ $item->category->category_name ?? 'N/A' }} @endif
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
            {{-- Order Summary --}}
            <div class="glass-card p-4 mb-3" style="position: sticky; top: 100px;">
                <h4 class="fw-bold mb-3">Order Summary</h4>
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
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold" style="color: #667eea; font-size: 20px;">₹ {{ number_format($order->total_price, 2) }}</span>
                </div>

                <a href="{{ route('orders.invoice', $order) }}" class="btn btn-glass w-100 mb-2">
                    Download Invoice
                </a>

                @if(in_array($order->status, ['pending', 'on_the_way']) && !$order->cancellation)
                    <a href="{{ route('orders.cancel.create', $order) }}" class="btn btn-danger w-100 mb-2">
                        Cancel Order
                    </a>
                @endif

                @if($order->status == 'delivered' && !$order->cancellation)
                    <a href="{{ route('orders.return.create', $order) }}" class="btn btn-glass w-100">
                        Return Order
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
