# PHP_Larvel12_Simple_Ecommerce_Store
# Functionality for ecommerce store
```php
Create Client side and server side
Adding admin authentication for server side
Admin Profile section and Customer profile section available
Add to Cart and Existing Address selecting
Adding customer authentication for client side
Two option for place order in COD and ONlINE RAZORPAY
Server Side All customer order show page 
Customer side only for login customer order page
```


# Step 1 : Install Laravel12 and create project for simple ecommerce store
```php
composer create-project laravel/laravel PHP_Larvel12_Simple_Ecommerce_Store “^12.0”
```
# Step 2 :Setup database for .env file
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Your Database name
DB_USERNAME=root
DB_PASSWORD=
```

# Now Create Simple Ecommerce Store Project With Client and Server side .
# Step 3 :Create Products table and stored ProductName ,Details,Price,Size,Color,Category,Image
Run command for terminal
```php
php artisan make:model  Product-mc with controller and migration file
```
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    'name',
    'details',
    'price',
    'image',
    'sizes',
    'colors',
    'categories',
     'status',
];

protected $casts = [
    'sizes' => 'array',
    'colors' => 'array',
    'categories' => 'array',
];
```
# Step 4 : Now adding create , edit , index , store and delete method for product controller
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ================================
    // LIST (ONLY ACTIVE PRODUCTS)
    // ================================
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::where('status', 'active') // ✅ IMPORTANT
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('details', 'like', "%{$search}%")
                      ->orWhere('price', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')   // OLD → NEW
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
    // CREATE FORM
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
    // STORE (DEFAULT STATUS = ACTIVE)
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

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Product::create([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'image'      => $imageName,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
            'status'     => 'active', // ✅ DEFAULT
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully');
    }

    // ================================
    // EDIT FORM
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

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->update([
            'name'       => $request->name,
            'details'    => $request->details,
            'price'      => $request->price,
            'sizes'      => $request->sizes,
            'colors'     => $request->colors,
            'categories' => $request->categories,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    // ================================
    // DELETE (SOFT DELETE BY STATUS)
    // ================================
    public function destroy(Product $product)
    {
        $product->update([
            'status' => 'deleted' // ✅ SOFT DELETE
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
```

# Step 5 : Create web route for routes/web.php file
```php
use App\Http\Controllers\ProductController;
 Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('prod
```
# Step 6 : Create Index,create,and edit .blade.file in resource/view/products folder
```php
resource/view/products/index.blade.php
resource/view/products/create.blade.php
resource/view/products/edit.blade.php
```
# Step 7 : Now Seam Create Sizes, Colors and Categories Module for Products 
# Create Size Controller , Table , Model , Routes and index , create and edit.blade.php file 
```php
Routes/web.php
use App\Http\Controllers\SizeController;
    Route::resource('sizes', SizeController::class);
```
# Controller Method
```php
<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // LIST
    public function index()
    {
        $sizes = Size::latest()->get();
        return view('sizes.index', compact('sizes'));
    }

    // CREATE FORM
    public function create()
    {
        return view('sizes.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name'
        ]);

        Size::create([
            'size_name' => $request->size_name
        ]);

        return redirect()->route('sizes.index')
                         ->with('success', 'Size added successfully');
    }

    // EDIT FORM
    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    // UPDATE
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'size_name' => 'required|unique:sizes,size_name,' . $size->id
        ]);

        $size->update([
            'size_name' => $request->size_name
        ]);

        return redirect()->route('sizes.index')
                         ->with('success', 'Size updated successfully');
    }

    // DELETE
    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->route('sizes.index')
                         ->with('success', 'Size deleted successfully');
    }
}

```
```php
resource/view/sizes/index.blade.php
resource/view/ sizes /create.blade.php
resource/view/ sizes /edit.blade.php
```
# Create Color Controller , Table , Model , Routes and index , create and edit.blade.php file 
```php
Routes/web.php
use App\Http\Controllers\ColorController;
    Route::resource('colors', ColorController::class);
```
# Controller Method
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
class ColorController extends Controller
{
    public function index() {
        $colors = Color::latest()->get();
        return view('colors.index', compact('colors'));
    }

    public function create() {
        return view('colors.create');
    }

    public function store(Request $request) {
        $request->validate([
            'color_name' => 'required|unique:colors'
        ]);

        Color::create($request->only('color_name'));

        return redirect()->route('colors.index')->with('success','Color added');
    }

    public function edit(Color $color) {
        return view('colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color) {
        $request->validate([
            'color_name' => 'required|unique:colors,color_name,'.$color->id
        ]);

        $color->update($request->only('color_name'));

        return redirect()->route('colors.index')->with('success','Color updated');
    }

    public function destroy(Color $color) {
        $color->delete();
        return redirect()->route('colors.index')->with('success','Color deleted');
    }
}

```
```php
resource/view/colors/index.blade.php
resource/view/ colors /create.blade.php
resource/view/ colors /edit.blade.php
```
# Create Categories Controller , Table , Model , Routes and index , create and edit.blade.php file 
```php
Routes/web.php
use App\Http\Controllers\CategoriesController;
    Route::resource('category', CategoriesController::class);
```
# Controller Method
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'category_name' => 'required|unique:categories'
        ]);

        Category::create($request->only('category_name'));

        return redirect()->route('categories.index')->with('success','Category added');
    }

    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'category_name' => 'required|unique:categories,category_name,'.$category->id
        ]);

        $category->update($request->only('category_name'));

        return redirect()->route('categories.index')->with('success','Category updated');
    }

    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted');
    }
}
```
```php
resource/view/categories/index.blade.php
resource/view/ categories /create.blade.php
resource/view/ categories /edit.blade.php
```
# Now Adding Admin authentication For brezz laravel
```php
composer require laravel/breeze --dev
```
```php
php artisan breeze:install blade
```
```php
npm install
npm run dev
```
```php
php artisan migrate
```
```php
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```
```php
php artisan serve
Then login → redirect to:
/products
```
# Now Adding Admin layout for layouts folder

# Resource/view/layouts/admin.blade.php
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- META --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- BOOTSTRAP CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- SELECT2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    {{-- VITE (TAILWIND + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- PAGE LEVEL CSS --}}
    @stack('styles')
</head>

<body class="bg-light">

    {{-- ✅ ADMIN NAVIGATION --}}
    @include('layouts.navigation')

    {{-- 🔔 FLASH MESSAGES --}}
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- 📦 MAIN CONTENT --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- FOOTER (OPTIONAL) --}}

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- SELECT2 GLOBAL INIT --}}
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select options',
                allowClear: true
            });
        });
    </script>

    {{-- ORDER VIEW TOGGLE (ADMIN ORDERS) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.view-order').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const targetRow = document.getElementById(targetId);

                    if (!targetRow) return;

                    document.querySelectorAll('.order-details').forEach(row => {
                        if (row !== targetRow) row.classList.add('d-none');
                    });

                    targetRow.classList.toggle('d-none');
                });
            });
        });
    </script>

    {{-- PAGE LEVEL SCRIPTS --}}
    @stack('scripts')

</body>
</html>
```

<img width="325" height="204" alt="image" src="https://github.com/user-attachments/assets/72886bbc-5f68-4c85-ac92-898971a91dab" />

 <img width="326" height="206" alt="image" src="https://github.com/user-attachments/assets/d350cf41-3242-4ce5-a36f-357e987fa0fe" />

 <img width="628" height="300" alt="image" src="https://github.com/user-attachments/assets/bef0a23f-acdc-4a80-8ff6-dacdb903893f" />

 


