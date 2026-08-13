<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'details',
        'price',
        'image',
        'sizes',
        'colors',
        'categories',
        'status',
        'sku',
        'stock_quantity',
        'low_stock_threshold',
        'track_stock',
        'allow_backorder',
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'categories' => 'array',
        'track_stock' => 'boolean',
        'allow_backorder' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants()
    {
        return $this->variants()->where('is_active', true);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('is_approved', true);
    }

    public function averageRating(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function reviewCount(): int
    {
        return $this->approvedReviews()->count();
    }

    public function isInStock(): bool
    {
        if (!$this->track_stock) {
            return true;
        }

        if ($this->allow_backorder) {
            return true;
        }

        if ($this->variants()->exists()) {
            return $this->activeVariants()->where('stock_quantity', '>', 0)->exists();
        }

        return $this->stock_quantity > 0;
    }

    public function isLowStock(): bool
    {
        if (!$this->track_stock) {
            return false;
        }

        if ($this->variants()->exists()) {
            return $this->activeVariants()
                ->where('stock_quantity', '<=', \DB::raw('low_stock_threshold'))
                ->where('stock_quantity', '>', 0)
                ->exists();
        }

        return $this->stock_quantity <= $this->low_stock_threshold && $this->stock_quantity > 0;
    }

    public function getEffectivePrice(?Size $size = null, ?Color $color = null, ?Category $category = null): float
    {
        if ($size || $color || $category) {
            $variant = $this->variants()
                ->when($size, fn($q) => $q->where('size_id', $size->id))
                ->when($color, fn($q) => $q->where('color_id', $color->id))
                ->when($category, fn($q) => $q->where('category_id', $category->id))
                ->where('is_active', true)
                ->first();

            if ($variant && $variant->price) {
                return (float) $variant->price;
            }
        }

        return (float) $this->price;
    }

    public function getEffectiveStock(?Size $size = null, ?Color $color = null, ?Category $category = null): int
    {
        if ($this->variants()->exists() && ($size || $color || $category)) {
            $variant = $this->variants()
                ->when($size, fn($q) => $q->where('size_id', $size->id))
                ->when($color, fn($q) => $q->where('color_id', $color->id))
                ->when($category, fn($q) => $q->where('category_id', $category->id))
                ->where('is_active', true)
                ->first();

            return $variant ? $variant->stock_quantity : 0;
        }

        return $this->stock_quantity;
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
              ->orWhere('allow_backorder', true)
              ->orWhere(function ($q2) {
                  $q2->whereHas('activeVariants', function ($q3) {
                      $q3->where('stock_quantity', '>', 0);
                  });
              })
              ->orWhere('stock_quantity', '>', 0);
        });
    }
}
