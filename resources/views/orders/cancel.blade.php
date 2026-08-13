@extends('layouts.customer')

@section('title', 'Cancel Order')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="glass-card p-5">
                <h2 class="fw-bold mb-4">Cancel Order #{{ $order->id }}</h2>

                <div class="alert alert-warning mb-4">
                    <strong>Warning:</strong> This action cannot be undone. Are you sure you want to cancel this order?
                </div>

                <div class="mb-4">
                    <p><strong>Order Total:</strong> ₹ {{ number_format($order->total_price, 2) }}</p>
                    <p><strong>Items:</strong> {{ $order->items->count() }}</p>
                </div>

                <form action="{{ route('orders.cancel.store', $order) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Reason for Cancellation</label>
                        <textarea name="reason" class="form-control glass-form-control" rows="4" required placeholder="Please explain why you want to cancel this order..."></textarea>
                        @error('reason')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-glass">Go Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
