@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Edit Product</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Details</label>
                    <textarea name="details" class="form-control glass-form-control" rows="3" required>{{ old('details', $product->details) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control glass-form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control glass-form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="form-control glass-form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="form-control glass-form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sizes</label>
                    <select name="sizes[]" class="form-control select2" multiple>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ in_array($size->id, old('sizes', $product->sizes ?? [])) ? 'selected' : '' }}>
                                {{ $size->size_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Colors</label>
                    <select name="colors[]" class="form-control select2" multiple>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ in_array($color->id, old('colors', $product->colors ?? [])) ? 'selected' : '' }}>
                                {{ $color->color_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Categories</label>
                    <select name="categories[]" class="form-control select2" multiple>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ in_array($cat->id, old('categories', $product->categories ?? [])) ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('images/'.$product->image) }}" width="120" class="rounded mb-2" style="border-radius: 12px;">
                    <input type="file" name="image" class="form-control glass-form-control mt-2" onchange="previewNewImage(this)">
                    <img id="newPreview" class="mt-2 rounded d-none" width="120" style="border-radius: 12px;">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Update Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewNewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = e => {
            document.getElementById('newPreview').src = e.target.result;
            document.getElementById('newPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
