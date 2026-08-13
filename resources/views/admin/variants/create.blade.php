@extends('layouts.admin')

@section('title', 'Add Variant')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Add Variant - {{ $product->name }}</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Size</label>
                    <select name="size_id" class="form-select glass-form-control">
                        <option value="">None</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>{{ $size->size_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Color</label>
                    <select name="color_id" class="form-select glass-form-control">
                        <option value="">None</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>{{ $color->color_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select glass-form-control">
                        <option value="">None</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">SKU (Optional)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (Optional - leave empty for product price)</label>
                    <input type="number" name="price" value="{{ old('price') }}" class="form-control glass-form-control" step="0.01">
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Save Variant</button>
                    <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
