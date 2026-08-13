<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Product;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::where('status', 'active')->with('variants')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Name', 'Details', 'Price', 'SKU', 'Stock Quantity',
            'Low Stock Threshold', 'Sizes', 'Colors', 'Categories', 'Status', 'Created At'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->details,
            $product->price,
            $product->sku,
            $product->stock_quantity,
            $product->low_stock_threshold,
            implode(', ', $product->sizes ?? []),
            implode(', ', $product->colors ?? []),
            implode(', ', $product->categories ?? []),
            $product->status,
            $product->created_at->format('Y-m-d H:i'),
        ];
    }
}