# Now This All Products Show Client side and adding add to cart function this products:
Create Route , controller for customer
# Controller method
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // CUSTOMER PRODUCT LIST + SEARCH + PAGINATION
public function index(Request $request)
{
    $search = $request->search;

    // 🔹 NAME se ID nikalna
    $sizeIds = Size::where('size_name', 'like', "%{$search}%")
                    ->pluck('id')
                    ->toArray();

    $colorIds = Color::where('color_name', 'like', "%{$search}%")
                     ->pluck('id')
                     ->toArray();

    $categoryIds = Category::where('category_name', 'like', "%{$search}%")
                            ->pluck('id')
                            ->toArray();

    // 🔹 PRODUCT SEARCH
     $products = Product::where('status', 'active')
        ->when($search, function ($query) use (
            $search,
            $sizeIds,
            $colorIds,
            $categoryIds
        ) {
            $query->where(function ($q) use (
                $search,
                $sizeIds,
                $colorIds,
                $categoryIds
            ) {

                // ✅ Product fields
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%");

                // ✅ Size NAME search
                foreach ($sizeIds as $id) {
                    $q->orWhereJsonContains('sizes', $id);
                }

                // ✅ Color NAME search
                foreach ($colorIds as $id) {
                    $q->orWhereJsonContains('colors', $id);
                }

                // ✅ Category NAME search
                foreach ($categoryIds as $id) {
                    $q->orWhereJsonContains('categories', $id);
                }
            });
        })
        ->orderBy('id', 'asc')   // ✅ OLD → NEW (new record niche)
        ->paginate(8)
        ->withQueryString();

    // 🔹 Mapping for view
    $sizes = Size::pluck('size_name', 'id');
    $colors = Color::pluck('color_name', 'id');
    $categories = Category::pluck('category_name', 'id');

    // 🔹 CUSTOMER AUTH INFO
    $isCustomerLoggedIn = auth('customer')->check();
    $customer = auth('customer')->user();

    return view('customer.index', compact(
        'products',
        'sizes',
        'colors',
        'categories',
        'isCustomerLoggedIn',
        'customer'
    ));
}

}
```
```php
Route for web.php file
use App\Http\Controllers\CustomerController;

Route::get('/customer/products', [CustomerController::class, 'index'])
    ->name('customer.products');
```

# Create index.blade.php file for customers folder in resource/view/customers folder

# Resource/view/customers/index.blade.php
# Now Create Carts table , model , controller and index.blade.php file and adding  web route and store  add to carts products 
```php
Create carts table , model , controller and web route
Controller
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
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

    $product = Product::findOrFail($request->product_id);

    // 🔍 Check if same item already exists
    $cartItem = Cart::where('customer_id', $customerId)
        ->where('product_id', $product->id)
        ->where('size_id', $request->size_id)
        ->where('color_id', $request->color_id)
        ->first();

    if ($cartItem) {
        // ➕ increase quantity (max 5)
        $cartItem->quantity = min($cartItem->quantity + $request->quantity, 5);
        $cartItem->save();
    } else {
        Cart::create([
            'customer_id' => $customerId,
            'product_id'  => $product->id,
            'size_id'     => $request->size_id,
            'color_id'    => $request->color_id,
            'category_id' => $request->category_id,
            'quantity'    => $request->quantity,
            'price'       => $product->price,
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

    if ($request->action === 'increase' && $cart->quantity < 5) {
        $cart->quantity += 1;
    }

    if ($request->action === 'decrease' && $cart->quantity > 1) {
        $cart->quantity -= 1;
    }

    $cart->save();

    return redirect()->route('cart.index');
}

}
```
```php
Web route for routes/web.php file
use App\Http\Controllers\CartController;

 Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add', [CartController::class, 'store'])
        ->name('cart.add');

    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])
        ->name('cart.remove');
Route::post('/cart/update-quantity/{cart}',
    [CartController::class, 'updateQuantity']
)->name('cart.update.quantity');
```
# Resource/view/cart/index.blade.php
```php
    @extends('layouts.customer')

    @section('content')

    <h2 class="mb-4">My Cart</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Size</th>
                <th>Color</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp

            @forelse($cartItems as $item)
            @php
                $total = $item->price * $item->quantity;
                $grandTotal += $total;
            @endphp
            <tr>
                <td>
                    <img src="{{ asset('images/'.$item->product->image) }}"
                        width="60" class="rounded me-2">
                    {{ $item->product->name }}
                </td>
                <td>{{ $sizes[$item->size_id] ?? '-' }}</td>
                <td>{{ $colors[$item->color_id] ?? '-' }}</td>
                <td>{{ $categories[$item->category_id] ?? '-' }}</td>
               <td>
    <div class="d-flex align-items-center gap-2">

        {{-- ➖ DECREASE --}}
        <form action="{{ route('cart.update.quantity', $item->id) }}"
              method="POST">
            @csrf
            <input type="hidden" name="action" value="decrease">
            <button class="btn btn-outline-secondary btn-sm"
                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                −
            </button>
        </form>

        {{-- QTY --}}
        <strong>{{ $item->quantity }}</strong>

        {{-- ➕ INCREASE --}}
        <form action="{{ route('cart.update.quantity', $item->id) }}"
              method="POST">
            @csrf
            <input type="hidden" name="action" value="increase">
            <button class="btn btn-outline-secondary btn-sm"
                    {{ $item->quantity >= 5 ? 'disabled' : '' }}>
                +
            </button>
        </form>

    </div>
</td>

                <td>₹ {{ $item->price }}</td>
                <td>₹ {{ $total }}</td>
                <td>
                    <form action="{{ route('cart.remove',$item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Cart is empty</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary">
            ← Continue Shopping
        </a>

        <h4 class="mb-0">
            Grand Total: ₹ {{ $grandTotal }}
        </h4>
        <a href="{{ route('address.index') }}" class="btn btn-success">
            Process to Checkout
        </a>
    </div>

    @endsection
```

#  Now Create customer authentication and set up for client side for when customer  any product add to cart if not logged for any time then open customer login page and after success customer login to add to cart any products 
Create customer table , route , customerauthcontroller,and register,login and dashboard .blade.php file
# CustomerAuthController method
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    // Register page
    public function register()
    {
        return view('customer.register');
    }

    // Register store
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')
            ->with('success', 'Register successful');
    }

    // Login page
    public function login()
    {
        return view('customer.login');
    }

    // ✅ LOGIN POST (UPDATED)
    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($request->only('email', 'password'))) {

            //  Login ke baad DIRECT products page
            return redirect()->route('customer.products');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ]);
    }

    // (Optional) Dashboard – ab use nahi hoga
    public function dashboard()
    {
        return view('customer.dashboard');
    }

    // Logout
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login');
    }
}
```
```php
Web route in web.php file
use App\Http\Controllers\CustomerAuthController;
/ Register
Route::get('/customer/register', [CustomerAuthController::class, 'register'])
    ->name('customer.register');

Route::post('/customer/register', [CustomerAuthController::class, 'registerPost'])
    ->name('customer.register.post');

// Login
Route::get('/customer/login', [CustomerAuthController::class, 'login'])
    ->name('customer.login');

Route::post('/customer/login', [CustomerAuthController::class, 'loginPost'])
    ->name('customer.login.post');

// Logout (protected)
Route::middleware('auth:customer')->group(function () {
    Route::get('/customer/logout', [CustomerAuthController::class, 'logout'])
        ->name('customer.logout');
});

 ```
<img width="373" height="244" alt="image" src="https://github.com/user-attachments/assets/eb2fb732-c04f-4674-83a5-e4a668e9844d" />
<img width="414" height="204" alt="image" src="https://github.com/user-attachments/assets/25a3e10c-ee13-4b8c-86fa-80d1e85684b4" />
<img width="628" height="322" alt="image" src="https://github.com/user-attachments/assets/a34d4463-0ece-42de-9e5d-06f6a83fcfcd" />
<img width="628" height="181" alt="image" src="https://github.com/user-attachments/assets/c3598cc5-f96d-423b-87b2-687ede5c1b33" />

 


 

 

