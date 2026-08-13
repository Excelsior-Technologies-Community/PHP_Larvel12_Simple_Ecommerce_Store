@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <div class="row g-0">
            <div class="col-md-5 auth-left d-flex flex-column justify-content-center">
                <div class="brand">Ecommerce Platform</div>
                <p class="mb-4">Reset your password to regain access to your account.</p>
                <ul>
                    <li>Secure password recovery</li>
                    <li>Quick and easy process</li>
                    <li>24/7 support available</li>
                </ul>
            </div>
            <div class="col-md-7 auth-right">
                <h3>Forgot Password?</h3>
                <p class="mb-4">Enter your email and we'll send you a reset link</p>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                </form>

                <p class="small-link mt-3 text-center">
                    Remember your password? <a href="{{ route('customer.login') }}">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
