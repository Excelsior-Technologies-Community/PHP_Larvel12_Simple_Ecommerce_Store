@extends('layouts.admin')

@section('title', 'Manage Colors')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Colors</h2>
    <a href="{{ route('admin.colors.create') }}" class="btn btn-primary-glass">+ Add Color</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Color Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($colors as $key => $color)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><strong>{{ $color->color_name }}</strong></td>
                        <td>
                            <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.colors.destroy', $color) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
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
