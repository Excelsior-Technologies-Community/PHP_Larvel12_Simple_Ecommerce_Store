<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'subtotal',
        'discount_amount',
        'total_price',
        'payment_method',
        'payment_status',
        'status',
        'tracking_number',
        'courier_name',
        'tracking_url',
        'tracking_history',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'tracking_history' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function cancellation(): HasMany
    {
        return $this->hasMany(OrderCancellation::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'on_the_way' => 'bg-info',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getTrackingTimelineAttribute(): array
    {
        $timeline = [];
        
        $timeline[] = [
            'status' => 'Order Placed',
            'date' => $this->created_at,
            'completed' => true,
            'active' => false,
        ];

        if ($this->shipped_at) {
            $timeline[] = [
                'status' => 'Shipped',
                'date' => $this->shipped_at,
                'completed' => true,
                'active' => in_array($this->status, ['shipped', 'delivered']),
            ];
        }

        if ($this->delivered_at) {
            $timeline[] = [
                'status' => 'Delivered',
                'date' => $this->delivered_at,
                'completed' => true,
                'active' => $this->status === 'delivered',
            ];
        }

        if ($this->tracking_history && is_array($this->tracking_history)) {
            foreach ($this->tracking_history as $event) {
                $timeline[] = [
                    'status' => $event['status'] ?? 'Unknown',
                    'date' => $event['date'] ?? now(),
                    'completed' => true,
                    'active' => false,
                ];
            }
        }

        return $timeline;
    }
}
