<?php

namespace App\Http\Controllers;

use App\Models\FeaturedProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class FeaturedProductController extends Controller
{
    public function index()
    {
        $featuredProducts = FeaturedProduct::with('product')->latest()->paginate(10);
        return view('admin.featured-products.index', compact('featuredProducts'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->get();
        return view('admin.featured-products.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:featured_products,product_id',
            'type' => 'required|string',
            'label' => 'nullable|string',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        FeaturedProduct::create($request->all());

        return redirect()->route('admin.featured-products.index')->with('success', 'Featured product added successfully');
    }

    public function edit(FeaturedProduct $featuredProduct)
    {
        $products = Product::where('status', 'active')->get();
        return view('admin.featured-products.edit', compact('featuredProduct', 'products'));
    }

    public function update(Request $request, FeaturedProduct $featuredProduct)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:featured_products,product_id,' . $featuredProduct->id,
            'type' => 'required|string',
            'label' => 'nullable|string',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $featuredProduct->update($request->all());

        return redirect()->route('admin.featured-products.index')->with('success', 'Featured product updated successfully');
    }

    public function destroy(FeaturedProduct $featuredProduct)
    {
        $featuredProduct->delete();
        return back()->with('success', 'Featured product removed successfully');
    }
}
