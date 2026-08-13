@extends('layouts.admin')

@section('title', 'Staff Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Staff Details</h2>

            <div class="text-center mb-4">
                <img src="{{ $staff->user->profile_image ? asset('images/'.$staff->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($staff->user->name) . '&background=667eea&color=fff' }}"
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid rgba(255,255,255,0.6); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <h4 class="fw-bold mt-3">{{ $staff->user->name }}</h4>
                <p class="text-muted">{{ $staff->user->email }}</p>
                <span class="badge {{ $staff->user->role->slug === 'super_admin' ? 'bg-danger' : 'bg-primary' }}">
                    {{ $staff->user->role->name ?? 'No Role' }}
                </span>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Phone</label>
                <p class="form-control-plaintext">{{ $staff->phone ?? '-' }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Address</label>
                <p class="form-control-plaintext">{{ $staff->address ?? '-' }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Designation</label>
                <p class="form-control-plaintext">{{ $staff->designation ?? '-' }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Status</label>
                <span class="badge {{ $staff->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $staff->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-primary-glass">Edit Staff</a>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-glass">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection