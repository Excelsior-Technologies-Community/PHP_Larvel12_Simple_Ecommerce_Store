@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<a href="{{ route('admin.customers.index') }}" class="btn btn-glass mb-3">← Back to Customers</a>

<div class="row">
    <div class="col-lg-4">
        <div class="glass-card p-4 mb-4">
            <div class="text-center">
                <img src="{{ $customer->profile_image ? asset('images/'.$customer->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&background=667eea&color=fff' }}"
                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid rgba(255,255,255,0.6); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <h4 class="fw-bold mt-3">{{ $customer->name }}</h4>
                <p class="text-muted">{{ $customer->email }}</p>
                <p class="text-muted small">Joined {{ $customer->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="glass-card p-4 mb-4">
            <h4 class="fw-bold mb-3">Customer Info</h4>
            <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="form-control glass-form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-control glass-form-control" required>
                </div>
                <button type="submit" class="btn btn-primary-glass">Update Customer</button>
            </form>
        </div>

        <div class="glass-card p-4">
            <h4 class="fw-bold mb-3">Recent Orders</h4>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders()->latest()->take(10)->get() as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>₹ {{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <span class="badge {{ $order->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
