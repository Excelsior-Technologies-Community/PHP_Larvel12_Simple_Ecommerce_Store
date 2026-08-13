@extends('layouts.admin')

@section('title', 'Manage Coupons')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Coupons</h2>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary-glass">+ Add Coupon</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Usage</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>
                            @if($coupon->type == 'percentage')
                                {{ $coupon->value }}%
                            @else
                                ₹ {{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                        <td>
                            @if($coupon->starts_at)
                                {{ $coupon->starts_at->format('M d, Y') }}
                            @else
                                No start
                            @endif
                            -
                            @if($coupon->expires_at)
                                {{ $coupon->expires_at->format('M d, Y') }}
                            @else
                                No expiry
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No coupons found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $coupons->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