# When Click process to checkout then open Address Page and store all address details 
# Create address table, controller , web.php route , create blade.php file
# AddressController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // ==============================
    // 📍 Address list + form
    // ==============================
    public function index()
    {
        $customerId = auth('customer')->id();

        // ✅ ONLY LOGGED-IN CUSTOMER ADDRESSES
        $addresses = Address::where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('address.index', compact('addresses'));
    }

    // ==============================
    // 💾 Save address (NORMAL SAVE PAGE)
    // ==============================
    public function store(Request $request)
    {
        $customerId = auth('customer')->id();

        $request->validate([
            'address' => 'required',
            'city'    => 'required',
            'state'   => 'required',
            'pincode' => 'required|min:6',
        ]);

        // 🔐 DUPLICATE PROTECTION
        Address::firstOrCreate(
            [
                'customer_id' => $customerId,
                'address'     => $request->address,
                'nearby'      => $request->nearby,
                'city'        => $request->city,
                'state'       => $request->state,
                'pincode'     => $request->pincode,
            ]
        );

        return redirect()->route('address.index')
            ->with('success', 'Address saved successfully');
    }

    // ==============================
    // 🚚 SAVE ADDRESS FOR CHECKOUT
    // ==============================
    public function saveForCheckout(Request $request)
    {
        $customerId = auth('customer')->id();

        /*
        |--------------------------------------------------
        | ✅ CASE 1: EXISTING ADDRESS SELECTED (DROPDOWN)
        | 👉 DB me bilkul save NA ho
        |--------------------------------------------------
        */
        if (!empty($request->address_id)) {

            $address = Address::where('id', $request->address_id)
                ->where('customer_id', $customerId)
                ->firstOrFail();

            session([
                'checkout_address' => [
                    'address' => $address->address,
                    'nearby'  => $address->nearby,
                    'city'    => $address->city,
                    'state'   => $address->state,
                    'pincode' => $address->pincode,
                ]
            ]);

            return redirect()->route('checkout.payment');
        }

        /*
        |--------------------------------------------------
        | ✅ CASE 2: NEW ADDRESS (CHECK DUPLICATE)
        |--------------------------------------------------
        */
        $request->validate([
            'address' => 'required',
            'city'    => 'required',
            'state'   => 'required',
            'pincode' => 'required|min:6',
        ]);

        // 🔥 MAIN FIX: SAME ADDRESS → SAME RECORD
        $address = Address::firstOrCreate(
            [
                'customer_id' => $customerId,
                'address'     => $request->address,
                'nearby'      => $request->nearby,
                'city'        => $request->city,
                'state'       => $request->state,
                'pincode'     => $request->pincode,
            ]
        );

        session([
            'checkout_address' => [
                'address' => $address->address,
                'nearby'  => $address->nearby,
                'city'    => $address->city,
                'state'   => $address->state,
                'pincode' => $address->pincode,
            ]
        ]);

        return redirect()->route('checkout.payment');
    }
}
```
```php
Web.php route
use App\Http\Controllers\AddressController;
 // Address
    Route::get('/checkout/address', [AddressController::class, 'index'])
        ->name('address.index');

    Route::post('/checkout/address', [AddressController::class, 'saveForCheckout'])
        ->name('checkout.saveAddress');
```
# Create index.blade.php file for resource/view/address folder
# Resource/view/address/index.blade.php file
```php
@extends('layouts.customer')

@section('content')

<h2 class="mb-4 text-center">Delivery Address</h2>

@if(session('success'))
<div class="alert alert-success text-center">
    {{ session('success') }}
</div>
@endif

<div class="row">

    {{-- 🏠 EXISTING ADDRESS (DROPDOWN) --}}
    <div class="col-md-8 offset-md-2 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Saved Addresses</h5>

                @if($addresses->count())
                    <select class="form-select" id="addressDropdown" onchange="fillFromDropdown(this)">
                        <option value="">Select Saved Address</option>

                        @foreach($addresses as $addr)
                            <option
                                data-id="{{ $addr->id }}"
                                data-address="{{ $addr->address }}"
                                data-nearby="{{ $addr->nearby }}"
                                data-city="{{ $addr->city }}"
                                data-state="{{ $addr->state }}"
                                data-pincode="{{ $addr->pincode }}"
                            >
                                {{ $addr->address }}, {{ $addr->city }}
                            </option>
                        @endforeach
                    </select>

                    <div class="mt-2 text-muted small">
                        Selecting an address will auto-fill the form
                    </div>
                @else
                    <p class="text-muted mb-0">No saved addresses found</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ➕ ADD / USE ADDRESS FORM --}}
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Add / Use Address</h5>

                <form method="POST" action="{{ route('checkout.saveAddress') }}">
                    @csrf

                    {{-- ✅ EXISTING ADDRESS ID --}}
                    <input type="hidden" name="address_id" id="address_id">

                    <div class="mb-2">
                        <textarea
                            name="address"
                            id="address"
                            class="form-control"
                            placeholder="Full Address"
                            required
                            oninput="clearAddressId()"></textarea>
                    </div>

                    <div class="mb-2">
                        <input
                            type="text"
                            name="nearby"
                            id="nearby"
                            class="form-control"
                            placeholder="Nearby Landmark (optional)"
                            oninput="clearAddressId()">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input
                                type="text"
                                name="city"
                                id="city"
                                class="form-control"
                                placeholder="City"
                                required
                                oninput="clearAddressId()">
                        </div>

                        <div class="col-md-4 mb-2">
                            <input
                                type="text"
                                name="state"
                                id="state"
                                class="form-control"
                                placeholder="State"
                                required
                                oninput="clearAddressId()">
                        </div>

                        <div class="col-md-4 mb-2">
                            <input
                                type="text"
                                name="pincode"
                                id="pincode"
                                class="form-control"
                                placeholder="Pincode"
                                required
                                oninput="clearAddressId()">
                        </div>
                    </div>

                    <button class="btn btn-success w-100 mt-2">
                        Save & Continue to Payment
                    </button>

                </form>
            </div>
        </div>
    </div>

</div>
<a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
        ← Back to cart
    </a>
{{-- 🔥 AUTO FILL + SAFETY SCRIPT --}}
<script>
function fillFromDropdown(select) {
    let option = select.options[select.selectedIndex];

    document.getElementById('address_id').value =
        option.getAttribute('data-id') || '';

    document.getElementById('address').value =
        option.getAttribute('data-address') || '';

    document.getElementById('nearby').value =
        option.getAttribute('data-nearby') || '';

    document.getElementById('city').value =
        option.getAttribute('data-city') || '';

    document.getElementById('state').value =
        option.getAttribute('data-state') || '';

    document.getElementById('pincode').value =
        option.getAttribute('data-pincode') || '';
}

// 🔐 IMPORTANT: agar user manually kuch change kare
function clearAddressId() {
    document.getElementById('address_id').value = '';
}
</script>

@endsection
```
 <img width="628" height="381" alt="image" src="https://github.com/user-attachments/assets/a0c3d859-560e-47c8-9e8f-c19c0d4a7d30" />

# When fillup all address details and click save/continue payment then open payment page and show all details for customer name , address , products details.
# Create checkout controller 
```php
class CheckoutController extends Controller
{
    // ==============================
    // ✅ PAYMENT PAGE (GET)
    // ==============================
    public function paymentPage()
    {
        $customerId = auth('customer')->id();
        $address = session('checkout_address');

        if (!$address) {
            return redirect()->route('customer.products')
                ->with('error', 'Invalid checkout session');
        }

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.products')
                ->with('error', 'Cart is empty');
        }

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        // 🔥 DISCOUNT LOGIC
        $cartProductIds = $cartItems->pluck('product_id')->toArray();
        $today = Carbon::today();

        $allProductDiscounts = Discount::where('apply_to', 'all_products')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        $specificDiscounts = Discount::where('apply_to', 'specific_product')
            ->get()
            ->filter(function ($discount) use ($cartProductIds) {
                $ids = json_decode($discount->product_ids, true) ?? [];
                return count(array_intersect($ids, $cartProductIds)) > 0;
            });

