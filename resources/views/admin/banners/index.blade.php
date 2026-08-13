@extends('layouts.admin')

@section('title', 'Manage Banners')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Banners</h2>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary-glass">+ Add Banner</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            @if($banner->image)
                                <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : asset('images/banners/'.$banner->image) }}" style="width: 80px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $banner->title }}</strong>
                            @if($banner->link)
                                <br><a href="{{ $banner->link }}" target="_blank" class="small">{{ $banner->link }}</a>
                            @endif
                        </td>
                        <td>{{ $banner->position }}</td>
                        <td>
                            <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this banner?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No banners found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $banners->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
