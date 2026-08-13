@extends('layouts.admin')

@section('title', 'Edit CMS Page')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Edit CMS Page</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.cms-pages.update', $cmsPage) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title', $cmsPage->title) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $cmsPage->slug) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control glass-form-control" rows="10">{{ old('content', $cmsPage->content) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $cmsPage->meta_title) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control glass-form-control" rows="3">{{ old('meta_description', $cmsPage->meta_description) }}</textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $cmsPage->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Update Page</button>
                    <a href="{{ route('admin.cms-pages.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
