<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BulkOrderController extends Controller
{
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,on_the_way,shipped,delivered',
        ]);

        Order::whereIn('id', $request->order_ids)->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully');
    }

    public function bulkInvoice(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $orders = Order::with(['items.product', 'customer', 'address'])
            ->whereIn('id', $request->order_ids)
            ->get();

        $pdf = Pdf::loadView('pdf.invoice', compact('orders'));

        return $pdf->download('invoices_' . now()->format('Y-m-d') . '.pdf');
    }
}
