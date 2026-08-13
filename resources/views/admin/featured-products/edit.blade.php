@extends('layouts.admin')

@section('title', 'Edit Featured Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Edit Featured Product</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.featured-products.update', $featuredProduct) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select glass-form-control" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $featuredProduct->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select glass-form-control">
                        <option value="featured" {{ $featuredProduct->type == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="deal" {{ $featuredProduct->type == 'deal' ? 'selected' : '' }}>Deal</option>
                        <option value="new" {{ $featuredProduct->type == 'new' ? 'selected' : '' }}>New Arrival</option>
                        <option value="bestseller" {{ $featuredProduct->type == 'bestseller' ? 'selected' : '' }}>Bestseller</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Label</label>
                    <input type="text" name="label" value="{{ old('label', $featuredProduct->label) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control glass-form-control" rows="3">{{ old('description', $featuredProduct->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $featuredProduct->sort_order) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at', $featuredProduct->starts_at?->format('Y-m-d')) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', $featuredProduct->expires_at?->format('Y-m-d')) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $featuredProduct->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Update</button>
                    <a href="{{ route('admin.featured-products.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
