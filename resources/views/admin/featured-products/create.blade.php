@extends('layouts.admin')

@section('title', 'Add Featured Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Add Featured Product</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.featured-products.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select glass-form-control" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select glass-form-control">
                        <option value="featured" {{ old('type', 'featured') == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="deal" {{ old('type') == 'deal' ? 'selected' : '' }}>Deal</option>
                        <option value="new" {{ old('type') == 'new' ? 'selected' : '' }}>New Arrival</option>
                        <option value="bestseller" {{ old('type') == 'bestseller' ? 'selected' : '' }}>Bestseller</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Label (Optional)</label>
                    <input type="text" name="label" value="{{ old('label') }}" class="form-control glass-form-control" placeholder="e.g., Hot Deal">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control glass-form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Start Date (Optional)</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">End Date (Optional)</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Save</button>
                    <a href="{{ route('admin.featured-products.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
