@extends('layouts.admin')

@section('title', 'Manage Sizes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Sizes</h2>
    <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary-glass">+ Add Size</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Size Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sizes as $key => $size)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><strong>{{ $size->size_name }}</strong></td>
                        <td>
                            <a href="{{ route('admin.sizes.edit', $size) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.sizes.destroy', $size) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
