@extends('layouts.customer')

@section('title', 'Write a Review')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="glass-card p-5">
                <h2 class="fw-bold mb-4">Write a Review</h2>

                @if(session('error'))
                    <div class="alert alert-glass alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="mb-4">
                    <h5 class="fw-bold">Products in this order:</h5>
                    @foreach($products as $product)
                        <div class="d-flex align-items-center gap-3 py-2">
                            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('images/'.$product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 10px;"
                                 onerror="this.src='https://via.placeholder.com/50x50?text=N/A'">
                            <span class="fw-bold">{{ $product->name }}</span>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select name="product_id" class="form-select glass-form-control" required>
                            <option value="">Choose a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="star-rating" style="font-size: 32px; cursor: pointer;">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star" data-value="{{ $i }}" style="color: #dee2e6; transition: color 0.2s;">★</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment (Optional)</label>
                        <textarea name="comment" class="form-control glass-form-control" rows="4" placeholder="Share your experience..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary-glass">Submit Review</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.dataset.value;
        document.getElementById('ratingInput').value = value;

        document.querySelectorAll('.star').forEach((s, index) => {
            if (index < value) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#dee2e6';
            }
        });
    });
});
</script>
@endsection
