<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->get();
        return view('admin.stock.index', compact('products'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer',
            'type' => 'required|in:in,out,adjustment',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;

        if ($variant) {
            $previousStock = $variant->stock_quantity;
            $newStock = $previousStock + $request->quantity;
            if ($newStock < 0) $newStock = 0;

            $variant->update(['stock_quantity' => $newStock]);

            StockHistory::create([
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'notes' => $request->notes,
            ]);
        } else {
            $previousStock = $product->stock_quantity;
            $newStock = $previousStock + $request->quantity;
            if ($newStock < 0) $newStock = 0;

            $product->update(['stock_quantity' => $newStock]);

            StockHistory::create([
                'product_id' => $product->id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'notes' => $request->notes,
            ]);
        }

        return back()->with('success', 'Stock adjusted successfully');
    }

    public function history()
    {
        $histories = StockHistory::with(['product', 'variant'])->latest()->paginate(20);
        return view('admin.stock.history', compact('histories'));
    }
}
