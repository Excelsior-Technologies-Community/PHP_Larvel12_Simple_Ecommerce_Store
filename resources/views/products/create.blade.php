@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Add Product</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Details</label>
                    <textarea name="details" class="form-control glass-form-control" rows="3" required>{{ old('details') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" value="{{ old('price') }}" class="form-control glass-form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">SKU (Optional)</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="form-control glass-form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" class="form-control glass-form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" class="form-control glass-form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sizes</label>
                    <select name="sizes[]" class="form-control select2" multiple>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ collect(old('sizes'))->contains($size->id) ? 'selected' : '' }}>
                                {{ $size->size_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Colors</label>
                    <select name="colors[]" class="form-control select2" multiple>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ collect(old('colors'))->contains($color->id) ? 'selected' : '' }}>
                                {{ $color->color_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Categories</label>
                    <select name="categories[]" class="form-control select2" multiple>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ collect(old('categories'))->contains($cat->id) ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control glass-form-control" onchange="previewImage(this)" required>
                    <img id="preview" class="mt-2 rounded d-none" width="120" style="border-radius: 12px;">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Save Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('preview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
