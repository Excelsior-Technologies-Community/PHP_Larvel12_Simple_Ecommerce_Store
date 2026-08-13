<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderCancellation;
use Illuminate\Http\Request;

class OrderCancellationController extends Controller
{
    public function create(Order $order)
    {
        $customerId = auth('customer')->id();

        if ($order->customer_id !== $customerId) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'on_the_way'])) {
            return back()->with('error', 'This order cannot be cancelled');
        }

        return view('orders.cancel', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $customerId = auth('customer')->id();

        if ($order->customer_id !== $customerId) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string',
        ]);

        OrderCancellation::create([
            'order_id' => $order->id,
            'customer_id' => $customerId,
            'type' => 'cancel',
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        $order->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders')->with('success', 'Cancellation request submitted');
    }

    public function createReturn(Order $order)
    {
        $customerId = auth('customer')->id();

        if ($order->customer_id !== $customerId) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Only delivered orders can be returned');
        }

        return view('orders.return', compact('order'));
    }

    public function storeReturn(Request $request, Order $order)
    {
        $customerId = auth('customer')->id();

        if ($order->customer_id !== $customerId) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string',
        ]);

        OrderCancellation::create([
            'order_id' => $order->id,
            'customer_id' => $customerId,
            'type' => 'return',
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('customer.orders')->with('success', 'Return request submitted');
    }

    public function index()
    {
        $cancellations = OrderCancellation::with(['order', 'customer'])
            ->latest()
            ->paginate(10);

        return view('admin.cancellations.index', compact('cancellations'));
    }

    public function approve(OrderCancellation $cancellation)
    {
        $cancellation->update([
            'status' => 'approved',
            'admin_notes' => request('admin_notes'),
        ]);

        $cancellation->order->update([
            'status' => $cancellation->type === 'cancel' ? 'cancelled' : 'returned',
        ]);

        return back()->with('success', 'Request approved');
    }

    public function reject(OrderCancellation $cancellation)
    {
        $cancellation->update([
            'status' => 'rejected',
            'admin_notes' => request('admin_notes'),
        ]);

        if ($cancellation->type === 'cancel') {
            $cancellation->order->update(['status' => 'pending']);
        }

        return back()->with('success', 'Request rejected');
    }
}
