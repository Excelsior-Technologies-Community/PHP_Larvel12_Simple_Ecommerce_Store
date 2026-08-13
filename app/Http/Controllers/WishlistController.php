<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('customer_id', auth('customer')->id())
            ->with('product')
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    public function add(Product $product)
    {
        $customerId = auth('customer')->id();

        Wishlist::firstOrCreate([
            'customer_id' => $customerId,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Added to wishlist');
    }

    public function remove(Product $product)
    {
        $customerId = auth('customer')->id();

        Wishlist::where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Removed from wishlist');
    }
}
