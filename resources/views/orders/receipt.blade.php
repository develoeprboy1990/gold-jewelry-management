{{-- resources/views/orders/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt - {{ $order->order_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #d4af37;
            margin-bottom: 5px;
        }
        .company-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        .document-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            width: 48%;
        }
        .info-box h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .value {
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-box {
            width: 48%;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row.final {
            border-top: 2px solid #d4af37;
            border-bottom: 2px solid #d4af37;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
            padding-top: 10px;
        }
        .payment-status {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-in-progress { background-color: #cce7ff; color: #004085; }
        .status-ready { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #e2e3e5; color: #383d41; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">{{ config('app.name', 'Golden Jewellers') }}</div>
        <div class="company-subtitle">Premium Gold & Diamond Jewelry</div>
        <div style="font-size: 12px; color: #888; margin-top: 5px;">
            📍 Main Market, Peshawar | 📞 +92-91-1234567 | ✉️ info@goldenjewellers.com
        </div>
        <div class="document-title">ORDER RECEIPT</div>
    </div>

    <!-- Order and Customer Information -->
    <div class="info-section">
        <div class="info-box">
            <h3>Order Information</h3>
            <div class="info-row">
                <span class="label">Order No:</span>
                <span class="value">{{ $order->order_no }}</span>
            </div>
            <div class="info-row">
                <span class="label">Order Date:</span>
                <span class="value">{{ $order->order_date->format('d-M-Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Order Type:</span>
                <span class="value">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
            </div>
            @if($order->promised_date)
            <div class="info-row">
                <span class="label">Promised Date:</span>
                <span class="value">{{ $order->promised_date->format('d-M-Y') }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
            <div class="info-row">
                <span class="label">Salesperson:</span>
                <span class="value">{{ $order->user->name }}</span>
            </div>
        </div>

        <div class="info-box">
            <h3>Customer Information</h3>
            <div class="info-row">
                <span class="label">Name:</span>
                <span class="value">{{ $order->customer->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Contact:</span>
                <span class="value">{{ $order->customer->contact_no }}</span>
            </div>
            @if($order->customer->email)
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">{{ $order->customer->email }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">CNIC:</span>
                <span class="value">{{ $order->customer->cnic }}</span>
            </div>
            <div class="info-row">
                <span class="label">Address:</span>
                <span class="value">{{ $order->customer->city }}, {{ $order->customer->country }}</span>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <h3 style="margin-bottom: 15px; color: #333;">Order Items</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 20%;">Item Type</th>
                <th style="width: 35%;">Description</th>
                <th style="width: 8%;">Qty</th>
                <th style="width: 10%;">Weight</th>
                <th style="width: 10%;">Karat</th>
                <th style="width: 12%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-weight: bold;">{{ ucfirst($item->item_type) }}</td>
                <td>{{ $item->description }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: center;">
                    {{ $item->estimated_weight ? number_format($item->estimated_weight, 2) . 'g' : 'TBD' }}
                </td>
                <td style="text-align: center;">{{ $item->karat ?: '-' }}</td>
                <td style="text-align: right; font-weight: bold;">
                    PKR {{ number_format($item->estimated_total, 2) }}
                </td>
            </tr>
            @if(isset($item->specifications['notes']) && $item->specifications['notes'])
            <tr>
                <td></td>
                <td colspan="6" style="font-style: italic; color: #666; font-size: 12px;">
                    <strong>Specifications:</strong> {{ $item->specifications['notes'] }}
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-box">
            @if($order->special_instructions)
            <h3>Special Instructions</h3>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-style: italic;">
                {{ $order->special_instructions }}
            </div>
            @endif
        </div>

        <div class="summary-box">
            <h3>Financial Summary</h3>
            <div class="total-row">
                <span>Estimated Total:</span>
                <span style="font-weight: bold;">PKR {{ number_format($order->estimated_total, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Advance Payment:</span>
                <span style="color: #28a745; font-weight: bold;">PKR {{ number_format($order->advance_payment, 2) }}</span>
            </div>
            <div class="total-row final">
                <span>Remaining Balance:</span>
                <span style="color: #dc3545;">PKR {{ number_format($order->estimated_total - $order->advance_payment, 2) }}</span>
            </div>
            
            @if($order->final_amount && $order->final_amount != $order->estimated_total)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                <div class="total-row">
                    <span>Final Amount:</span>
                    <span style="font-weight: bold;">PKR {{ number_format($order->final_amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Final Balance:</span>
                    <span style="color: #dc3545;">PKR {{ number_format($order->final_amount - $order->advance_payment, 2) }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Payment Status -->
    <div class="payment-status">
        <h3 style="margin: 0 0 10px 0;">Payment Status</h3>
        @php
            $remainingBalance = $order->estimated_total - $order->advance_payment;
        @endphp
        @if($remainingBalance > 0)
            <p style="margin: 5px 0; color: #dc3545;">
                <strong>⚠️ Payment Pending:</strong> PKR {{ number_format($remainingBalance, 2) }} remaining to be paid upon delivery.
            </p>
        @elseif($remainingBalance == 0)
            <p style="margin: 5px 0; color: #28a745;">
                <strong>✅ Fully Paid:</strong> No remaining balance.
            </p>
        @else
            <p style="margin: 5px 0; color: #17a2b8;">
                <strong>💰 Overpaid:</strong> PKR {{ number_format(abs($remainingBalance), 2) }} credit balance.
            </p>
        @endif
        
        @if($order->promised_date)
            <p style="margin: 5px 0;">
                <strong>📅 Expected Delivery:</strong> {{ $order->promised_date->format('l, d F Y') }}
            </p>
        @endif
    </div>

    <!-- Terms and Conditions -->
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 10px 0; font-size: 14px;">Terms & Conditions</h3>
        <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #666;">
            <li>Custom orders cannot be cancelled once manufacturing begins</li>
            <li>Advance payment is non-refundable for custom orders</li>
            <li>Final weight and price may vary from estimates (±5%)</li>
            <li>Orders not collected within 30 days of completion will incur storage charges</li>
            <li>All jewelry comes with a 1-year manufacturing warranty</li>
            <li>Prices are subject to gold rate fluctuations</li>
        </ul>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">Customer Signature</div>
            <div style="font-size: 12px; margin-top: 5px;">{{ $order->customer->name }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Authorized Signature</div>
            <div style="font-size: 12px; margin-top: 5px;">{{ $order->user->name }}</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank you for choosing {{ config('app.name', 'Golden Jewellers') }}!</strong></p>
        <p>For any queries, please contact us at +92-91-1234567 or visit our store.</p>
        <p style="margin-top: 10px; font-size: 10px;">
            Generated on {{ now()->format('d-M-Y H:i:s') }} | Order Receipt #{{ $order->order_no }}
        </p>
    </div>
</body>
</html>