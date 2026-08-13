@extends('layouts.admin')

@section('title', 'Product Variants')

@section('content')
<a href="{{ route('admin.products.index') }}" class="btn btn-glass mb-3">← Back to Products</a>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Variants: {{ $product->name }}</h2>
    <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary-glass">+ Add Variant</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Category</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $variant)
                    <tr>
                        <td>{{ $variant->size->size_name ?? 'N/A' }}</td>
                        <td>{{ $variant->color->color_name ?? 'N/A' }}</td>
                        <td>{{ $variant->category->category_name ?? 'N/A' }}</td>
                        <td>{{ $variant->sku ?? 'N/A' }}</td>
                        <td>₹ {{ number_format($variant->price ?? $product->price, 2) }}</td>
                        <td>
                            <span class="badge {{ $variant->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $variant->stock_quantity }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $variant->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $variant->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.variants.edit', $variant) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.products.variants.destroy', $variant) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No variants found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