        return view('checkout.payment', compact(
            'address',
            'cartItems',
            'sizes',
            'colors',
            'categories',
            'allProductDiscounts',
            'specificDiscounts'
        ));
    }
```
```php
Create web.php file
use App\Http\Controllers\CheckoutController;
 Route::get('/checkout/payment', [CheckoutController::class, 'paymentPage'])
        ->name('checkout.payment');
```
# Create payment .blade.php file for resource/view/checkout folder
```php
@extends('layouts.customer')

@section('content')

<style>
    .payment-title { font-weight: 700; letter-spacing: 0.5px; }
    .checkout-card { border: none; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); margin-bottom: 25px; }
    .checkout-card h5 { font-weight: 600; margin-bottom: 15px; }
    .address-box p { margin-bottom: 4px; font-size: 14px; color: #555; }
    .order-item { padding: 12px 0; border-bottom: 1px dashed #ddd; }
    .order-item:last-child { border-bottom: none; }
    .order-item-name { font-weight: 600; }
    .order-meta { font-size: 13px; color: #666; }
    .price-box { background: #f8f9fa; border-radius: 10px; padding: 15px; margin-top: 10px; }
    .total-amount { font-size: 20px; font-weight: 700; color: #198754; }
    .payment-option { border: 1px solid #ddd; border-radius: 10px; padding: 12px 15px; cursor: pointer; transition: 0.2s; }
    .payment-option:hover { border-color: #198754; background: #f6fffa; }
    .place-order-btn { font-size: 18px; font-weight: 600; padding: 14px; border-radius: 12px; }
</style>

<h2 class="mb-4 text-center payment-title">Secure Payment</h2>

<div class="row justify-content-center">

    {{-- 📍 ADDRESS --}}
    <div class="col-md-8">
        <div class="card checkout-card">
            <div class="card-body address-box">
                <h5>📍 Delivery Address</h5>
                <p><strong>Name:</strong> {{ auth('customer')->user()->name }}</p>
                <p><strong>Address:</strong> {{ $address['address'] }}</p>
                <p>{{ $address['city'] }}, {{ $address['state'] }} - {{ $address['pincode'] }}</p>
            </div>
        </div>
    </div>

    {{-- 🛒 ORDER SUMMARY --}}
    <div class="col-md-8">
        <div class="card checkout-card">
            <div class="card-body">
                <h5>🛒 Order Summary</h5>

                @php $grandTotal = 0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $total = $item->price * $item->quantity;
                        $grandTotal += $total;
                    @endphp
                    <div class="order-item">
                        <div class="order-item-name">{{ $item->product->name }}</div>
                        <div class="order-meta">
                            Size: {{ $sizes[$item->size_id] ?? '' }} |
                            Color: {{ $colors[$item->color_id] ?? '' }} |
                            Qty: {{ $item->quantity }}
                        </div>
                    </div>
                @endforeach

                {{-- 🎁 DISCOUNT DROPDOWN --}}
                @if($specificDiscounts->count() || $allProductDiscounts->count())
                    <label class="mt-3 fw-bold">🎁 Apply Discount</label>
                    <select id="discountSelect" class="form-select">
                        <option value="">-- No Discount --</option>

                        @foreach($specificDiscounts as $discount)
                            <option data-type="{{ $discount->apply_on }}" data-value="{{ $discount->value }}">
                                {{ $discount->title }} (Specific)
                            </option>
                        @endforeach

                        @foreach($allProductDiscounts as $discount)
                            <option data-type="{{ $discount->apply_on }}" data-value="{{ $discount->value }}">
                                {{ $discount->title }} (All Products)
                            </option>
                        @endforeach
                    </select>
                @endif

                {{-- 💰 TOTAL --}}
                <div class="price-box">
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <span>₹ <span id="originalTotal">{{ $grandTotal }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between text-danger">
                        <span>Discount</span>
                        <span>- ₹ <span id="discountAmount">0</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between total-amount">
                        <span>Payable</span>
                        <span>₹ <span id="finalAmount">{{ $grandTotal }}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 💳 PAYMENT --}}
    <div class="col-md-8">
        <div class="card checkout-card">
            <div class="card-body">
                <h5>💳 Payment Method</h5>

                <form id="paymentForm" method="POST" action="{{ route('place.order') }}">
                    @csrf
                    <input type="hidden" name="discount" id="discountInput" value="0">

                    <label class="payment-option d-flex align-items-center mb-2">
                        <input type="radio" name="payment_method" value="COD" checked class="me-2">
                        Cash on Delivery
                    </label>

                    <label class="payment-option d-flex align-items-center mb-3">
                        <input type="radio" name="payment_method" value="ONLINE" class="me-2">
                        Online Payment
                    </label>

                    <button type="submit" class="btn btn-success w-100 place-order-btn">
                        Place Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
let originalTotal = {{ $grandTotal }};

document.getElementById('discountSelect')?.addEventListener('change', function () {
    let opt = this.options[this.selectedIndex];
    let type = opt.dataset.type;
    let value = parseFloat(opt.dataset.value || 0);
    let discount = 0;

    if (type === 'percentage') discount = (originalTotal * value) / 100;
    if (type === 'fixed') discount = value;

    discount = Math.min(discount, originalTotal);

    document.getElementById('discountAmount').innerText = discount.toFixed(2);
    document.getElementById('finalAmount').innerText = (originalTotal - discount).toFixed(2);
    document.getElementById('discountInput').value = discount.toFixed(2);
});

document.querySelector('.place-order-btn').addEventListener('click', function (e) {

    let method = document.querySelector('input[name="payment_method"]:checked').value;
    if (method === 'COD') return;

    e.preventDefault();
    let payable = document.getElementById('finalAmount').innerText;

    fetch("{{ route('razorpay.order') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ amount: payable })
    })
    .then(res => res.json())
    .then(data => {
        let rzp = new Razorpay({
            key: data.key,
            amount: data.amount * 100,
            currency: "INR",
            order_id: data.order_id,
            handler: function (response) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('razorpay.verify') }}";
                form.innerHTML = `
                    @csrf
                    <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                    <input type="hidden" name="razorpay_order_id" value="${response.razorpay_order_id}">
                    <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                    <input type="hidden" name="discount" value="${document.getElementById('discountInput').value}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
        rzp.open();
    });
});
</script>

@endsection

 ```
<img width="607" height="471" alt="image" src="https://github.com/user-attachments/assets/3cffd0e4-1664-4124-a292-11460ed6d686" />

# Now Adding payment method for cash and razorpay and when click any one option then order place successful and store all details for orders and order_items table in database 
# Adding razorpay id in .env file
```php
RAZORPAY_KEY=Your_key
RAZORPAY_SECRET=Your_secret
```
# Create orders and order_items table and model 
# Orders model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'subtotal',          // ✅ NEW
        'discount_amount',   // ✅ NEW
        'total_price',
        'payment_method',
        'payment_status',    // (ONLINE / COD)
        'status',            // (pending, shipped, delivered etc.)
    ];

    // 🔗 Order → Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 🔗 Order → Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🔗 Order → Address
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
```

# orderitem model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'category_id',
        'quantity',
        'price',
        'discount_amount', // ✅ NEW
        'total',
    ];

    // 🔗 Item → Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔗 Item → Size
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // 🔗 Item → Color
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // 🔗 Item → Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```
# Adding Web Route For Order place in Cash and Razorpay:
```php
 // Payment
    Route::get('/checkout/payment', [CheckoutController::class, 'paymentPage'])
        ->name('checkout.payment');

    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('place.order');

    // Razorpay
    Route::post('/razorpay/order', [CheckoutController::class, 'razorpayOrder'])
        ->name('razorpay.order');

    Route::post('/razorpay/verify', [CheckoutController::class, 'razorpayVerify'])
        ->name('razorpay.verify');

    // Success
    Route::get('/order-success', function () {
        return view('checkout.success');
    })->name('order.success');
});

```
# Adding Cash and Online Razorpay Method In Place Order and Store Method For ChekoutController
# ChekoutController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Address;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Discount;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // ==============================
    // ✅ PAYMENT PAGE (GET)
    // ==============================
    public function paymentPage()
    {
        $customerId = auth('customer')->id();
        $address = session('checkout_address');

        if (!$address) {
            return redirect()->route('customer.products')
                ->with('error', 'Invalid checkout session');
        }

        $cartItems = Cart::with('product')
            ->where('customer_id', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.products')
                ->with('error', 'Cart is empty');
        }

        $sizes = Size::pluck('size_name', 'id');
        $colors = Color::pluck('color_name', 'id');
        $categories = Category::pluck('category_name', 'id');

        // 🔥 DISCOUNT LOGIC
        $cartProductIds = $cartItems->pluck('product_id')->toArray();
        $today = Carbon::today();

        $allProductDiscounts = Discount::where('apply_to', 'all_products')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        $specificDiscounts = Discount::where('apply_to', 'specific_product')
            ->get()
            ->filter(function ($discount) use ($cartProductIds) {
                $ids = json_decode($discount->product_ids, true) ?? [];
                return count(array_intersect($ids, $cartProductIds)) > 0;
            });

        return view('checkout.payment', compact(
            'address',
            'cartItems',
            'sizes',
            'colors',
            'categories',
            'allProductDiscounts',
            'specificDiscounts'
        ));
    }

    // ==============================
    // ✅ RAZORPAY ORDER CREATE
    // ==============================
    public function razorpayOrder(Request $request)
    {
        $amount = (float) $request->amount;

        if ($amount <= 0) {
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $order = $api->order->create([
            'receipt'  => 'order_' . time(),
            'amount'   => $amount * 100,
            'currency' => 'INR'
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount'   => $amount,
            'key'      => config('services.razorpay.key')
        ]);
    }

    // ==============================
    // ✅ RAZORPAY VERIFY
    // ==============================
    public function razorpayVerify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature'  => 'required',
        ]);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            $request->merge(['payment_method' => 'ONLINE']);
            return $this->placeOrder($request);

        } catch (\Exception $e) {
            return redirect()->route('checkout.payment')
                ->with('error', 'Payment verification failed');
        }
    }

    // ==============================
    // ✅ PLACE ORDER (COD + ONLINE)
    // ==============================
    public function placeOrder(Request $request)
    {
        $customerId = auth('customer')->id();

        $request->validate([
            'payment_method' => 'required|in:COD,ONLINE',
        ]);

        $discount = (float) ($request->discount ?? 0);

        $sessionAddress = session('checkout_address');
        if (!$sessionAddress) {
            return redirect()->route('address.index')
                ->with('error', 'Please select address again');
        }

        $address = Address::where('customer_id', $customerId)
            ->where('address', $sessionAddress['address'])
            ->where('city', $sessionAddress['city'])
            ->where('state', $sessionAddress['state'])
            ->where('pincode', $sessionAddress['pincode'])
            ->firstOrFail();

        $cartItems = Cart::where('customer_id', $customerId)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.products')
                ->with('error', 'Cart is empty');
        }

        // 🔢 TOTALS
        $subtotal = $cartItems->sum(fn ($i) => $i->price * $i->quantity);
        $finalTotal = max($subtotal - $discount, 0);

        // 🧾 CREATE ORDER
        $order = Order::create([
            'customer_id'     => $customerId,
            'address_id'      => $address->id,
            'subtotal'        => $subtotal,
            'discount_amount' => $discount,
            'total_price'     => $finalTotal,
            'payment_method'  => $request->payment_method,
             'status'          => $request->payment_method === 'ONLINE'
                            ? 'paid'
                            : 'pending',
        ]);

        // 🧾 ORDER ITEMS (PROPORTIONAL DISCOUNT)
        foreach ($cartItems as $item) {

            $itemSubtotal = $item->price * $item->quantity;

            $itemDiscount = $subtotal > 0
                ? round(($itemSubtotal / $subtotal) * $discount, 2)
                : 0;

            OrderItem::create([
                'order_id'        => $order->id,
                'product_id'      => $item->product_id,
                'size_id'         => $item->size_id,
                'color_id'        => $item->color_id,
                'category_id'     => $item->category_id,
                'quantity'        => $item->quantity,
                'price'           => $item->price,
                'discount_amount' => $itemDiscount,
                'total'           => $itemSubtotal - $itemDiscount,
            ]);
        }

        // 🧹 CLEAR
        Cart::where('customer_id', $customerId)->delete();
        session()->forget('checkout_address');

        return redirect()->route('order.success');
    }
}
```
# Create Success.blade.php file in resource/view/checkout folder
```php
@extends('layouts.customer')

@section('content')

<div class="text-center mt-5">
    <h2 class="text-success">🎉 Order Placed Successfully!</h2>
    <p class="mt-3">Thank you for shopping with us.</p>

    <a href="{{ route('customer.products') }}" class="btn btn-primary mt-3">
        Continue Shopping
    </a>
</div>

@endsection
```
# Now Create Customer Layouts for Header and Footer Function
# resource/view/layouts/customer.blade.php
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ecommerce Platform')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            padding-top: 80px;
            background-color: #f8f9fb;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        main {
            flex: 1;
        }

        /* =======================
           NAVBAR
        ======================== */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 22px;
            color: #0d6efd !important;
            letter-spacing: 0.4px;
        }

        .navbar .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 6px 14px;
        }

        .navbar .btn-outline-primary:hover {
            background: #0d6efd;
            color: #fff;
        }

        /* =======================
           PROFILE IMAGE
        ======================== */
        .profile-img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            transition: 0.2s;
        }

        .profile-img:hover {
            border-color: #0d6efd;
        }

        .dropdown-menu {
            border-radius: 14px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
        }

        /* =======================
           FOOTER
        ======================== */
        footer {
            background: linear-gradient(180deg, #ffffff, #f1f5f9);
            border-top: 1px solid #e5e7eb;
            color: #475569;
        }

        footer h4,
        footer h5 {
            color: #0f172a;
            font-weight: 700;
        }

        footer p {
            font-size: 14px;
            line-height: 1.6;
        }

        footer a {
            color: #475569;
            text-decoration: none;
            transition: 0.2s;
            font-size: 14px;
        }

        footer a:hover {
            color: #0d6efd;
            text-decoration: underline;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-bottom {
            font-size: 13px;
            color: #64748b;
        }
        .footer-link {
    font-size: 19px;
    color:rgb(10, 112, 255);
    text-decoration: none;
    transition: 0.2s;
  
}

.footer-link:hover {
    color: #0d6efd;
    text-decoration: underline;
}

    </style>
</head>

<body>

{{-- 🔹 NAVBAR --}}
<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="{{ route('customer.products') }}">
             Ecommerce Platform
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">

            @auth('customer')
                <a href="{{ route('customer.products') }}" class="btn btn-outline-primary btn-sm">
                    Products
                </a>

                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm">
                    Cart
                </a>

                <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary btn-sm">
                    Orders
                </a>

                {{-- PROFILE --}}
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown">
                        <img
                            src="{{ auth('customer')->user()->profile_image
                                    ? asset('images/'.auth('customer')->user()->profile_image)
                                    : asset('images/default-user.png') }}"
                            class="profile-img">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                 My Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('customer.logout') }}">
                                 Logout
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('customer.login') }}" class="btn btn-primary btn-sm">
                    Login
                </a>
            @endauth

            <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">
                Admin Panel
            </a>
        </div>
    </div>
</nav>

{{-- 🔹 PAGE CONTENT --}}
<main class="container my-4">
    @yield('content')
</main>

{{-- 🔹 FOOTER --}}
<footer class="pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row gy-4">

          <div class="col-md-4">
    <h4> Ecommerce Platform</h4>

    <div class="d-flex gap-3 mt-2 flex-wrap">
        <a href="{{ route('about') }}" class="footer-link">About Us</a>
        <a href="{{ route('privacy') }}" class="footer-link">Privacy</a>
        <a href="{{ route('terms') }}" class="footer-link">Terms</a>
    </div>
</div>


            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled footer-links mt-2">
                    <li><a href="{{ route('customer.products') }}">Products</a></li>
                    <li><a href="{{ route('customer.orders') }}">My Orders</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="{{ route('customer.profile') }}">My Profile</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5>Support</h5>
                <p class="small mb-1">📧 support@ecommerceplatform.com</p>
                <p class="small mb-1">📞 +91 99999 88888</p>
                <p class="small mb-1">
                    📍 Sindhu Bhavan Road, Ahmedabad – 395002
                </p>
            </div>
        </div>

        <hr class="my-4">

        <div class="text-center footer-bottom">
            © {{ date('Y') }} Ecommerce Platform. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- AUTO LOGOUT --}}
@auth('customer')
<script>
    window.addEventListener('unload', function () {
        navigator.sendBeacon("{{ route('customer.auto.logout') }}");
    });
</script>
@endauth

</body>
</html>
```
# resource/view/layouts/navigation.blade.php
```php
<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center">
                {{-- LOGO --}}
                <a href="{{ route('products.index') }}"
                   class="text-lg font-bold text-gray-800">
                     Admin Panel
                </a>

                {{-- LINKS (DESKTOP) --}}
                <div class="hidden sm:flex sm:space-x-8 sm:ms-10">
                    <x-nav-link
        :href="route('products.index')"
        :active="request()->routeIs('products.*')">
        Products
    </x-nav-link>

    <x-nav-link
        :href="route('sizes.index')"
        :active="request()->routeIs('sizes.*')">
        Sizes
    </x-nav-link>

    <x-nav-link
        :href="route('colors.index')"
        :active="request()->routeIs('colors.*')">
        Colors
    </x-nav-link>

    <x-nav-link
        :href="route('categories.index')"
        :active="request()->routeIs('categories.*')">
        Categories
    </x-nav-link>

    <x-nav-link
        :href="route('discounts.index')"
        :active="request()->routeIs('discounts.*')">
        Discounts
    </x-nav-link>

    <x-nav-link
        :href="route('admin.orders.index')"
        :active="request()->routeIs('admin.orders.*')">
        ALLOrders
    </x-nav-link>

    {{-- 🔵 ADD PRODUCT BUTTON STYLE --}}
    <a href="{{ route('products.create') }}"
       class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
        + Add Product
    </a>

                    
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:text-gray-800 focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>

                            <svg class="h-4 w-4 fill-current"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-sm text-gray-500">
                            {{ Auth::user()->email }}
                        </div>

                       <x-dropdown-link :href="route('admin.dashboard')">
    Dashboard
</x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link
                :href="route('products.index')"
                :active="request()->routeIs('products.*')">
                Products
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('admin.orders.index')"
                :active="request()->routeIs('admin.orders.*')">
                Orders
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('discounts.index')"
                :active="request()->routeIs('discounts.*')">
                Discounts
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-red-600">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
```
# resource/view/layouts/app.blade.php
```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ecommerce Platform')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Premium Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
  /* ===============================
   GLOBAL BODY BACKGROUND
================================ */
body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    margin: 0;

    /* 🌟 Ecommerce Background Image */
    background: url('/images/photo.avif') center / cover no-repeat fixed;
}

/* 🔥 SOFT BLUR OVERLAY (NO WHITE LAYER) */
body::before {
    content: "";
    position: fixed;
    inset: 0;

    background: rgba(255, 255, 255, 0.18); /* very light tint */
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);

    z-index: -1;
}

/* ===============================
   AUTH CONTAINER
================================ */
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

/* ===============================
   AUTH CARD (GLASS EFFECT)
================================ */
.auth-box {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);

    border-radius: 26px;
    width: 100%;
    max-width: 920px;
    overflow: hidden;

    box-shadow:
        0 30px 80px rgba(0, 0, 0, 0.25),
        inset 0 0 0 1px rgba(255,255,255,0.3);
}

/* ===============================
   LEFT INFO PANEL
================================ */
.auth-left {
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.95),
        rgba(245,247,252,0.95)
    );
    padding: 64px;
}

.brand {
    font-size: 36px;
    font-weight: 800;
    color: #0d6efd;
    margin-bottom: 18px;
}

.auth-left p {
    color: #495057;
    font-size: 15px;
    line-height: 1.7;
}

.auth-left ul {
    padding-left: 18px;
    margin-top: 20px;
}

.auth-left li {
    margin-bottom: 10px;
    color: #343a40;
    font-size: 14px;
}

/* ===============================
   RIGHT FORM PANEL
================================ */
.auth-right {
    padding: 64px;
    background: rgba(255,255,255,0.98);
}

.auth-right h3 {
    font-weight: 700;
    margin-bottom: 6px;
}

.auth-right p {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 28px;
}

/* ===============================
   FORM ELEMENTS
================================ */
.form-control {
    border-radius: 14px;
    padding: 14px 16px;
    border: 1px solid #e1e5eb;
    font-size: 15px;
}

.form-control::placeholder {
    color: #adb5bd;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.12);
}

/* ===============================
   BUTTONS
================================ */
.btn {
    border-radius: 14px;
    padding: 14px;
    font-weight: 600;
    font-size: 15px;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0b5ed7, #0a58ca);
}

/* ===============================
   SMALL LINKS
================================ */
.small-link {
    font-size: 14px;
}

.small-link a {
    text-decoration: none;
    font-weight: 500;
    color: #0d6efd;
}

.small-link a:hover {
    text-decoration: underline;
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 768px) {
    .auth-left {
        display: none;
    }

    .auth-right {
        padding: 42px 32px;
    }

    .auth-box {
        border-radius: 22px;
    }
}

    </style>
</head>

<body>

<div class="auth-container">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

# Now Create Customer Profile Function and Adding Login customer name , email , profile photo ..
# Create customer profile controller and web route
# Controller
```php
@extends('layouts.customer')

@section('title','My Profile')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">

                <h3 class="mb-4 fw-bold text-center">My Profile</h3>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('customer.profile.update') }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- PROFILE IMAGE --}}
                    <div class="text-center mb-4">
                        <img src="{{ $customer->profile_image
                                ? asset('images/'.$customer->profile_image)
                                : asset('images/default-user.png') }}"
                             class="rounded-circle shadow"
                             width="150"
                             height="150"
                             style="object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Profile Image</label>
                        <input type="file"
                               name="profile_image"
                               class="form-control">
                    </div>

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ $customer->name }}"
                               required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email"
                               class="form-control"
                               value="{{ $customer->email }}"
                               disabled>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <button class="btn btn-primary w-100 mb-3">
                        Update Profile
                    </button>
                </form>

                <hr>

                {{-- QUICK ACTIONS --}}
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <a href="{{ route('customer.orders') }}"
                       class="btn btn-outline-primary w-100 w-md-auto">
                         My Orders
                    </a>

                    <a href="{{ route('cart.index') }}"
                       class="btn btn-outline-success w-100 w-md-auto">
                        View Cart
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
```
```php
Web route
use App\Http\Controllers\CustomerProfileController;
Route::middleware('auth:customer')->group(function () {
    Route::get('/my-profile', [CustomerProfileController::class, 'index'])
        ->name('customer.profile');

    Route::post('/my-profile', [CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
});
```
# Create profile.blade.php file in resource/view/customer folder
```php
@extends('layouts.customer')

@section('title','My Profile')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">

                <h3 class="mb-4 fw-bold text-center">My Profile</h3>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('customer.profile.update') }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- PROFILE IMAGE --}}
                    <div class="text-center mb-4">
                        <img src="{{ $customer->profile_image
                                ? asset('images/'.$customer->profile_image)
                                : asset('images/default-user.png') }}"
                             class="rounded-circle shadow"
                             width="150"
                             height="150"
                             style="object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Profile Image</label>
                        <input type="file"
                               name="profile_image"
                               class="form-control">
                    </div>

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ $customer->name }}"
                               required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email"
                               class="form-control"
                               value="{{ $customer->email }}"
                               disabled>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <button class="btn btn-primary w-100 mb-3">
                        Update Profile
                    </button>
                </form>

                <hr>

                {{-- QUICK ACTIONS --}}
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <a href="{{ route('customer.orders') }}"
                       class="btn btn-outline-primary w-100 w-md-auto">
                         My Orders
                    </a>

                    <a href="{{ route('cart.index') }}"
                       class="btn btn-outline-success w-100 w-md-auto">
                        View Cart
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
```
<img width="979" height="746" alt="image" src="https://github.com/user-attachments/assets/e0d1ef44-4986-423d-9b0b-c0b00ca15f03" />

 
# Create aboutus , privacy and terms condition page and this page link adding footer function
# Create Pagecontroller , web route and blade file
# Controller
```php
<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}
```
```php
Web.php route
use App\Http\Controllers\PageController;
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-conditions', [PageController::class, 'terms'])->name('terms');
```
# Create blade.php file for resource/view/pages folder
```php
resource/view/pages/about.blade.php
resource/view/pages/privacy.blade.php
resource/view/pages/terms.blade.php
```
# Create all customer order page in server side.
```php
Create web.route 
use App\Http\Controllers\AdminOrderController;
Route::middleware('auth:customer')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');
});

