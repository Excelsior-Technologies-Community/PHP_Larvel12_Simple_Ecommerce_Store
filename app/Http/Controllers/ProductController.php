<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ActivityLogger;

    // =========================================================
    // LIST PRODUCTS
    // =========================================================

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $stock = $request->stock;
        $sort = $request->sort ?? 'newest';

        /*
        |--------------------------------------------------------------------------
        | Product Query
        |--------------------------------------------------------------------------
        */

        $query = Product::query()
            ->where('status', '!=', 'deleted')
            ->with('variants');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search) {
            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */

        if ($stock === 'in_stock') {

            $query->inStock();
        } elseif ($stock === 'out_of_stock') {

            $query->where(function ($q) {

                $q->where(function ($q2) {
                    $q2->where('track_stock', true)
                        ->where('allow_backorder', false)
                        ->whereDoesntHave('variants')
                        ->where('stock_quantity', '<=', 0);
                })

                    ->orWhere(function ($q2) {
                        $q2->where('track_stock', true)
                            ->where('allow_backorder', false)
                            ->whereHas('variants')
                            ->whereDoesntHave('activeVariants', function ($variant) {
                                $variant->where('stock_quantity', '>', 0);
                            });
                    });
            });
        } elseif ($stock === 'low_stock') {

            $query->where(function ($q) {

                $q->where(function ($q2) {

                    $q2->where('track_stock', true)
                        ->whereDoesntHave('variants')
                        ->whereColumn(
                            'stock_quantity',
                            '<=',
                            'low_stock_threshold'
                        )
                        ->where('stock_quantity', '>', 0);
                })

                    ->orWhereHas('activeVariants', function ($variant) {

                        $variant->whereColumn(
                            'stock_quantity',
                            '<=',
                            'low_stock_threshold'
                        )
                            ->where('stock_quantity', '>', 0);
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Price Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }

        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch ($sort) {

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'stock_asc':
                $query->orderBy('stock_quantity', 'asc');
                break;

            case 'stock_desc':
                $query->orderBy('stock_quantity', 'desc');
                break;

            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dropdown Data
        |--------------------------------------------------------------------------
        */

        $sizes = Size::pluck('size_name', 'id');

        $colors = Color::pluck('color_name', 'id');

        $categories = Category::pluck(
            'category_name',
            'id'
        );

        /*
        |--------------------------------------------------------------------------
        | Product Statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::where(
            'status',
            '!=',
            'deleted'
        )->count();

        $activeProducts = Product::where(
            'status',
            'active'
        )->count();

        $inactiveProducts = Product::where(
            'status',
            'inactive'
        )->count();

        $outOfStockProducts = Product::where(
            'status',
            '!=',
            'deleted'
        )
            ->where('track_stock', true)
            ->where('allow_backorder', false)
            ->whereDoesntHave('variants')
            ->where('stock_quantity', '<=', 0)
            ->count();

        $lowStockProducts = Product::where(
            'status',
            '!=',
            'deleted'
        )
            ->where('track_stock', true)
            ->whereDoesntHave('variants')
            ->whereColumn(
                'stock_quantity',
                '<=',
                'low_stock_threshold'
            )
            ->where('stock_quantity', '>', 0)
            ->count();

        return view('products.index', compact(
            'products',
            'sizes',
            'colors',
            'categories',
            'search',
            'status',
            'stock',
            'sort',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'outOfStockProducts',
            'lowStockProducts'
        ));
    }

    // =========================================================
    // CREATE
    // =========================================================

    public function create()
    {
        return view('products.create', [
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // =========================================================
    // STORE
    // =========================================================

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

        if (!file_exists(public_path('images'))) {
            mkdir(public_path('images'), 0755, true);
        }

        $imageName = time() . '.' .
            $request->image->extension();

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
            ->with(
                'success',
                'Product added successfully'
            );
    }

    // =========================================================
    // EDIT
    // =========================================================

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'categories' => Category::all()
        ]);
    }

    // =========================================================
    // UPDATE
    // =========================================================

    public function update(
        Request $request,
        Product $product
    ) {
        $request->validate([
            'name'       => 'required',
            'details'    => 'required',
            'price'      => 'required|numeric',
            'sizes'      => 'required|array',
            'colors'     => 'required|array',
            'categories' => 'required|array',
            'image'      => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {

            if (!file_exists(public_path('images'))) {
                mkdir(public_path('images'), 0755, true);
            }

            if (
                $product->image &&
                file_exists(
                    public_path(
                        'images/' . $product->image
                    )
                )
            ) {
                unlink(
                    public_path(
                        'images/' . $product->image
                    )
                );
            }

            $imageName = time() . '.' .
                $request->image->extension();

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
            ->with(
                'success',
                'Product updated successfully'
            );
    }

    // =========================================================
    // DELETE
    // =========================================================

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
            ->with(
                'success',
                'Product deleted successfully'
            );
    }

    // =========================================================
    // 4. TOGGLE STATUS
    // =========================================================

    public function toggleStatus(Product $product)
    {
        if ($product->status === 'active') {

            $product->update([
                'status' => 'inactive'
            ]);

            $message = 'Product deactivated successfully';

            $action = 'product_deactivated';
        } else {

            $product->update([
                'status' => 'active'
            ]);

            $message = 'Product activated successfully';

            $action = 'product_activated';
        }

        $this->logActivity(
            $action,
            $product,
            "Product '{$product->name}' status changed"
        );

        return redirect()
            ->back()
            ->with('success', $message);
    }

    // =========================================================
    // 3. BULK DELETE
    // =========================================================

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $products = Product::whereIn(
            'id',
            $request->product_ids
        )->get();

        foreach ($products as $product) {

            $product->update([
                'status' => 'deleted'
            ]);

            $this->logActivity(
                'product_bulk_deleted',
                $product,
                "Product '{$product->name}' deleted through bulk action"
            );
        }

        return redirect()
            ->back()
            ->with(
                'success',
                count($products) .
                    ' product(s) deleted successfully'
            );
    }

    // =========================================================
    // 5. DUPLICATE PRODUCT
    // =========================================================

    public function duplicate(Product $product)
    {
        DB::transaction(function () use ($product, &$newProduct) {

            $newProduct = $product->replicate();

            $newProduct->name =
                $product->name . ' (Copy)';

            $newProduct->sku = null;

            $newProduct->status = 'active';

            $newProduct->save();

            /*
            |--------------------------------------------------------------------------
            | Duplicate Variants
            |--------------------------------------------------------------------------
            */

            foreach ($product->variants as $variant) {

                $newVariant = $variant->replicate();

                $newVariant->product_id =
                    $newProduct->id;

                $newVariant->sku = null;

                $newVariant->save();
            }
        });

        $this->logActivity(
            'product_duplicated',
            $newProduct,
            "Product '{$product->name}' duplicated"
        );

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product duplicated successfully'
            );
    }
}
