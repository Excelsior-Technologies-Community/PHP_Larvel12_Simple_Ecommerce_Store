@extends('layouts.admin')

@section('title', 'CMS Pages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">CMS Pages</h2>
    <a href="{{ route('admin.cms-pages.create') }}" class="btn btn-primary-glass">+ Add Page</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td><strong>{{ $page->title }}</strong></td>
                        <td><code>{{ $page->slug }}</code></td>
                        <td>
                            <span class="badge {{ $page->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $page->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.cms-pages.edit', $page) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.cms-pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No pages found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $pages->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
