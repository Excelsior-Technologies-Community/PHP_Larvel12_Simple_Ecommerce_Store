<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 🛒 Cart List (ONLY LOGGED-IN CUSTOMER)
    public function index()
    {
        $customerId = auth('customer')->id();

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        return view('cart.index', compact(
            'cartItems',
            'sizes',
            'colors',
            'categories'
        ));
    }

    // ➕ Add to Cart (SAVE customer_id)
   public function store(Request $request)
{
    $request->validate([
        'product_id'  => 'required|exists:products,id',
        'size_id'     => 'required',
        'color_id'    => 'required',
        'category_id' => 'required',
        'quantity'    => 'required|integer|min:1|max:5',
    ]);

    $customerId = auth('customer')->id();

    $product = Product::where('status', 'active')->findOrFail($request->product_id);

    if (!$product->isInStock()) {
        return back()->withErrors(['product_id' => 'This product is out of stock']);
    }

    $variant = ProductVariant::where('product_id', $product->id)
        ->where('size_id', $request->size_id)
        ->where('color_id', $request->color_id)
        ->where('category_id', $request->category_id)
        ->where('is_active', true)
        ->first();

    if ($variant && $variant->stock_quantity < $request->quantity) {
        return back()->withErrors(['product_id' => 'Not enough stock available']);
    }

    if ($product->variants()->exists() && !$variant) {
        return back()->withErrors(['product_id' => 'This variant is not available']);
    }

    $price = $product->getEffectivePrice(
        Size::find($request->size_id),
        Color::find($request->color_id),
        Category::find($request->category_id)
    );

    // 🔍 Check if same item already exists
    $cartItem = Cart::where('customer_id', $customerId)
        ->where('product_id', $product->id)
        ->where('size_id', $request->size_id)
        ->where('color_id', $request->color_id)
        ->first();

    if ($cartItem) {
        // ➕ increase quantity (max 5)
        $newQuantity = $cartItem->quantity + $request->quantity;

        if ($variant && $variant->stock_quantity < $newQuantity) {
            return back()->withErrors(['product_id' => 'Not enough stock available']);
        }

        $cartItem->quantity = min($newQuantity, 5);
        $cartItem->save();
    } else {
        Cart::create([
            'customer_id' => $customerId,
            'product_id'  => $product->id,
            'size_id'     => $request->size_id,
            'color_id'    => $request->color_id,
            'category_id' => $request->category_id,
            'quantity'    => $request->quantity,
            'price'       => $price,
        ]);
    }

    return redirect()->route('cart.index')
        ->with('success', 'Product added to cart successfully');
}

    // ❌ Remove (ONLY OWN CART ITEM)
    public function destroy(Cart $cart)
    {
        // 🔐 Security check
        if ($cart->customer_id !== auth('customer')->id()) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Item removed from cart');
    }
    // 🔄 UPDATE QUANTITY (+ / -)
public function updateQuantity(Request $request, Cart $cart)
{
    // 🔐 Security
    if ($cart->customer_id !== auth('customer')->id()) {
        abort(403);
    }

    $request->validate([
        'action' => 'required|in:increase,decrease',
    ]);

    $product = $cart->product;
    $variant = ProductVariant::where('product_id', $product->id)
        ->where('size_id', $cart->size_id)
        ->where('color_id', $cart->color_id)
        ->where('category_id', $cart->category_id)
        ->first();

    if ($request->action === 'increase' && $cart->quantity < 5) {
        $newQuantity = $cart->quantity + 1;

        if ($variant && $variant->stock_quantity < $newQuantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $cart->quantity = $newQuantity;
    }

    if ($request->action === 'decrease' && $cart->quantity > 1) {
        $cart->quantity -= 1;
    }

    $cart->save();

    return redirect()->route('cart.index');
}

}
