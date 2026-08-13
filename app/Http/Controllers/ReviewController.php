<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Order $order)
    {
        $customerId = auth('customer')->id();

        if ($order->customer_id !== $customerId) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'You can only review delivered orders');
        }

        $products = $order->items->pluck('product')->filter();

        return view('reviews.create', compact('order', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $customerId = auth('customer')->id();
        $order = Order::findOrFail($request->order_id);

        if ($order->customer_id !== $customerId) {
            abort(403);
        }

        Review::create([
            'customer_id' => $customerId,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return redirect()->route('customer.orders')->with('success', 'Review submitted successfully');
    }

    public function edit(Review $review)
    {
        $customerId = auth('customer')->id();

        if ($review->customer_id !== $customerId) {
            abort(403);
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $customerId = auth('customer')->id();

        if ($review->customer_id !== $customerId) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return redirect()->route('customer.orders')->with('success', 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $customerId = auth('customer')->id();

        if ($review->customer_id !== $customerId) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully');
    }
}
