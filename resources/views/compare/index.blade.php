@extends('layouts.customer')

@section('title', 'Compare Products')

@section('content')
<div class="container">
    <h1 class="display-6 fw-bold mb-4" style="color: #1a1a2e;">Compare Products</h1>

    @if(session('success'))
        <div class="alert alert-glass alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table" style="background: rgba(255,255,255,0.75); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.5); border-radius: 20px; overflow: hidden;">
                <thead>
                    <tr>
                        <th style="width: 20%;">Feature</th>
                        @foreach($products as $product)
                            <th class="text-center">
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('images/'.$product->image) }}"
                                     alt="{{ $product->name }}"
                                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; margin-bottom: 10px;"
                                     onerror="this.src='https://via.placeholder.com/120x120?text=No+Image'">
                                <br>
                                <strong>{{ $product->name }}</strong>
                                <form action="{{ route('compare.remove', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                </form>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Price</strong></td>
                        @foreach($products as $product)
                            <td class="text-center fw-bold" style="color: #667eea; font-size: 18px;">
                                ₹ {{ number_format($product->price, 2) }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Rating</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">
                                @if($product->reviewCount() > 0)
                                    <span class="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($product->averageRating()))
                                                ★
                                            @else
                                                <span class="star-empty">★</span>
                                            @endif
                                        @endfor
                                    </span>
                                    <small>({{ $product->reviewCount() }})</small>
                                @else
                                    <span class="text-muted">No ratings</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Stock</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">
                                @if($product->isInStock())
                                    <span class="badge bg-success">In Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Details</strong></td>
                        @foreach($products as $product)
                            <td>{{ $product->details }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td><strong>Action</strong></td>
                        @foreach($products as $product)
                            <td class="text-center">
                                @auth('customer')
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-primary-glass btn-sm">Add to Cart</button>
                                    </form>
                                @else
                                    <a href="{{ route('customer.login') }}" class="btn btn-primary-glass btn-sm">Login to Buy</a>
                                @endauth
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-3">
            <form action="{{ route('compare.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Clear Compare List</button>
            </form>
        </div>
    @else
        <div class="glass-card p-5 text-center">
            <p style="font-size: 64px; margin-bottom: 16px;">⚖️</p>
            <h4 class="text-muted">No products to compare</h4>
            <a href="{{ route('customer.products') }}" class="btn btn-primary-glass mt-3">Browse Products</a>
        </div>
    @endif
</div>
@endsection
