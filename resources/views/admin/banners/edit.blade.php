@extends('layouts.admin')

@section('title', 'Edit Banner')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Edit Banner</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control glass-form-control" rows="3">{{ old('description', $banner->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    @if($banner->image)
                        <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : asset('images/banners/'.$banner->image) }}" width="200" class="rounded mb-2" style="border-radius: 12px;">
                    @endif
                    <input type="file" name="image" class="form-control glass-form-control mt-2" onchange="previewImage(this)">
                    <img id="preview" class="mt-2 rounded d-none" width="200" style="border-radius: 12px;">
                </div>

                <div class="mb-3">
                    <label class="form-label">Link</label>
                    <input type="url" name="link" value="{{ old('link', $banner->link) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Link Text</label>
                    <input type="text" name="link_text" value="{{ old('link_text', $banner->link_text) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <select name="position" class="form-select glass-form-control">
                        <option value="home_top" {{ $banner->position == 'home_top' ? 'selected' : '' }}>Home Top</option>
                        <option value="home_bottom" {{ $banner->position == 'home_bottom' ? 'selected' : '' }}>Home Bottom</option>
                        <option value="category_top" {{ $banner->position == 'category_top' ? 'selected' : '' }}>Category Top</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d')) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', $banner->expires_at?->format('Y-m-d')) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Update Banner</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-glass">Back</a>
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