Route::post('/admin/orders/{order}/status', 
    [AdminOrderController::class, 'updateStatus']
)->name('admin.orders.status');

Route::get('/admin/orders', [AdminOrderController::class, 'index'])
    ->name('admin.orders.index');
Route::middleware('auth:customer')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');
});
```
# Create admincontroller 
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // ================================
    // ALL ORDERS (ADMIN)
    // ================================
 public function index(Request $request)
{
    $search = $request->search;
    $priceSort = $request->price_sort;
     $status     = $request->status; 

    $orders = Order::with([
        'items.product',
        'items.size',
        'items.color',
        'items.category',
        'address',
        'customer',
    ])
    ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {

            // 🔹 Order ID
            $q->where('id', 'like', "%{$search}%")

            // 🔹 Total price (DIRECT COLUMN)
            ->orWhere('total_price', 'like', "%{$search}%")

            // 🔹 Customer name
            ->orWhereHas('customer', function ($cq) use ($search) {
                $cq->where('name', 'like', "%{$search}%");
            })

            // 🔹 Address
            ->orWhereHas('address', function ($aq) use ($search) {
                $aq->where('address', 'like', "%{$search}%")
                   ->orWhere('city', 'like', "%{$search}%")
                   ->orWhere('state', 'like', "%{$search}%")
                   ->orWhere('pincode', 'like', "%{$search}%");
            })

            // 🔹 Product
            ->orWhereHas('items.product', function ($pq) use ($search) {
                $pq->where('name', 'like', "%{$search}%");
            })
             // 🔹 Order Date (created_at)
            ->orWhereDate('created_at', $search)
            ->orWhere('created_at', 'like', "%{$search}%")

            // 🔹 Payment type smart search
->orWhere(function ($pq) use ($search) {
    $keyword = strtolower($search);

    if (in_array($keyword, ['cash', 'cod', 'offline'])) {
        $pq->whereIn('payment_method', ['COD', 'Cash', 'CASH']);
    } elseif (in_array($keyword, ['online', 'paid'])) {
        $pq->whereIn('payment_method', ['ONLINE', 'Online']);
    } else {
        $pq->where('payment_method', 'like', "%{$search}%");
    }
})

            // 🔹 Size
            ->orWhereHas('items.size', function ($sq) use ($search) {
                $sq->where('size_name', 'like', "%{$search}%");
            })

            // 🔹 Color
            ->orWhereHas('items.color', function ($cq) use ($search) {
                $cq->where('color_name', 'like', "%{$search}%");
            })

            // 🔹 Category
            ->orWhereHas('items.category', function ($catq) use ($search) {
                $catq->where('category_name', 'like', "%{$search}%");
            });
        });
    })

      // 🟡 STATUS FILTER
    ->when($status, function ($query) use ($status) {
        $query->where('status', $status);
    })

    // 💰 PRICE SORT
    ->when($priceSort, function ($query) use ($priceSort) {
        if ($priceSort === 'high') {
            $query->orderBy('total_price', 'desc');
        } elseif ($priceSort === 'low') {
            $query->orderBy('total_price', 'asc');
        }
    }, function ($query) {
        $query->orderBy('created_at', 'desc');
    })

    ->paginate(10)
    ->withQueryString();

    return view('admin.orders.index', compact('orders', 'search', 'priceSort', 'status'));
}

public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,on_the_way,shipped,delivered',
    ]);

    $order->update([
        'status' => $request->status,
    ]);

    return back()->with('success', 'Order status updated');
}

}
```


