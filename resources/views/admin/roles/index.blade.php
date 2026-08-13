@extends('layouts.admin')

@section('title', 'Manage Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Roles</h2>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary-glass">+ Add Role</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td><strong>{{ $role->name }}</strong></td>
                        <td><code>{{ $role->slug }}</code></td>
                        <td>
                            @if($role->permissions)
                                @if(is_array($role->permissions) && isset($role->permissions['*']))
                                    <span class="badge bg-success">All Permissions</span>
                                @else
                                    <span class="badge badge-glass">{{ count(is_array($role->permissions) ? $role->permissions : []) }} permissions</span>
                                @endif
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No roles found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $roles->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
