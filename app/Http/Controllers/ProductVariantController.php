<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->with(['size', 'color', 'category'])->get();
        return view('admin.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        return view('admin.variants.create', compact('product', 'sizes', 'colors', 'categories'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|unique:product_variants,sku',
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'category_id' => $request->category_id,
            'sku' => $request->sku,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'low_stock_threshold' => $request->low_stock_threshold,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.products.variants.index', $product)->with('success', 'Variant created successfully');
    }

    public function edit(ProductVariant $variant)
    {
        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        return view('admin.variants.edit', compact('variant', 'sizes', 'colors', 'categories'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|unique:product_variants,sku,' . $variant->id,
            'price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $variant->update($request->all());

        return redirect()->route('admin.products.variants.index', $variant->product_id)->with('success', 'Variant updated successfully');
    }

    public function destroy(ProductVariant $variant)
    {
        $productId = $variant->product_id;
        $variant->delete();

        return redirect()->route('admin.products.variants.index', $productId)->with('success', 'Variant deleted successfully');
    }
}
