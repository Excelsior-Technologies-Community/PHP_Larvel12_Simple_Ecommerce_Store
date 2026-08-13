<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderCancellationController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FeaturedProductController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CustomerManagementController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\BulkOrderController;
use App\Http\Controllers\CustomerForgotPasswordController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\DiscountController;
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $banners = \App\Models\Banner::where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        })
        ->orderBy('sort_order')
        ->get();

    $featuredProducts = \App\Models\FeaturedProduct::where('is_active', true)
        ->where(function ($q) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
        })
        ->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        })
        ->with('product')
        ->orderBy('sort_order')
        ->get();

    $products = \App\Models\Product::where('status', 'active')
        ->inStock()
        ->latest()
        ->take(8)
        ->get();

    return view('welcome', compact('banners', 'featuredProducts', 'products'));
})->name('home');

// CMS Pages
Route::get('/{slug}', [CmsPageController::class, 'show'])
    ->where('slug', 'about-us|privacy-policy|terms-conditions')
    ->name('cms.page');

/*
|--------------------------------------------------------------------------
| CUSTOMER AUTH
|--------------------------------------------------------------------------
*/

Route::get('/customer/register', [CustomerAuthController::class, 'register'])
    ->name('customer.register');

Route::post('/customer/register', [CustomerAuthController::class, 'registerPost'])
    ->name('customer.register.post');

Route::get('/customer/login', [CustomerAuthController::class, 'login'])
    ->name('customer.login');

Route::post('/customer/login', [CustomerAuthController::class, 'loginPost'])
    ->name('customer.login.post');

