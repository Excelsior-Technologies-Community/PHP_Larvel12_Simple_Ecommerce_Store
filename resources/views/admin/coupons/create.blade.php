@extends('layouts.admin')

@section('title', 'Add Coupon')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="glass-card p-5">
            <h2 class="fw-bold mb-4">Add Coupon</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Coupon Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="form-control glass-form-control" required style="text-transform: uppercase;">
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select glass-form-control">
                        <option value="percentage" {{ old('type', 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Value</label>
                    <input type="number" name="value" value="{{ old('value') }}" class="form-control glass-form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Minimum Order Amount (Optional)</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}" class="form-control glass-form-control" step="0.01">
                </div>

                <div class="mb-3">
                    <label class="form-label">Max Discount Amount (Optional)</label>
                    <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount') }}" class="form-control glass-form-control" step="0.01">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Usage Limit (Optional)</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="form-control glass-form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Usage Limit Per User (Optional)</label>
                        <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" class="form-control glass-form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Starts At (Optional)</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="form-control glass-form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expires At (Optional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="form-control glass-form-control">
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-glass">Save Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-glass">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
