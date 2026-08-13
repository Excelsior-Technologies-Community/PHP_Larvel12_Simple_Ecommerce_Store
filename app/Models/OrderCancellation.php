<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCancellation extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'type',
        'reason',
        'admin_notes',
        'status',
        'refund_amount',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