// Customer Forgot Password
Route::get('/customer/forgot-password', [CustomerForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('customer.password.request');

Route::post('/customer/forgot-password', [CustomerForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('customer.password.email');

Route::get('/customer/reset-password/{token}', [CustomerForgotPasswordController::class, 'showResetForm'])
    ->name('customer.password.reset');

Route::post('/customer/reset-password', [CustomerForgotPasswordController::class, 'reset'])
    ->name('customer.password.update');

// Customer Logout
Route::middleware('auth:customer')->group(function () {
    Route::get('/customer/logout', [CustomerAuthController::class, 'logout'])
        ->name('customer.logout');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER PRODUCTS (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/customer/products', [CustomerController::class, 'index'])
    ->name('customer.products');

// Compare Products
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/add/{product}', [CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/remove/{product}', [CompareController::class, 'remove'])->name('compare.remove');
Route::delete('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');

/*
|--------------------------------------------------------------------------
| AUTO LOGOUT ON TAB / WINDOW CLOSE (CUSTOMER)
|--------------------------------------------------------------------------
*/

Route::post('/customer/auto-logout', function () {
    if (auth('customer')->check()) {
       Auth::guard('customer')->logout();
        session()->invalidate();
        session()->regenerateToken();
    }
    return response()->noContent();
})->name('customer.auto.logout');

/*
|--------------------------------------------------------------------------
| WISHLIST (CUSTOMER ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

/*
|--------------------------------------------------------------------------
| REVIEWS (CUSTOMER ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/reviews/create/{order}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

/*
|--------------------------------------------------------------------------
| CART (CUSTOMER ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add', [CartController::class, 'store'])
        ->name('cart.add');

    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])
        ->name('cart.remove');
});

Route::post('/cart/update-quantity/{cart}',
    [CartController::class, 'updateQuantity']
)->name('cart.update.quantity');

/*
|--------------------------------------------------------------------------
| CHECKOUT FLOW (CUSTOMER ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    // Address Management
    Route::get('/addresses', [AddressController::class, 'index'])
        ->name('addresses.index');

    Route::post('/addresses', [AddressController::class, 'store'])
        ->name('addresses.store');

    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])
        ->name('addresses.edit');

    Route::put('/addresses/{address}', [AddressController::class, 'update'])
        ->name('addresses.update');

    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])
        ->name('addresses.destroy');

    Route::post('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])
        ->name('addresses.setDefault');

    // Checkout Address Selection
    Route::match(['GET', 'POST'], '/checkout/address', [AddressController::class, 'saveForCheckout'])
        ->name('checkout.saveAddress');

    // Place Order (Direct, No Payment)
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('place.order');

    // Success
    Route::get('/order-success', function () {
        return view('checkout.success');
    })->name('order.success');

    // Coupon Apply
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])
        ->name('checkout.applyCoupon');

    Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])
        ->name('checkout.removeCoupon');

    // Order Cancellation / Return
    Route::get('/orders/{order}/cancel', [OrderCancellationController::class, 'create'])
        ->name('orders.cancel.create');

    Route::post('/orders/{order}/cancel', [OrderCancellationController::class, 'store'])
        ->name('orders.cancel.store');

    Route::get('/orders/{order}/return', [OrderCancellationController::class, 'createReturn'])
        ->name('orders.return.create');

    Route::post('/orders/{order}/return', [OrderCancellationController::class, 'storeReturn'])
        ->name('orders.return.store');

    // Invoice Download
    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'download'])
        ->name('orders.invoice');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER PROFILE & ORDERS
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {
    Route::get('/my-profile', [CustomerProfileController::class, 'index'])
        ->name('customer.profile');

    Route::match(['GET', 'POST', 'PUT'], '/my-profile', [CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');

    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('customer.orders');

    Route::get('/my-orders/{order}', [CustomerOrderController::class, 'show'])
        ->name('customer.orders.show');
});

/*
|--------------------------------------------------------------------------
| ADMIN / BACKEND (DEFAULT AUTH + RBAC)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin.role'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])
        ->name('analytics');

    // Roles
    Route::resource('roles', RoleController::class);

    // Staff
    Route::resource('staff', StaffController::class);

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Product Variants
    Route::get('/products/{product}/variants', [ProductVariantController::class, 'index'])->name('products.variants.index');
    Route::get('/products/{product}/variants/create', [ProductVariantController::class, 'create'])->name('products.variants.create');
    Route::post('/products/{product}/variants', [ProductVariantController::class, 'store'])->name('products.variants.store');
    Route::get('/variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('products.variants.edit');
    Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('products.variants.update');
    Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('products.variants.destroy');

    // Stock Management
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
    Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Colors
    Route::resource('colors', ColorController::class);

    // Sizes
    Route::get('/sizes', [SizeController::class, 'index'])->name('sizes.index');
    Route::get('/sizes/create', [SizeController::class, 'create'])->name('sizes.create');
    Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store');
    Route::get('/sizes/{size}/edit', [SizeController::class, 'edit'])->name('sizes.edit');
    Route::put('/sizes/{size}', [SizeController::class, 'update'])->name('sizes.update');
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');

    // Discounts
    Route::resource('discounts', DiscountController::class);

    // Coupons
    Route::resource('coupons', CouponController::class);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/bulk-status', [BulkOrderController::class, 'bulkStatus'])->name('orders.bulkStatus');
    Route::post('/orders/bulk-invoice', [BulkOrderController::class, 'bulkInvoice'])->name('orders.bulkInvoice');

    // Order Cancellations / Returns
    Route::get('/cancellations', [OrderCancellationController::class, 'index'])->name('cancellations.index');
    Route::post('/cancellations/{cancellation}/approve', [OrderCancellationController::class, 'approve'])->name('cancellations.approve');
    Route::post('/cancellations/{cancellation}/reject', [OrderCancellationController::class, 'reject'])->name('cancellations.reject');

    // Customers
    Route::get('/customers', [CustomerManagementController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerManagementController::class, 'show'])->name('customers.show');
    Route::put('/customers/{customer}', [CustomerManagementController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerManagementController::class, 'destroy'])->name('customers.destroy');

    // Banners
    Route::resource('banners', BannerController::class);

    // Featured Products
    Route::resource('featured-products', FeaturedProductController::class);

    // CMS Pages
    Route::resource('cms-pages', CmsPageController::class);

    // Product Import/Export
    Route::get('/products/export', [ImportExportController::class, 'export'])->name('products.export');
    Route::post('/products/import', [ImportExportController::class, 'import'])->name('products.import');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
