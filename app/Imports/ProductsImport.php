<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $sizes = array_filter(explode(',', $row['sizes'] ?? ''));
            $colors = array_filter(explode(',', $row['colors'] ?? ''));
            $categories = array_filter(explode(',', $row['categories'] ?? ''));

            $sizeIds = Size::whereIn('size_name', $sizes)->pluck('id')->toArray();
            $colorIds = Color::whereIn('color_name', $colors)->pluck('id')->toArray();
            $categoryIds = Category::whereIn('category_name', $categories)->pluck('id')->toArray();

            Product::create([
                'name' => $row['name'],
                'details' => $row['details'] ?? '',
                'price' => $row['price'] ?? 0,
                'sizes' => $sizeIds,
                'colors' => $colorIds,
                'categories' => $categoryIds,
                'status' => $row['status'] ?? 'active',
                'sku' => $row['sku'] ?? null,
                'stock_quantity' => $row['stock_quantity'] ?? 0,
                'low_stock_threshold' => $row['low_stock_threshold'] ?? 5,
            ]);
        }
    }
}
