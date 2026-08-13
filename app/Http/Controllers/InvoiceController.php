<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        $order->load(['items.product', 'customer', 'address']);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));

        return $pdf->download("invoice_{$order->id}.pdf");
    }
}
