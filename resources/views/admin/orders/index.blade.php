@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')
{{-- Filters --}}
<div class="glass-card p-4 mb-4">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
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
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date" value="{{ $date ?? '' }}" class="form-control glass-form-control">
        </div>
        <div class="col-md-2">
            <select name="price_sort" class="form-select glass-form-control">
                <option value="">Sort by Price</option>
                <option value="high" {{ ($priceSort ?? '') == 'high' ? 'selected' : '' }}>High to Low</option>
                <option value="low" {{ ($priceSort ?? '') == 'low' ? 'selected' : '' }}>Low to High</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary-glass w-100">Filter</button>
        </div>
    </form>
</div>

{{-- Orders Table --}}
<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><input type="checkbox" class="order-checkbox" value="{{ $order->id }}"></td>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                        <td>{{ $order->items->count() }} items</td>
                        <td>₹ {{ number_format($order->total_price, 2) }}</td>
                        <td>{{ $order->payment_method }}</td>
                        <td>
                            <span class="badge {{ $order->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-glass">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No orders found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->count() > 0)
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex gap-2">
                <select id="bulkStatus" class="form-select glass-form-control" style="width: auto;">
                    <option value="">Update Status</option>
                    <option value="pending">Pending</option>
                    <option value="on_the_way">On the Way</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                </select>
                <button onclick="bulkStatusUpdate()" class="btn btn-primary-glass btn-sm">Apply</button>
                <button onclick="bulkInvoice()" class="btn btn-glass btn-sm">Print Invoices</button>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-center mt-3">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
function bulkStatusUpdate() {
    const status = document.getElementById('bulkStatus').value;
    if (!status) return alert('Please select a status');

    const ids = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (ids.length === 0) return alert('Please select at least one order');

    fetch("{{ route('admin.orders.bulkStatus') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order_ids: ids, status: status })
    }).then(() => window.location.reload());
}

function bulkInvoice() {
    const ids = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    if (ids.length === 0) return alert('Please select at least one order');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('admin.orders.bulkInvoice') }}";

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'order_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>
@endsection
