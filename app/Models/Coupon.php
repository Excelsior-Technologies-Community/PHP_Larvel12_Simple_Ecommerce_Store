<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'usage_limit_per_user',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsedBy(Customer $customer): bool
    {
        if (!$this->usage_limit_per_user) {
            return true;
        }

        $usedByUser = $this->usedByCustomer($customer);
        return $usedByUser < $this->usage_limit_per_user;
    }

    public function usedByCustomer(Customer $customer): int
    {
        return Order::where('customer_id', $customer->id)
            ->where('coupon_id', $this->id)
            ->where('payment_status', 'paid')
            ->count();
    }
}
