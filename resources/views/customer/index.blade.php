@extends('layouts.customer')

@section('title', 'Products')

@section('content')
<div class="container">
    {{-- Page Header --}}
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold" style="color: #1a1a2e;">Our Products</h1>
        <p class="text-muted">Discover amazing products at great prices</p>
    </div>

    {{-- Search Bar --}}
    <div class="glass-card p-4 mb-4">
        <form method="GET" action="{{ route('customer.products') }}">
            <div class="input-group">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control glass-form-control"
                       placeholder="Search by name, price, size, color, category...">
                <button class="btn btn-primary-glass" type="submit">Search</button>
                <a href="{{ route('customer.products') }}" class="btn btn-glass">Reset</a>
            </div>
        </form>
    </div>

    {{-- Flash Messages --}}
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

    {{-- Product Grid --}}
    @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="product-card">
                        {{-- Product Image --}}
                        <div style="position: relative;">
<img
    src="{{ filter_var($product->image, FILTER_VALIDATE_URL)
        ? $product->image
        : asset('images/' . $product->image) }}"
    class="product-image"
    alt="{{ $product->name }}"
    onerror="this.onerror=null; this.src='https://placehold.co/300x300?text=No+Image';"
>

                            {{-- Wishlist Button --}}
                            @auth('customer')
                                @php
                                    $isWishlisted = \App\Models\Wishlist::where('customer_id', auth('customer')->id())
                                        ->where('product_id', $product->id)
                                        ->exists();
                                @endphp
                                <form action="{{ $isWishlisted ? route('wishlist.remove', $product) : route('wishlist.add', $product) }}"
                                      method="POST"
                                      style="position: absolute; top: 10px; right: 10px;">
                                    @csrf
                                    @if($isWishlisted)
                                        @method('DELETE')
                                    @endif
                                    <button type="submit" class="btn btn-sm" style="background: rgba(255,255,255,0.9); border-radius: 50%; width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                        {{ $isWishlisted ? '💖' : '🤍' }}
                                    </button>
                                </form>
                            @endauth

                            {{-- Out of Stock Badge --}}
                            @if(!$product->isInStock())
                                <div style="position: absolute; top: 10px; left: 10px;">
                                    <span class="badge bg-danger">Out of Stock</span>
                                </div>
                            @elseif($product->isLowStock())
                                <div style="position: absolute; top: 10px; left: 10px;">
                                    <span class="badge bg-warning">Low Stock</span>
                                </div>
                            @endif
                        </div>

                        <div class="product-body">
                            {{-- Product Title --}}
                            <h5 class="product-title">{{ $product->name }}</h5>

                            {{-- Product Details --}}
                            <p class="text-muted small mb-2" style="line-height: 1.5;">
                                {{ \Illuminate\Support\Str::limit($product->details, 60) }}
                            </p>

                            {{-- Rating --}}
                            @if($product->reviewCount() > 0)
                                <div class="mb-2">
                                    <span class="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($product->averageRating()))
                                                ★
                                            @else
                                                <span class="star-empty">★</span>
                                            @endif
                                        @endfor
                                    </span>
                                    <span class="text-muted small">({{ $product->reviewCount() }})</span>
                                </div>
                            @endif

                            {{-- Price --}}
                            <p class="product-price mb-3">₹ {{ number_format($product->price, 2) }}</p>

                            {{-- Actions --}}
                            <div class="d-flex gap-2 mb-2">
                                @auth('customer')
                                    @if($product->isInStock())
                                        <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            @if($product->variants()->exists())
                                                <div class="mb-2">
                                                    <select name="size_id" class="form-select form-select-sm glass-form-control" required>
                                                        <option value="">Size</option>
                                                        @foreach($product->variants as $variant)
                                                            @if($variant->size_id)
                                                                <option value="{{ $variant->size_id }}">{{ $variant->size->size_name ?? '' }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="color_id" class="form-select form-select-sm glass-form-control" required>
                                                        <option value="">Color</option>
                                                        @foreach($product->variants as $variant)
                                                            @if($variant->color_id)
                                                                <option value="{{ $variant->color_id }}">{{ $variant->color->color_name ?? '' }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <select name="category_id" class="form-select form-select-sm glass-form-control" required>
                                                        <option value="">Category</option>
                                                        @foreach($product->variants as $variant)
                                                            @if($variant->category_id)
                                                                <option value="{{ $variant->category_id }}">{{ $variant->category->category_name ?? '' }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="mb-2">
                                                <select name="quantity" class="form-select form-select-sm glass-form-control">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">Qty: {{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary-glass w-100 btn-sm">
                                                Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100 btn-sm" disabled>Out of Stock</button>
                                    @endif
                                @else
                                    <a href="{{ route('customer.login') }}?redirect={{ url()->current() }}"
                                       class="btn btn-primary-glass w-100 btn-sm">
                                        Login to Buy
                                    </a>
                                @endauth
                            </div>

                            {{-- Compare Button --}}
                            @auth('customer')
                                @if(in_array($product->id, $compareIds ?? []))
                                    <form action="{{ route('compare.remove', $product) }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-glass btn-sm w-100">Remove from Compare</button>
                                    </form>
                                @else
                                    <form action="{{ route('compare.add', $product) }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-glass btn-sm w-100">Compare</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="glass-card p-5 text-center">
            <p style="font-size: 48px; margin-bottom: 16px;">📦</p>
            <h4 class="text-muted">No products found</h4>
            <p class="text-muted">Try adjusting your search criteria</p>
        </div>
    @endif
</div>
@endsection
