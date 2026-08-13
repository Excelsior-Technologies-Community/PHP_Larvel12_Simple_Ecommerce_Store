@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <div class="row g-0">
            <div class="col-md-5 auth-left d-flex flex-column justify-content-center">
                <div class="brand">Ecommerce Platform</div>
                <p class="mb-4">Create a new password for your account.</p>
                <ul>
                    <li>Secure password reset</li>
                    <li>Easy account recovery</li>
                    <li>Safe and protected</li>
                </ul>
            </div>
            <div class="col-md-7 auth-right">
                <h3>Reset Password</h3>
                <p class="mb-4">Enter your new password below</p>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('customer.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
