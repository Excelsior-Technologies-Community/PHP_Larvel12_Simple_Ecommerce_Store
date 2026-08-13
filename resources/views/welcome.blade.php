@extends('layouts.customer')

@section('title', 'Home')

@section('content')
{{-- Hero Banner --}}
@if($banners->count() > 0)
    <div class="row mb-5">
        @foreach($banners->take(1) as $banner)
            <div class="col-12">
                <div class="glass-card p-0 overflow-hidden" style="border-radius: 20px;">
                    @if($banner->image)
                        <img src="{{ str_starts_with($banner->image, 'http') ? $banner->image : asset('images/banners/'.$banner->image) }}" alt="{{ $banner->title }}" style="width: 100%; height: 400px; object-fit: cover;">
                    @else
                        <div class="hero-section" style="border-radius: 0;">
                            <h1>{{ $banner->title }}</h1>
                            <p class="lead">{{ $banner->description }}</p>
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="btn btn-primary-glass">{{ $banner->link_text ?? 'Shop Now' }}</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Featured Products --}}
@if($featuredProducts->count() > 0)
    <div class="mb-5">
        <h2 class="fw-bold mb-4" style="color: #1a1a2e;">Featured Products</h2>
        <div class="row g-4">
            @foreach($featuredProducts as $featured)
                @if($featured->product && $featured->product->status == 'active')
                    <div class="col-md-3 col-sm-6">
                        <div class="product-card">
                            <img src="{{ str_starts_with($featured->product->image, 'http') ? $featured->product->image : asset('images/'.$featured->product->image) }}"
                                 class="product-image"
                                 alt="{{ $featured->product->name }}"
                                 onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                            <div class="product-body">
                                <h5 class="product-title">{{ $featured->product->name }}</h5>
                                <p class="product-price">₹ {{ number_format($featured->product->price, 2) }}</p>
                                @if($featured->label)
                                    <span class="badge bg-primary">{{ $featured->label }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif

{{-- All Products --}}
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: #1a1a2e;">All Products</h2>
        <a href="{{ route('customer.products') }}" class="btn btn-primary-glass">View All</a>
    </div>
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-md-3 col-sm-6">
                <div class="product-card">
                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('images/'.$product->image) }}"
                         class="product-image"
                         alt="{{ $product->name }}"
                         onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                    <div class="product-body">
                        <h5 class="product-title">{{ $product->name }}</h5>
                        <p class="product-price">₹ {{ number_format($product->price, 2) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
