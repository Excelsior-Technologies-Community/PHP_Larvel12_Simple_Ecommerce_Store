@extends('layouts.admin')

@section('title', 'Cancellation & Return Requests')

@section('content')
<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cancellations as $cancellation)
                    <tr>
                        <td>#{{ $cancellation->order_id }}</td>
                        <td>{{ $cancellation->customer->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $cancellation->type == 'cancel' ? 'bg-danger' : 'bg-warning' }}">
                                {{ ucfirst($cancellation->type) }}
                            </span>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($cancellation->reason, 50) }}</td>
                        <td>
                            <span class="badge {{ $cancellation->status == 'approved' ? 'bg-success' : ($cancellation->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($cancellation->status) }}
                            </span>
                        </td>
                        <td>
                            @if($cancellation->status == 'pending')
                                <form action="{{ route('admin.cancellations.approve', $cancellation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <form action="{{ route('admin.cancellations.reject', $cancellation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            @else
                                <span class="text-muted small">Processed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No requests found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $cancellations->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
