<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 40px;
            background: #f8f9fa;
        }

        .invoice {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #667eea;
            font-size: 32px;
            font-weight: 800;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-box {
            flex: 1;
        }

        .info-box h3 {
            color: #667eea;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .info-box p {
            color: #495057;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background: #667eea;
            color: white;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .totals {
            text-align: right;
        }

        .totals table {
            width: 300px;
            margin-left: auto;
        }

        .totals td {
            padding: 8px;
            border-bottom: none;
        }

        .totals .total-row {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
            border-top: 2px solid #667eea;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <h1>Ecommerce Platform</h1>
            <p>Invoice</p>
        </div>

        <div class="info-section">
            <div class="info-box">
                <h3>Bill To</h3>
                <p><strong>{{ $order->customer->name ?? 'Customer' }}</strong></p>
                <p>{{ $order->address->address ?? '' }}</p>
                <p>{{ $order->address->city ?? '' }}, {{ $order->address->state ?? '' }} - {{ $order->address->pincode ?? '' }}</p>
                <p>{{ $order->customer->email ?? '' }}</p>
            </div>
            <div class="info-box" style="text-align: right;">
                <h3>Invoice Details</h3>
                <p><strong>Invoice #:</strong> {{ $order->id }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p><strong>Payment:</strong> {{ $order->payment_method }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Product' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹ {{ number_format($item->price, 2) }}</td>
                        <td>₹ {{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>₹ {{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->discount_amount > 0)
                    <tr>
                        <td>Discount:</td>
                        <td>- ₹ {{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td>Total:</td>
                    <td>₹ {{ number_format($order->total_price, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Ecommerce Platform | support@ecommerceplatform.com</p>
        </div>
    </div>
</body>
</html>
