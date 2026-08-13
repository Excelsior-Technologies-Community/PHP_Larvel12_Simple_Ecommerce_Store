@extends('layouts.admin')

@section('title', 'Featured Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Featured Products</h2>
    <a href="{{ route('admin.featured-products.create') }}" class="btn btn-primary-glass">+ Add Featured</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Label</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($featuredProducts as $featured)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ str_starts_with($featured->product->image, 'http') ? $featured->product->image : asset('images/'.$featured->product->image) }}"
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;"
                                     onerror="this.src='https://via.placeholder.com/40x40?text=N/A'">
                                <strong>{{ $featured->product->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $featured->type }}</td>
                        <td>{{ $featured->label ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $featured->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $featured->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.featured-products.edit', $featured) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.featured-products.destroy', $featured) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No featured products</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $featuredProducts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
