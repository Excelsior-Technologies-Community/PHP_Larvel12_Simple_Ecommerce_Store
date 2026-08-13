@extends('layouts.customer')

@section('title', 'My Wishlist')

@section('content')
<div class="container">
    <h1 class="display-6 fw-bold mb-4" style="color: #1a1a2e;">My Wishlist</h1>

    @if(session('success'))
        <div class="alert alert-glass alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($wishlists->count() > 0)
        <div class="row g-4">
            @foreach($wishlists as $wishlist)
                <div class="col-md-4 col-sm-6">
                    <div class="product-card">
                        <img src="{{ str_starts_with($wishlist->product->image, 'http') ? $wishlist->product->image : asset('images/'.$wishlist->product->image) }}"
                             class="product-image"
                             alt="{{ $wishlist->product->name }}"
                             onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">

                        <div class="product-body">
                            <h5 class="product-title">{{ $wishlist->product->name }}</h5>
                            <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($wishlist->product->details, 60) }}</p>
                            <p class="product-price mb-3">₹ {{ number_format($wishlist->product->price, 2) }}</p>

                            <div class="d-flex gap-2">
                                <form action="{{ route('wishlist.remove', $wishlist->product) }}" method="POST" class="w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100 btn-sm">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $wishlists->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="glass-card p-5 text-center">
            <p style="font-size: 64px; margin-bottom: 16px;">💖</p>
            <h4 class="text-muted">Your wishlist is empty</h4>
            <a href="{{ route('customer.products') }}" class="btn btn-primary-glass mt-3">Explore Products</a>
        </div>
    @endif
</div>
@endsection
