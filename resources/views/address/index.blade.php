@extends('layouts.customer')

@section('title', 'My Addresses')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold" style="color: #1a1a2e;">My Addresses</h1>
        <button type="button" class="btn btn-primary-glass" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            + Add Address
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-glass alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($addresses as $address)
            <div class="col-md-6">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="fw-bold mb-2">{{ $address->full_name }}</h5>
                            <p class="text-muted small mb-1">{{ $address->mobile }}</p>
                            <p class="small mb-1">{{ $address->address }}</p>
                            @if($address->nearby)
                                <p class="small text-muted mb-1">{{ $address->nearby }}</p>
                            @endif
                            <p class="small mb-1">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                            <div class="mt-2">
                                @if($address->is_default)
                                    <span class="badge bg-success">Default</span>
                                @endif
                                @if($address->type)
                                    <span class="badge badge-glass">{{ ucfirst($address->type) }}</span>
                                @endif
                                @if($address->label)
                                    <span class="badge badge-glass">{{ $address->label }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        @if(!$address->is_default)
                            <form action="{{ route('addresses.setDefault', $address) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-glass">Set Default</button>
                            </form>
                        @endif
                        <form action="{{ route('place.order') }}" method="POST" class="d-inline" onsubmit="return confirm('Place order with this address?')">
                            @csrf
                            <input type="hidden" name="address_id" value="{{ $address->id }}">
                            <button type="submit" class="btn btn-sm btn-primary-glass">Place Order</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-glass" data-bs-toggle="modal" data-bs-target="#editAddressModal{{ $address->id }}">
                            Edit
                        </button>
                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Delete this address?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="glass-card p-5 text-center">
                    <p style="font-size: 64px; margin-bottom: 16px;">📍</p>
                    <h4 class="text-muted">No addresses saved</h4>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Add Address Modal --}}
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(24px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.6);">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('addresses.store') }}" method="POST" id="addAddressForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control glass-form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control glass-form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control glass-form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nearby (Optional)</label>
                        <input type="text" name="nearby" class="form-control glass-form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control glass-form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control glass-form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" class="form-control glass-form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select glass-form-control">
                            <option value="shipping">Shipping</option>
                            <option value="billing">Billing</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Label (Optional)</label>
                        <input type="text" name="label" class="form-control glass-form-control" placeholder="e.g., Home, Work">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary-glass">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Address Modals --}}
@foreach($addresses as $address)
    <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(24px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.6);">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('addresses.update', $address) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" value="{{ $address->full_name }}" class="form-control glass-form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="text" name="mobile" value="{{ $address->mobile }}" class="form-control glass-form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control glass-form-control" rows="2" required>{{ $address->address }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nearby</label>
                            <input type="text" name="nearby" value="{{ $address->nearby }}" class="form-control glass-form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" value="{{ $address->city }}" class="form-control glass-form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" value="{{ $address->state }}" class="form-control glass-form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" value="{{ $address->pincode }}" class="form-control glass-form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select glass-form-control">
                                <option value="shipping" {{ $address->type == 'shipping' ? 'selected' : '' }}>Shipping</option>
                                <option value="billing" {{ $address->type == 'billing' ? 'selected' : '' }}>Billing</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" name="label" value="{{ $address->label }}" class="form-control glass-form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary-glass">Update Address</button>
                    </div>
                </form>
    </div>

    @if($addresses->count() > 0)
        <div class="mt-4">
        </div>
    @endif
</div>
    </div>
@endforeach
@endsection
