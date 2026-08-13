@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control glass-form-control" placeholder="Search products..." style="width: 300px;">
            <button type="submit" class="btn btn-primary-glass">Search</button>
        </form>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary-glass">+ Add Product</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('images/'.$product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px;"
                                 onerror="this.src='https://via.placeholder.com/50x50?text=N/A'">
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            <br><small class="text-muted">{{ $product->sku ?? 'No SKU' }}</small>
                        </td>
                        <td>₹ {{ number_format($product->price, 2) }}</td>
                        <td>
                            @if($product->variants()->exists())
                                <span class="badge badge-glass">{{ $product->variants->sum('stock_quantity') }} units</span>
                            @else
                                <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock_quantity }} units
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No products found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <a href="{{ route('admin.products.export') }}" class="btn btn-glass btn-sm">Export Excel</a>
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                @csrf
                <input type="file" name="file" class="form-control d-inline" style="width: auto;" required>
                <button type="submit" class="btn btn-sm btn-primary-glass">Import</button>
            </form>
        </div>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
