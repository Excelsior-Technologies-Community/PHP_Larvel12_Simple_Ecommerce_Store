@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Edit Staff</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $staff->user->name) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $staff->user->email) }}" class="form-control glass-form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role_id" class="form-select glass-form-control" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $staff->user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control glass-form-control" rows="3">{{ old('address', $staff->address) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" value="{{ old('designation', $staff->designation) }}" class="form-control glass-form-control">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $staff->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Update Staff</button>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
