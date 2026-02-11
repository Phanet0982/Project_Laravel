<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Receipt #{{ $sale->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
            background: white;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
        }
        .receipt-header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .receipt-header p {
            font-size: 10px;
            color: #666;
        }
        .receipt-info {
            margin-bottom: 15px;
        }
        .receipt-info p {
            margin-bottom: 3px;
        }
        .receipt-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .receipt-items th,
        .receipt-items td {
            text-align: left;
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
        }
        .receipt-items th {
            border-bottom: 2px solid #000;
        }
        .receipt-items .text-right {
            text-align: right;
        }
        .receipt-totals {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 15px;
        }
        .receipt-totals p {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .receipt-totals .total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px dashed #000;
        }
        .receipt-footer p {
            margin-bottom: 5px;
        }
        .barcode {
            text-align: center;
            margin: 15px 0;
            font-size: 14px;
            letter-spacing: 3px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <h1>SALE RECEIPT</h1>
        <p>Sale Management System</p>
        <p>{{ $sale->created_at->format('M d, Y h:i A') }}</p>
    </div>

    <div class="receipt-info">
        <p><strong>Receipt #:</strong> {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
        <p><strong>Cashier:</strong> {{ $sale->user->name ?? 'Staff' }}</p>
        <p><strong>Payment:</strong> {{ $sale->payment_method ?? 'Cash' }}</p>
    </div>

    <table class="receipt-items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleItems as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Product' }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->price, 2) }}</td>
                <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="receipt-totals">
        <p>
            <span>Subtotal:</span>
            <span>${{ number_format($sale->subtotal ?? $sale->total_amount, 2) }}</span>
        </p>
        @if($sale->tax_amount)
        <p>
            <span>Tax (10%):</span>
            <span>${{ number_format($sale->tax_amount, 2) }}</span>
        </p>
        @endif
        @if($sale->discount_amount)
        <p>
            <span>Discount:</span>
            <span>-${{ number_format($sale->discount_amount, 2) }}</span>
        </p>
        @endif
        <p class="total">
            <span>TOTAL:</span>
            <span>${{ number_format($sale->total_amount, 2) }}</span>
        </p>
    </div>

    <div class="barcode">
        *{{ str_pad($sale->id, 12, '0', STR_PAD_LEFT) }}*
    </div>

    <div class="receipt-footer">
        <p>Thank you for your purchase!</p>
        <p>Please come again</p>
        <p style="font-size: 9px; margin-top: 10px;">
            For returns, present this receipt within 7 days
        </p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            Print Receipt
        </button>
    </div>
</body>
</html>
