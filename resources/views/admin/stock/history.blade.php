@extends('layouts.admin')

@section('title', 'Stock History')

@section('content')
<div class="glass-card p-4">
    <h4 class="fw-bold mb-3">Stock History</h4>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Previous</th>
                    <th>New</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('M d, Y h:i A') }}</td>
                        <td>{{ $history->product->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $history->type == 'in' ? 'bg-success' : ($history->type == 'out' ? 'bg-danger' : 'bg-warning') }}">
                                {{ ucfirst($history->type) }}
                            </span>
                        </td>
                        <td>{{ $history->quantity }}</td>
                        <td>{{ $history->previous_stock }}</td>
                        <td>{{ $history->new_stock }}</td>
                        <td>{{ $history->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No stock history</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $histories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
