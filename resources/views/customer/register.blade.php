@extends('layouts.customer')

@section('title', 'Customer Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="glass-card p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Create Account</h2>
                    <p class="text-muted">Join us for a great shopping experience</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-glass alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.register.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control glass-form-control" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control glass-form-control" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control glass-form-control" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control glass-form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary-glass w-100 mb-3">Register</button>
                </form>

                <div class="text-center">
                    <p class="small text-muted">
                        Already have an account? <a href="{{ route('customer.login') }}" class="text-decoration-none fw-bold">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
