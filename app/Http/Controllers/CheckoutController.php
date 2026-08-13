<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Address;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Discount;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // ==============================
    // ✅ APPLY COUPON
    // ==============================
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $customerId = auth('customer')->id();
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return back()->withErrors(['coupon_code' => 'Invalid coupon code']);
        }

        if (!$coupon->isValid()) {
            return back()->withErrors(['coupon_code' => 'This coupon has expired']);
        }

        if (!$coupon->canBeUsedBy(auth('customer')->user())) {
            return back()->withErrors(['coupon_code' => 'You have already used this coupon']);
        }

        $cartItems = Cart::where('customer_id', $customerId)->get();
        $subtotal = $cartItems->sum(fn ($i) => $i->price * $i->quantity);

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return back()->withErrors(['coupon_code' => "Minimum order amount is {$coupon->min_order_amount}"]);
        }

        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = ($subtotal * $coupon->value) / 100;
            if ($coupon->max_discount_amount) {
                $discount = min($discount, $coupon->max_discount_amount);
            }
        } else {
            $discount = min($coupon->value, $subtotal);
        }

        session([
            'applied_coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount' => $discount,
            ]
        ]);

        return back()->with('success', "Coupon '{$coupon->code}' applied! You saved ₹{$discount}");
    }

    // ==============================
    // ✅ REMOVE COUPON
    // ==============================
    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return back()->with('success', 'Coupon removed');
    }

    // ==============================
    // ✅ PLACE ORDER (NO PAYMENT)
    // ==============================
    public function placeOrder(Request $request)
    {
        $customerId = auth('customer')->id();

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $discountAmount = 0;
        $couponId = null;

        // Check for coupon
        if (session('applied_coupon')) {
            $coupon = Coupon::find(session('applied_coupon.id'));
            if ($coupon && $coupon->isValid()) {
                $discountAmount += session('applied_coupon.discount');
                $couponId = $coupon->id;
                $coupon->increment('used_count');
            }
            session()->forget('applied_coupon');
        }

        // Check for auto discounts
        $cartItems = Cart::with('product')->where('customer_id', $customerId)->get();
        $subtotal = $cartItems->sum(fn ($i) => $i->price * $i->quantity);

        $today = Carbon::today();
        $autoDiscount = Discount::where('apply_to', 'all_products')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->first();

        if ($autoDiscount) {
            if ($autoDiscount->apply_on === 'percentage') {
                $discountAmount += ($subtotal * $autoDiscount->value) / 100;
            } else {
                $discountAmount += $autoDiscount->value;
            }
        }

        $address = Address::where('id', $request->address_id)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.products')
                ->with('error', 'Cart is empty');
        }

        $finalTotal = max($subtotal - $discountAmount, 0);

        $order = Order::create([
            'customer_id'     => $customerId,
            'address_id'      => $address->id,
            'coupon_id'       => $couponId,
            'subtotal'        => $subtotal,
            'discount_amount' => $discountAmount,
            'total_price'     => $finalTotal,
            'payment_method'  => 'COD',
            'payment_status'  => 'pending',
            'status'          => 'pending',
        ]);

        foreach ($cartItems as $item) {
            $itemSubtotal = $item->price * $item->quantity;
            $itemDiscount = $subtotal > 0 ? round(($itemSubtotal / $subtotal) * $discountAmount, 2) : 0;

            OrderItem::create([
                'order_id'        => $order->id,
                'product_id'      => $item->product_id,
                'size_id'         => $item->size_id,
                'color_id'        => $item->color_id,
                'category_id'     => $item->category_id,
                'quantity'        => $item->quantity,
                'price'           => $item->price,
                'discount_amount' => $itemDiscount,
                'total'           => $itemSubtotal - $itemDiscount,
            ]);

            $product = Product::find($item->product_id);
            if ($product) {
                if ($product->variants()->exists()) {
                    $variant = ProductVariant::where('product_id', $item->product_id)
                        ->where('size_id', $item->size_id)
                        ->where('color_id', $item->color_id)
                        ->where('category_id', $item->category_id)
                        ->first();

                    if ($variant) {
                        $previousStock = $variant->stock_quantity;
                        $variant->decrement('stock_quantity', $item->quantity);

                        StockHistory::create([
                            'product_id' => $item->product_id,
                            'variant_id' => $variant->id,
                            'type' => 'out',
                            'quantity' => $item->quantity,
                            'previous_stock' => $previousStock,
                            'new_stock' => $variant->fresh()->stock_quantity,
                            'reference_type' => Order::class,
                            'reference_id' => $order->id,
                            'notes' => 'Order #' . $order->id,
                        ]);
                    }
                } else {
                    $previousStock = $product->stock_quantity;
                    $product->decrement('stock_quantity', $item->quantity);

                    StockHistory::create([
                        'product_id' => $item->product_id,
                        'type' => 'out',
                        'quantity' => $item->quantity,
                        'previous_stock' => $previousStock,
                        'new_stock' => $product->fresh()->stock_quantity,
                        'reference_type' => Order::class,
                        'reference_id' => $order->id,
                        'notes' => 'Order #' . $order->id,
                    ]);
                }
            }
        }
        Cart::where('customer_id', $customerId)->delete();

        return redirect()->route('order.success');
    }
}
