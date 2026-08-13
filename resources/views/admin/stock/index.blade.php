@extends('layouts.admin')

@section('title', 'Stock Management')

@section('content')
<div class="glass-card p-4 mb-4">
    <h4 class="fw-bold mb-3">Adjust Stock</h4>
    <form action="{{ route('admin.stock.adjust') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <select name="product_id" class="form-select glass-form-control" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (SKU: {{ $product->sku ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="quantity" class="form-control glass-form-control" placeholder="Quantity (+/-)" required>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select glass-form-control">
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                    <option value="adjustment">Adjustment</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="notes" class="form-control glass-form-control" placeholder="Notes (optional)">
            </div>
        </div>
        <button type="submit" class="btn btn-primary-glass mt-3">Adjust Stock</button>
    </form>
</div>

<div class="glass-card p-4">
    <h4 class="fw-bold mb-3">Product Stock Levels</h4>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Stock</th>
                    <th>Threshold</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('images/'.$product->image) }}"
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;"
                                     onerror="this.src='https://via.placeholder.com/40x40?text=N/A'">
                                <strong>{{ $product->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td>
                            @if($product->variants()->exists())
                                <span class="badge badge-glass">{{ $product->variants->sum('stock_quantity') }} units</span>
                            @else
                                <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $product->low_stock_threshold }}</td>
                        <td>
                            @if($product->isInStock())
                                @if($product->isLowStock())
                                    <span class="badge bg-warning">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No products found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="{{ route('admin.stock.history') }}" class="btn btn-glass">View Stock History</a>
    </div>
</div>
@endsection
