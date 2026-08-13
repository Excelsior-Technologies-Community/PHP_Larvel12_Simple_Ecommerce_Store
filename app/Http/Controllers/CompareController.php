<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompareController extends Controller
{
    public function index()
    {
        $compareIds = Session::get('compare_ids', []);
        $products = Product::whereIn('id', $compareIds)->get();

        return view('compare.index', compact('products'));
    }

    public function add(Product $product)
    {
        $compareIds = Session::get('compare_ids', []);

        if (!in_array($product->id, $compareIds)) {
            if (count($compareIds) >= 4) {
                return back()->with('error', 'You can compare up to 4 products only');
            }

            $compareIds[] = $product->id;
            Session::put('compare_ids', $compareIds);
        }

        return back()->with('success', 'Product added to compare');
    }

    public function remove(Product $product)
    {
        $compareIds = Session::get('compare_ids', []);
        $compareIds = array_diff($compareIds, [$product->id]);
        Session::put('compare_ids', $compareIds);

        return back()->with('success', 'Product removed from compare');
    }

    public function clear()
    {
        Session::forget('compare_ids');
        return back()->with('success', 'Compare list cleared');
    }
}
