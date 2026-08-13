@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Role Details</h2>

            <div class="mb-3">
                <label class="form-label fw-bold">Name</label>
                <p class="form-control-plaintext">{{ $role->name }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Slug</label>
                <p class="form-control-plaintext"><code>{{ $role->slug }}</code></p>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Permissions</label>
                @if($role->permissions && is_array($role->permissions))
                    @if(isset($role->permissions['*']))
                        <span class="badge bg-success">All Permissions</span>
                    @else
                        <div class="mt-2">
                            @foreach($role->permissions as $key => $value)
                                @if($value)
                                    <span class="badge badge-glass me-1 mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="text-muted">No permissions assigned</p>
                @endif
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary-glass">Edit Role</a>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-glass">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection