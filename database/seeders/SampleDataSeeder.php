<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use App\Models\Coupon;
use App\Models\Banner;
use App\Models\FeaturedProduct;
use App\Models\CmsPage;
use App\Models\Staff;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Discounts
        Discount::firstOrCreate([
            'code' => 'SUMMER20',
        ], [
            'title' => 'Summer Sale',
            'apply_on' => 'percentage',
            'value' => 20,
            'apply_to' => 'all_products',
            'is_active' => true,
        ]);

        Discount::firstOrCreate([
            'code' => 'FLAT500',
        ], [
            'title' => 'Flat ₹500 Off',
            'apply_on' => 'fixed',
            'value' => 500,
            'apply_to' => 'specific_product',
            'product_ids' => json_encode([1, 2, 3]),
            'is_active' => true,
        ]);

        // Coupons
        Coupon::firstOrCreate([
            'code' => 'WELCOME10',
        ], [
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 500,
            'max_discount_amount' => 200,
            'usage_limit' => 100,
            'used_count' => 0,
            'is_active' => true,
            'starts_at' => now()->subDays(5),
            'expires_at' => now()->addDays(30),
        ]);

        Coupon::firstOrCreate([
            'code' => 'SPECIAL100',
        ], [
            'type' => 'fixed',
            'value' => 100,
            'min_order_amount' => 1000,
            'max_discount_amount' => 100,
            'usage_limit' => 50,
            'used_count' => 0,
            'is_active' => true,
            'starts_at' => now()->subDays(2),
            'expires_at' => now()->addDays(60),
        ]);

        // Banners
        Banner::firstOrCreate([
            'title' => 'Summer Collection',
        ], [
            'description' => 'Up to 50% off',
            'image' => 'https://picsum.photos/seed/banner1/1200/400',
            'link' => '/customer/products',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::firstOrCreate([
            'title' => 'New Arrivals',
        ], [
            'description' => 'Check out the latest products',
            'image' => 'https://picsum.photos/seed/banner2/1200/400',
            'link' => '/customer/products',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Featured Products
        $products = Product::take(5)->get();
        foreach ($products as $index => $product) {
            FeaturedProduct::firstOrCreate([
                'product_id' => $product->id,
                'type' => 'featured',
            ], [
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }

        // CMS Pages
        CmsPage::firstOrCreate([
            'slug' => 'about-us',
        ], [
            'title' => 'About Us',
            'content' => 'We are a leading ecommerce platform providing quality products at great prices.',
            'is_active' => true,
        ]);

        CmsPage::firstOrCreate([
            'slug' => 'privacy-policy',
        ], [
            'title' => 'Privacy Policy',
            'content' => 'Your privacy is important to us. This policy explains how we collect and use your data.',
            'is_active' => true,
        ]);

        CmsPage::firstOrCreate([
            'slug' => 'terms-conditions',
        ], [
            'title' => 'Terms & Conditions',
            'content' => 'By using our service, you agree to these terms and conditions.',
            'is_active' => true,
        ]);

        // Staff
        $staffUser = \App\Models\User::firstOrCreate(
            ['email' => 'staff@example.com'],
            ['name' => 'John Doe', 'password' => bcrypt('password'), 'role_id' => 2, 'is_active' => true]
        );
        Staff::firstOrCreate(
            ['user_id' => $staffUser->id],
            ['phone' => '9876543210', 'address' => '456 Office Street', 'designation' => 'Manager', 'is_active' => true]
        );

        // Product Variants
        $sizes = \App\Models\Size::all();
        $colors = \App\Models\Color::all();
        $categories = \App\Models\Category::all();

        foreach (Product::take(10)->get() as $product) {
            foreach ($sizes->take(2) as $size) {
                foreach ($colors->take(2) as $color) {
                    foreach ($categories->take(1) as $category) {
                        ProductVariant::firstOrCreate([
                            'product_id' => $product->id,
                            'size_id' => $size->id,
                            'color_id' => $color->id,
                            'category_id' => $category->id,
                        ], [
                            'price' => $product->price + rand(0, 200),
                            'stock_quantity' => rand(5, 50),
                            'is_active' => true,
                        ]);
                    }
                }
            }
        }

        // Stock History
        foreach (Product::take(5)->get() as $product) {
            StockHistory::firstOrCreate([
                'product_id' => $product->id,
                'type' => 'in',
                'notes' => 'Initial stock',
            ], [
                'quantity' => rand(10, 50),
                'previous_stock' => 0,
                'new_stock' => rand(10, 50),
            ]);
        }

        // Customers
        $customer = Customer::firstOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Demo Customer', 'password' => bcrypt('password')]
        );

        // Addresses
        Address::firstOrCreate([
            'customer_id' => $customer->id,
            'address' => '123 Main Street',
            'city' => 'Mumbai',
        ], [
            'full_name' => 'Demo Customer',
            'mobile' => '9876543210',
            'state' => 'Maharashtra',
            'pincode' => '400001',
            'type' => 'shipping',
            'is_default' => true,
        ]);

        // Cart items
        foreach (Product::take(3)->get() as $product) {
            $variant = ProductVariant::where('product_id', $product->id)->first();
            if ($variant) {
                Cart::firstOrCreate([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'size_id' => $variant->size_id,
                    'color_id' => $variant->color_id,
                ], [
                    'category_id' => $variant->category_id,
                    'quantity' => rand(1, 3),
                    'price' => $product->price,
                ]);
            }
        }

        // Orders
        foreach (Product::take(3)->get() as $product) {
            $variant = ProductVariant::where('product_id', $product->id)->first();
            if ($variant) {
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'address_id' => 1,
                    'subtotal' => $product->price,
                    'discount_amount' => 0,
                    'total_price' => $product->price,
                    'payment_method' => 'COD',
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'tracking_number' => 'TRK' . rand(10000, 99999),
                    'courier_name' => 'Delhivery',
                    'tracking_url' => 'https://track.example.com/' . rand(10000, 99999),
                ]);

                OrderItem::firstOrCreate([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ], [
                    'size_id' => $variant->size_id,
                    'color_id' => $variant->color_id,
                    'category_id' => $variant->category_id,
                    'quantity' => 1,
                    'price' => $product->price,
                    'total' => $product->price,
                ]);
            }
        }

        // Wishlists
        foreach (Product::take(5)->get() as $product) {
            Wishlist::firstOrCreate([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
            ]);
        }

        // Reviews
        foreach (Product::take(5)->get() as $product) {
            Review::firstOrCreate([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
            ], [
                'rating' => rand(3, 5),
                'comment' => 'Great product! Highly recommended.',
                'is_approved' => true,
            ]);
        }

        // Update product images to online placeholders
        foreach (Product::all() as $index => $product) {
            $product->update([
                'image' => 'https://picsum.photos/seed/product' . ($index + 1) . '/400/400',
            ]);
        }
    }
}
