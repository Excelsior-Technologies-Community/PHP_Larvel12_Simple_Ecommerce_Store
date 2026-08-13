@extends('layouts.customer')

@section('title', 'Customer Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="glass-card p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Welcome Back</h2>
                    <p class="text-muted">Login to your account to continue shopping</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-glass alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control glass-form-control" required autofocus>
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

                    <button type="submit" class="btn btn-primary-glass w-100 mb-3">Login</button>
                </form>

                <div class="text-center">
                    <p class="small text-muted mb-2">
                        <a href="{{ route('customer.password.request') }}" class="text-decoration-none">Forgot password?</a>
                    </p>
                    <p class="small text-muted">
                        Don't have an account? <a href="{{ route('customer.register') }}" class="text-decoration-none fw-bold">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
