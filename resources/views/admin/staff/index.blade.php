@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Staff</h2>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary-glass">+ Add Staff</a>
</div>

<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Phone</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $member)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $member->user->profile_image ? asset('images/'.$member->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($member->user->name) . '&background=667eea&color=fff' }}"
                                     style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                                <strong>{{ $member->user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $member->phone ?? '-' }}</td>
                        <td>{{ $member->designation ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $member->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-sm btn-glass">Edit</a>
                            <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No staff found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $staff->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
