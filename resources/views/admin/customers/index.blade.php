@extends('layouts.admin')

@section('title', 'Manage Customers')

@section('content')
<div class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control glass-form-control" placeholder="Search customers..." style="width: 300px;">
            <button type="submit" class="btn btn-primary-glass">Search</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Orders</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $customer->profile_image ? asset('images/'.$customer->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&background=667eea&color=fff' }}"
                                     style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                <strong>{{ $customer->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->orders_count }}</td>
                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-glass">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No customers found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
