<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Address extends Model
{
    protected $fillable = [
        'customer_id',
        'full_name',
        'mobile',
        'address',
        'nearby',
        'city',
        'state',
        'pincode',
        'type',
        'is_default',
        'label',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
