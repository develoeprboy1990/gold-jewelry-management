{{-- resources/views/sales/invoice.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $sale->sale_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #d97706;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #d97706;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .customer-details, .sale-details {
            width: 48%;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #d97706;
            border-bottom: 1px solid #d97706;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th {
            background-color: #d97706;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .summary-left, .summary-right {
            width: 48%;
        }
        .summary-table {
            width: 100%;
        }
        .summary-table td {
            padding: 5px 0;
        }
        .summary-table .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #d97706;
            border-bottom: 2px solid #d97706;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            color: #666;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        @media print {
            body { margin: 0; padding: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Golden Jewellers</div>
        <div class="company-tagline">Premium Jewelry Store</div>
        <div style="font-size: 12px; color: #666;">
            📍 Main Bazaar, Peshawar | 📞 +92-91-5555555 | ✉️ info@goldenjewellers.com
        </div>
        <div class="invoice-title">INVOICE</div>
    </div>

    <div class="invoice-details" style="display: table; width: 100%;">
        <div class="customer-details" style="display: table-cell; width: 50%; vertical-align: top;">
            <div class="section-title">Customer Information</div>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span>{{ $sale->customer->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">CNIC:</span>
                <span>{{ $sale->customer->cnic }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Contact:</span>
                <span>{{ $sale->customer->contact_no }}</span>
            </div>
            @if($sale->customer->email)
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span>{{ $sale->customer->email }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Address:</span>
                <span>{{ $sale->customer->city }}, {{ $sale->customer->country }}</span>
            </div>
        </div>

        <div class="sale-details" style="display: table-cell; width: 50%; vertical-align: top;">
            <div class="section-title">Sale Information</div>
            <div class="detail-row">
                <span class="detail-label">Invoice No:</span>
                <span>{{ $sale->sale_no }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span>{{ $sale->sale_date->format('M d, Y') }}</span>
            </div>
            @if($sale->bill_book_no)
            <div class="detail-row">
                <span class="detail-label">Bill Book No:</span>
                <span>{{ $sale->bill_book_no }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Salesperson:</span>
                <span>{{ $sale->user->name }}</span>
            </div>
            @if($sale->promise_date)
            <div class="detail-row">
                <span class="detail-label">Promise Date:</span>
                <span>{{ $sale->promise_date->format('M d, Y') }}</span>
            </div>
            @endif
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">S.No</th>
                <th style="width: 25%;">Item Description</th>
                <th style="width: 10%;">Karat</th>
                <th style="width: 12%;">Weight (g)</th>
                <th style="width: 12%;">Waste %</th>
                <th style="width: 12%;">Total Wt.</th>
                <th style="width: 12%;">Gold Rate</th>
                <th style="width: 12%;">Net Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleItems as $index => $saleItem)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $saleItem->item->group_item }}</strong><br>
                    <small>{{ $saleItem->item->tag_number }}</small><br>
                    <small>{{ $saleItem->item->category->name }}</small>
                </td>
                <td>{{ $saleItem->item->karat ?? 'N/A' }}</td>
                <td>{{ number_format($saleItem->weight, 3) }}</td>
                <td>{{ number_format($saleItem->waste_percentage, 2) }}%</td>
                <td>{{ number_format($saleItem->total_weight, 3) }}</td>
                <td>PKR {{ number_format($saleItem->gold_rate) }}</td>
                <td>PKR {{ number_format($saleItem->net_price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-section" style="display: table; width: 100%;">
        <div class="summary-left" style="display: table-cell; width: 50%; vertical-align: top;">
            <div class="section-title">Payment Details</div>
            <table class="summary-table">
                <tr>
                    <td>Cash Received:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->cash_received) }}</td>
                </tr>
                @if($sale->credit_card_amount > 0)
                <tr>
                    <td>Credit Card:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->credit_card_amount) }}</td>
                </tr>
                @endif
                @if($sale->check_amount > 0)
                <tr>
                    <td>Check Amount:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->check_amount) }}</td>
                </tr>
                @endif
                @if($sale->used_gold_amount > 0)
                <tr>
                    <td>Used Gold:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->used_gold_amount) }}</td>
                </tr>
                @endif
                @if($sale->pure_gold_amount > 0)
                <tr>
                    <td>Pure Gold:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->pure_gold_amount) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td><strong>Total Received:</strong></td>
                    <td style="text-align: right;"><strong>PKR {{ number_format($sale->total_received) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="summary-right" style="display: table-cell; width: 50%; vertical-align: top;">
            <div class="section-title">Bill Summary</div>
            <table class="summary-table">
                <tr>
                    <td>Total Making:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->total_making) }}</td>
                </tr>
                <tr>
                    <td>Total Stone Charges:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->total_stone_charges) }}</td>
                </tr>
                <tr>
                    <td>Total Other Charges:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->total_other_charges) }}</td>
                </tr>
                <tr>
                    <td>Total Gold Price:</td>
                    <td style="text-align: right;">PKR {{ number_format($sale->total_gold_price) }}</td>
                </tr>
                <tr>
                    <td>Item Discount:</td>
                    <td style="text-align: right; color: red;">- PKR {{ number_format($sale->total_item_discount) }}</td>
                </tr>
                <tr>
                    <td>Bill Discount:</td>
                    <td style="text-align: right; color: red;">- PKR {{ number_format($sale->bill_discount) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Net Bill:</strong></td>
                    <td style="text-align: right;"><strong>PKR {{ number_format($sale->net_bill) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Balance:</strong></td>
                    <td style="text-align: right; color: {{ $sale->cash_balance == 0 ? 'green' : ($sale->cash_balance > 0 ? 'red' : 'blue') }};">
                        <strong>PKR {{ number_format(abs($sale->cash_balance)) }}
                        @if($sale->cash_balance > 0) (Pending) @elseif($sale->cash_balance < 0) (Advance) @else (Paid) @endif</strong>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Terms and Conditions -->
    <div style="margin-top: 30px;">
        <div class="section-title">Terms & Conditions</div>
        <div style="font-size: 10px; line-height: 1.4; color: #666;">
            <p>• All gold items are sold as per current market rates and purity standards.</p>
            <p>• Exchange/Return policy: Items can be exchanged within 7 days with original receipt.</p>
            <p>• Making charges are non-refundable in case of returns.</p>
            <p>• The company is not responsible for any loss or damage after delivery.</p>
            <p>• All disputes are subject to Peshawar jurisdiction only.</p>
        </div>
    </div>

    <div class="signature-section" style="display: table; width: 100%; margin-top: 40px;">
        <div class="signature-box" style="display: table-cell; width: 33%; text-align: center;">
            <div class="signature-line">Customer Signature</div>
        </div>
        <div class="signature-box" style="display: table-cell; width: 33%; text-align: center;">
            <div class="signature-line">Salesperson Signature</div>
        </div>
        <div class="signature-box" style="display: table-cell; width: 33%; text-align: center;">
            <div class="signature-line">Manager Signature</div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Thank you for choosing Golden Jewellers!</strong></p>
        <p>For any queries, please contact us at +92-91-5555555 or visit our store.</p>
        <p style="font-size: 10px; margin-top: 15px;">
            This is a computer-generated invoice. | Invoice Date: {{ now()->format('M d, Y h:i A') }}
        </p>
    </div>

    <!-- Print Button (hidden in print) -->
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="background: #d97706; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            🖨️ Print Invoice
        </button>
    </div>
</body>
</html>