# Create order blade.php file in resource/view/admin/order folder
```php
@extends('layouts.admin')

@section('content')

<style>
    .order-total-box {
        line-height: 1.4;
    }
    .order-total-box .total {
        font-size: 15px;
        font-weight: 700;
    }
    .order-total-box .subtotal {
        font-size: 13px;
        color: #6c757d;
    }
    .order-total-box .discount {
        font-size: 13px;
        color: #dc3545;
    }
    .order-details {
        background: #f9fafb;
    }
    .details-table th {
        background: #eef1f4;
        font-size: 13px;
    }
    .details-table td {
        font-size: 13px;
        vertical-align: middle;
    }
</style>

<h2 class="mb-3 fw-bold"> All Customer Orders</h2>

{{-- 🔍 SEARCH + FILTER --}}
<form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Search order, product, customer, address...">
    </div>

    <div class="col-md-2">
        <select name="price_sort" class="form-select">
            <option value="">Sort by Price</option>
            <option value="high" {{ request('price_sort')=='high'?'selected':'' }}>High → Low</option>
            <option value="low" {{ request('price_sort')=='low'?'selected':'' }}>Low → High</option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="on_the_way" {{ request('status')=='on_the_way'?'selected':'' }}>On The Way</option>
            <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
            <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Delivered</option>
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary">Apply</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Order</th>
            <th>Customer</th>
            <th>Address</th>
            <th>Total</th>
            <th>Payment</th>
            <th>Date</th>
            <th>Status</th>
            <th width="90">Action</th>
        </tr>
    </thead>

    <tbody>
    @forelse($orders as $order)

        {{-- SUMMARY --}}
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>
                <strong>{{ $order->id }}</strong>
            </td>

            <td>
                <strong>{{ $order->customer->name ?? 'Guest' }}</strong><br>
                <small class="text-muted">ID: {{ $order->customer_id }}</small>
            </td>

            <td style="max-width:260px">
                {{ $order->address->address ?? '' }},
                {{ $order->address->city ?? '' }},
                {{ $order->address->state ?? '' }} -
                {{ $order->address->pincode ?? '' }}
            </td>

            {{-- 💰 TOTAL --}}
            <td>
                <div class="order-total-box">
                    <div class="total">₹ {{ number_format($order->total_price, 2) }}</div>

                    @if($order->discount_amount > 0)
                        <div class="subtotal">
                            Subtotal: ₹ {{ number_format($order->subtotal, 2) }}
                        </div>
                        <div class="discount">
                            Discount: -₹ {{ number_format($order->discount_amount, 2) }}
                        </div>
                    @endif
                </div>
            </td>

            {{-- 💳 PAYMENT --}}
            <td>
                @if($order->payment_method === 'ONLINE')
                    <span class="badge bg-success">Online</span>
                @else
                    <span class="badge bg-warning text-dark">Cash</span>
                @endif
            </td>

            <td>
                {{ $order->created_at->format('d M Y') }}<br>
                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
            </td>

            {{-- 🔄 STATUS --}}
            <td>
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <select name="status"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                        <option value="on_the_way" {{ $order->status=='on_the_way'?'selected':'' }}>On The Way</option>
                        <option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
                        <option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
                    </select>
                </form>

                @php
                    $statusColors = [
                        'pending' => 'secondary',
                        'on_the_way' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                    ];
                @endphp

                <span class="badge bg-{{ $statusColors[$order->status] ?? 'dark' }} mt-1">
                    {{ ucfirst(str_replace('_',' ',$order->status)) }}
                </span>
            </td>

            <td>
                <button class="btn btn-sm btn-outline-primary view-order"
                        data-target="order-{{ $order->id }}">
                    View
                </button>
            </td>
        </tr>

        {{-- DETAILS --}}
        <tr class="order-details d-none" id="order-{{ $order->id }}">
            <td colspan="9">
                <table class="table table-sm table-bordered details-table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td>{{ $item->size->size_name ?? '-' }}</td>
                            <td>{{ $item->color->color_name ?? '-' }}</td>
                            <td>{{ $item->category->category_name ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹ {{ number_format($item->price, 2) }}</td>
                            <td class="text-danger">
                                -₹ {{ number_format($item->discount_amount, 2) }}
                            </td>
                            <td class="fw-bold text-success">
                                ₹ {{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="9" class="text-center text-muted">
                No orders found
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">
    ← Back to Products
</a>

<div class="d-flex justify-content-center mt-3">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>

@endsection
```
<img width="979" height="347" alt="image" src="https://github.com/user-attachments/assets/f7c89a07-0e60-4849-ab4c-b77b35ac1c0d" />

 
# Create  Client side Login Customer Myorder page and show all order details .
# Create web route , controller and order index file
```php
Route
use App\Http\Controllers\CustomerOrderController;
Route::middleware('auth:customer')->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');
});
```

