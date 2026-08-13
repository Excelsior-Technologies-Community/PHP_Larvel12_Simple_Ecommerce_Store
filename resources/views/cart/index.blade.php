@extends('layouts.customer')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <h1 class="display-6 fw-bold mb-4" style="color: #1a1a2e;">Shopping Cart</h1>

    @if(session('success'))
        <div class="alert alert-glass alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-glass alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                @foreach($cartItems as $item)
                    <div class="glass-card p-4 mb-3">
                        <div class="d-flex gap-3">
                            {{-- Product Image --}}
                            <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('images/'.$item->product->image) }}"
                                 alt="{{ $item->product->name }}"
                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px;"
                                 onerror="this.src='https://via.placeholder.com/100x100?text=No+Image'">

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="fw-bold mb-1" style="color: #1a1a2e;">{{ $item->product->name }}</h5>
                                        <p class="text-muted small mb-2">
                                            @if($item->size_id)
                                                Size: {{ $sizes[$item->size_id] ?? 'N/A' }}
                                            @endif
                                            @if($item->color_id)
                                                &nbsp;|&nbsp;Color: {{ $colors[$item->color_id] ?? 'N/A' }}
                                            @endif
                                            @if($item->category_id)
                                                &nbsp;|&nbsp;Category: {{ $categories[$item->category_id] ?? 'N/A' }}
                                            @endif
                                        </p>
                                        <p class="fw-bold mb-0" style="color: #667eea; font-size: 18px;">
                                            ₹ {{ number_format($item->price, 2) }}
                                        </p>
                                    </div>

                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-danger" style="background: rgba(255,255,255,0.7); border-radius: 10px;">
                                            ✕
                                        </button>
                                    </form>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="{{ route('cart.update.quantity', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn btn-sm btn-glass" {{ $item->quantity <= 1 ? 'disabled' : '' }}>−</button>
                                        </form>

                                        <span class="fw-bold px-3">{{ $item->quantity }}</span>

                                        <form action="{{ route('cart.update.quantity', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn btn-sm btn-glass" {{ $item->quantity >= 5 ? 'disabled' : '' }}>+</button>
                                        </form>
                                    </div>

                                    <p class="fw-bold mb-0" style="color: #1a1a2e;">
                                        Subtotal: ₹ {{ number_format($item->price * $item->quantity, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="glass-card p-4" style="position: sticky; top: 100px;">
                    <h4 class="fw-bold mb-3">Cart Summary</h4>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Items ({{ $cartItems->sum('quantity') }})</span>
                        <span class="fw-bold">₹ {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                    </div>

                    <hr style="border-color: rgba(255,255,255,0.3);">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold" style="color: #667eea; font-size: 20px;">
                            ₹ {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity), 2) }}
                        </span>
                    </div>

                    <a href="{{ route('addresses.index') }}" class="btn btn-primary-glass w-100">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="glass-card p-5 text-center">
            <p style="font-size: 64px; margin-bottom: 16px;">🛒</p>
            <h4 class="text-muted mb-3">Your cart is empty</h4>
            <a href="{{ route('customer.products') }}" class="btn btn-primary-glass">
                Continue Shopping
            </a>
        </div>
    @endif
</div>
@endsection
