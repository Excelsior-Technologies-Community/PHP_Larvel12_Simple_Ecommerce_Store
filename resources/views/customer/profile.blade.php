@extends('layouts.customer')

@section('title', 'My Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="glass-card p-5">
                <h2 class="fw-bold mb-4">My Profile</h2>

                @if(session('success'))
                    <div class="alert alert-glass alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        <img src="{{ $customer->profile_image ? asset('images/'.$customer->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&background=667eea&color=fff' }}"
                             alt="Profile"
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid rgba(255,255,255,0.6); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <div class="mt-3">
                            <input type="file" name="profile_image" class="form-control glass-form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="form-control glass-form-control" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" value="{{ $customer->email }}" class="form-control glass-form-control" disabled>
                    </div>

                    <button type="submit" class="btn btn-primary-glass">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
