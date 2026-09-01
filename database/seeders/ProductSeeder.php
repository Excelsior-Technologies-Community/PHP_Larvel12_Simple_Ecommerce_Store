<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = [
            'Men',
            'Women',
            'Kids',
            'Electronics',
            'Accessories',
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate([
                'category_name' => $categoryName,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Sizes
        |--------------------------------------------------------------------------
        */

        $sizes = [
            'S',
            'M',
            'L',
            'XL',
        ];

        foreach ($sizes as $sizeName) {
            Size::firstOrCreate([
                'size_name' => $sizeName,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Colors
        |--------------------------------------------------------------------------
        */

        $colors = [
            'Black',
            'White',
            'Red',
            'Blue',
            'Green',
        ];

        foreach ($colors as $colorName) {
            Color::firstOrCreate([
                'color_name' => $colorName,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Product Data
        |--------------------------------------------------------------------------
        */

        $products = [
            [
                'name' => 'Classic Black T-Shirt',
                'details' => 'Comfortable classic black cotton t-shirt for everyday use.',
                'price' => 499,
                'image' => 'https://picsum.photos/seed/product1/400/400',
            ],
            [
                'name' => 'Women Casual Dress',
                'details' => 'Elegant and comfortable casual dress for women.',
                'price' => 999,
                'image' => 'https://picsum.photos/seed/product2/400/400',
            ],
            [
                'name' => 'Kids Cotton Shirt',
                'details' => 'Soft and comfortable cotton shirt for kids.',
                'price' => 599,
                'image' => 'https://picsum.photos/seed/product3/400/400',
            ],
            [
                'name' => 'Wireless Headphones',
                'details' => 'High-quality wireless headphones with clear sound.',
                'price' => 1499,
                'image' => 'https://picsum.photos/seed/product4/400/400',
            ],
            [
                'name' => 'Smart Watch',
                'details' => 'Modern smart watch with multiple useful features.',
                'price' => 2499,
                'image' => 'https://picsum.photos/seed/product5/400/400',
            ],
            [
                'name' => 'Leather Wallet',
                'details' => 'Premium stylish leather wallet for everyday use.',
                'price' => 799,
                'image' => 'https://picsum.photos/seed/product6/400/400',
            ],
            [
                'name' => 'Running Shoes',
                'details' => 'Lightweight and comfortable running shoes.',
                'price' => 1799,
                'image' => 'https://picsum.photos/seed/product7/400/400',
            ],
            [
                'name' => 'Women Handbag',
                'details' => 'Stylish handbag suitable for everyday use.',
                'price' => 1299,
                'image' => 'https://picsum.photos/seed/product8/400/400',
            ],
            [
                'name' => 'Kids Sports Shoes',
                'details' => 'Comfortable sports shoes designed for kids.',
                'price' => 899,
                'image' => 'https://picsum.photos/seed/product9/400/400',
            ],
            [
                'name' => 'Bluetooth Speaker',
                'details' => 'Portable Bluetooth speaker with powerful sound.',
                'price' => 1199,
                'image' => 'https://picsum.photos/seed/product10/400/400',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Create Products
        |--------------------------------------------------------------------------
        */

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                [
                    'name' => $productData['name'],
                ],
                $productData
            );

            /*
            |--------------------------------------------------------------------------
            | Add Product Categories
            |--------------------------------------------------------------------------
            */

            $categoryNames = [
                'Men' => ['Classic Black T-Shirt', 'Running Shoes'],
                'Women' => ['Women Casual Dress', 'Women Handbag'],
                'Kids' => ['Kids Cotton Shirt', 'Kids Sports Shoes'],
                'Electronics' => ['Wireless Headphones', 'Smart Watch', 'Bluetooth Speaker'],
                'Accessories' => ['Leather Wallet'],
            ];

            $productCategory = null;

            foreach ($categoryNames as $categoryName => $productNames) {
                if (in_array($product->name, $productNames)) {
                    $productCategory = Category::where(
                        'category_name',
                        $categoryName
                    )->first();

                    break;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Product JSON Fields
            |--------------------------------------------------------------------------
            */

            $product->update([
                'categories' => $productCategory
                    ? [$productCategory->id]
                    : [],

                'sizes' => Size::pluck('id')->take(4)->values()->toArray(),

                'colors' => Color::pluck('id')->take(5)->values()->toArray(),

                'status' => 'active',

                'sku' => 'SKU-' . str_pad($product->id, 5, '0', STR_PAD_LEFT),

                'stock_quantity' => rand(10, 50),

                'low_stock_threshold' => 5,

                'track_stock' => true,

                'allow_backorder' => false,
            ]);
        }
    }
}