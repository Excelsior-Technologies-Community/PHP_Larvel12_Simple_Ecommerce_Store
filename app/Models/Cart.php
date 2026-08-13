<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Customer;

class Cart extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'size_id',
        'color_id',
        'category_id',
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
