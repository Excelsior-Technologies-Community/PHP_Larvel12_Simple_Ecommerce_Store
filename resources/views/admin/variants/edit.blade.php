@extends('layouts.admin')

@section('title', 'Edit Variant')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Edit Variant</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.variants.update', $variant) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Size</label>
                    <select name="size_id" class="form-select glass-form-control">
                        <option value="">None</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ old('size_id', $variant->size_id) == $size->id ? 'selected' : '' }}>{{ $size->size_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Color</label>
                    <select name="color_id" class="form-select glass-form-control">
                        <option value="">None</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ old('color_id', $variant->color_id) == $color->id ? 'selected' : '' }}>{{ $color->color_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select glass-form-control">
                        <option value="">None</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $variant->category_id) == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" value="{{ old('price', $variant->price) }}" class="form-control glass-form-control" step="0.01">
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $variant->stock_quantity) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $variant->low_stock_threshold) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $variant->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Update Variant</button>
                    <a href="{{ route('admin.products.variants.index', $variant->product_id) }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
