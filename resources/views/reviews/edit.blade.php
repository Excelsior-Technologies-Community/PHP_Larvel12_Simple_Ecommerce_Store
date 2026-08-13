@extends('layouts.customer')

@section('title', 'Edit Review')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="glass-card p-5">
                <h2 class="fw-bold mb-4">Edit Review</h2>

                <form action="{{ route('reviews.update', $review) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="star-rating" style="font-size: 32px; cursor: pointer;">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}" data-value="{{ $i }}">★</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" value="{{ $review->rating }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea name="comment" class="form-control glass-form-control" rows="4">{{ old('comment', $review->comment) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-glass">Update Review</button>
                        <a href="{{ route('customer.orders') }}" class="btn btn-glass">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.dataset.value;
        document.querySelector('input[name="rating"]').value = value;

        document.querySelectorAll('.star').forEach((s, index) => {
            if (index < value) {
                s.classList.remove('text-secondary');
                s.classList.add('text-warning');
            } else {
                s.classList.remove('text-warning');
                s.classList.add('text-secondary');
            }
        });
    });
});
</script>
@endsection
