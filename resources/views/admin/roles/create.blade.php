@extends('layouts.admin')

@section('title', 'Add Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Add Role</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Role Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permissions (JSON)</label>
                    <textarea name="permissions" class="form-control glass-form-control" rows="5" placeholder='{"products": true, "orders": true}'>{{ old('permissions') }}</textarea>
                    <small class="text-muted">Enter permissions as JSON object</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Save Role</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