# Controller for customer order
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $customerId = auth('customer')->id();

        $search    = $request->search;
        $priceSort = $request->price_sort; // high | low
        $status    = $request->status;     // pending | on_the_way | shipped | delivered

        $orders = Order::with([
                'items.product',
                'items.size',
                'items.color',
                'items.category',
                'address'
            ])
            ->where('customer_id', $customerId)

            //  SEARCH (ALL)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('total_price', 'like', "%{$search}%")
                      ->orWhereDate('created_at', $search)
                      ->orWhere('created_at', 'like', "%{$search}%")

                      // PRODUCT
                      ->orWhereHas('items.product', function ($pq) use ($search) {
                          $pq->where('name', 'like', "%{$search}%");
                      })

                      // SIZE
                      ->orWhereHas('items.size', function ($sq) use ($search) {
                          $sq->where('size_name', 'like', "%{$search}%");
                      })

                      // COLOR
                      ->orWhereHas('items.color', function ($cq) use ($search) {
                          $cq->where('color_name', 'like', "%{$search}%");
                      })

                      // CATEGORY
                      ->orWhereHas('items.category', function ($catq) use ($search) {
                          $catq->where('category_name', 'like', "%{$search}%");
                      });
                });
            })

            //  STATUS FILTER
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })

            //  PRICE SORT
            ->when($priceSort, function ($query) use ($priceSort) {
                if ($priceSort === 'high') {
                    $query->orderBy('total_price', 'desc');
                } elseif ($priceSort === 'low') {
                    $query->orderBy('total_price', 'asc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })

            ->paginate(10)
            ->withQueryString();

        return view('customer.orders.index', compact(
            'orders',
            'search',
            'priceSort',
            'status'
        ));
    }
}

```

# Create index.blade.php file in resource/view/customer/order folder
```php
@extends('layouts.customer')

@section('content')

<style>
    .order-total-box {
        line-height: 1.4;
    }
    .order-total-box .total {
        font-size: 16px;
        font-weight: 700;
    }
    .order-total-box .subtotal {
        font-size: 13px;
        color: #6c757d;
    }
    .order-total-box .discount {
        font-size: 13px;
        color: #dc3545;
    }
    .order-details {
        background: #f9fafb;
    }
    .details-table th {
        background: #eef1f4;
        font-size: 13px;
    }
    .details-table td {
        font-size: 13px;
        vertical-align: middle;
    }
</style>

<h3 class="mb-4 fw-bold"> My Orders</h3>

{{-- 🔍 SEARCH + FILTER --}}
<form method="GET" action="{{ route('customer.orders') }}" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Search product, size, color, date...">
    </div>

    <div class="col-md-3">
        <select name="price_sort" class="form-select">
            <option value="">Sort by Price</option>
            <option value="high" {{ request('price_sort')=='high'?'selected':'' }}>
                High → Low
            </option>
            <option value="low" {{ request('price_sort')=='low'?'selected':'' }}>
                Low → High
            </option>
        </select>
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="on_the_way" {{ request('status')=='on_the_way'?'selected':'' }}>On The Way</option>
            <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
            <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Delivered</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary">Apply</button>
        <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
            Reset
        </a>
    </div>
</form>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Order ID</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th width="90">Action</th>
        </tr>
    </thead>

    <tbody>
    @forelse($orders as $order)

        {{-- SUMMARY --}}
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>
                <strong>{{ $order->id }}</strong>
            </td>

            {{-- 💰 TOTAL --}}
            <td>
                <div class="order-total-box">
                    <div class="total">
                        ₹ {{ number_format($order->total_price, 2) }}
                    </div>

                    @if($order->discount_amount > 0)
                        <div class="subtotal">
                            Subtotal: ₹ {{ number_format($order->subtotal, 2) }}
                        </div>
                        <div class="discount">
                            Discount: -₹ {{ number_format($order->discount_amount, 2) }}
                        </div>
                    @endif
                </div>
            </td>

            {{-- 🎨 STATUS --}}
            @php
                $colors = [
                    'pending' => 'secondary',
                    'on_the_way' => 'info',
                    'shipped' => 'primary',
                    'delivered' => 'success',
                ];
            @endphp
            <td>
                <span class="badge bg-{{ $colors[$order->status] ?? 'dark' }}">
                    {{ ucfirst(str_replace('_',' ',$order->status)) }}
                </span>
            </td>

            <td>
                {{ $order->created_at->format('d M Y') }}<br>
                <small class="text-muted">
                    {{ $order->created_at->format('h:i A') }}
                </small>
            </td>

            <td>
                <button class="btn btn-sm btn-outline-primary view-order"
                        data-target="order-{{ $order->id }}">
                    View
                </button>
            </td>
        </tr>

        {{-- DETAILS --}}
        <tr class="order-details d-none" id="order-{{ $order->id }}">
            <td colspan="6">
                <table class="table table-sm table-bordered details-table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td>{{ $item->size->size_name ?? '-' }}</td>
                            <td>{{ $item->color->color_name ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹ {{ number_format($item->price, 2) }}</td>
                            <td class="text-danger">
                                -₹ {{ number_format($item->discount_amount, 2) }}
                            </td>
                            <td class="fw-bold text-success">
                                ₹ {{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    {{-- ORDER SUMMARY --}}
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Subtotal</strong></td>
                            <td colspan="2">₹ {{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end text-danger"><strong>Discount</strong></td>
                            <td colspan="2">-₹ {{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end fw-bold"><strong>Total Paid</strong></td>
                            <td colspan="2">₹ {{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="6" class="text-center text-muted">
                You have no orders yet
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<a href="{{ route('customer.products') }}" class="btn btn-outline-secondary mt-3">
    ← Continue Shopping
</a>

<div class="d-flex justify-content-center mt-3">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>

@endsection
```
<img width="628" height="234" alt="image" src="https://github.com/user-attachments/assets/6e0389b7-602d-4185-a47a-e642dc320201" />

# Now Your Ecommerce Store is ready to use for all function is easy to work all time :
 <img width="1504" height="879" alt="image" src="https://github.com/user-attachments/assets/90d06630-b415-4213-8bdf-e3554fd3d5a7" />
<img width="1038" height="900" alt="image" src="https://github.com/user-attachments/assets/74a27eed-75c6-4e5e-b924-7527d6b3407e" />


