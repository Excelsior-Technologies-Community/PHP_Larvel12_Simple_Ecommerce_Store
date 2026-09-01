<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ActivityLogger;

    // ================================
    // LIST ACTIVE PRODUCTS
    // ================================

    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::where('status', 'active')
            ->inStock()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('details', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(5)
            ->withQueryString();

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        return view('products.index', compact(
            'products',
            'sizes',
            'colors',
            'categories',
            'search'
        ));
    }

    // ================================
    // CREATE
    // ================================

    public function create()
    {
        return view('products.create', [
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // ================================
    // STORE
    // ================================

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'image'      => 'required|image',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Make sure images directory exists
        |--------------------------------------------------------------------------
        */

        if (!file_exists(public_path('images'))) {
            mkdir(public_path('images'), 0755, true);
        }

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(
            public_path('images'),
            $imageName
        );

        $product = Product::create([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'image'      => $imageName,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
            'status'     => 'active',
        ]);

        $this->logActivity(
            'product_created',
            $product,
            "Product '{$product->name}' created"
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product added successfully');
    }

    // ================================
    // EDIT
    // ================================

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // ================================
    // UPDATE
    // ================================

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
            'image'      => 'nullable|image',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload New Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if (!file_exists(public_path('images'))) {
                mkdir(public_path('images'), 0755, true);
            }

            /*
            | Delete old image
            */

            if (
                $product->image &&
                file_exists(public_path('images/' . $product->image))
            ) {
                unlink(
                    public_path('images/' . $product->image)
                );
            }

            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(
                public_path('images'),
                $imageName
            );

            $product->image = $imageName;

            $product->save();
        }

        $oldPrice = $product->price;

        $product->update([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
        ]);

        $changes = [];

        if ($oldPrice != $request->price) {
            $changes['price'] =
                "{$oldPrice} -> {$request->price}";
        }

        $this->logActivity(
            'product_updated',
            $product,
            "Product '{$product->name}' updated",
            $changes
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    // ================================
    // DELETE
    // ================================

    public function destroy(Product $product)
    {
        $product->update([
            'status' => 'deleted'
        ]);

        $this->logActivity(
            'product_deleted',
            $product,
            "Product '{$product->name}' deleted"
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}