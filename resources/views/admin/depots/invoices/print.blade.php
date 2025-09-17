<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_no }} - {{ $depot->depot_type }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        .invoice-header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #333;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .detail-section {
            flex: 1;
            padding: 0 10px;
        }
        
        .detail-section h3 {
            font-size: 14px;
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .detail-item {
            margin-bottom: 5px;
        }
        
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 80px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            background: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .items-table td:nth-child(3),
        .items-table td:nth-child(4),
        .items-table td:nth-child(5) {
            text-align: right;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .summary-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .summary-table .label {
            text-align: right;
            font-weight: bold;
            width: 60%;
        }
        
        .summary-table .amount {
            text-align: right;
            width: 40%;
        }
        
        .total-row {
            background: #007bff;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }
        
        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .notes-section h4 {
            color: #007bff;
            margin-bottom: 10px;
        }
        
        @media print {
            body {
                font-size: 11px;
            }
            
            .invoice-container {
                padding: 10px;
            }
            
            .no-print {
                display: none;
            }
        }
        
        .print-actions {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Print Actions (hidden when printing) -->
        <div class="print-actions no-print">
            <button class="btn" onclick="window.print()">
                🖨️ Print Invoice
            </button>
            <a href="{{ route('admin.depots.invoices.show', $invoice->id) }}" class="btn btn-secondary">
                ← Back to Details
            </a>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-name">{{ config('app.name', 'POS System') }}</div>
                <div class="company-tagline">Professional Point of Sale & Inventory Management</div>
            </div>
            <div class="invoice-title">
                INVOICE #{{ $invoice->invoice_no }}
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-section">
                <h3>🏪 Depot Information</h3>
                <div class="detail-item">
                    <span class="detail-label">Type:</span>
                    {{ $depot->depot_type }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Address:</span>
                    {{ $depot->address }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">City:</span>
                    {{ $depot->city }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">State:</span>
                    {{ $depot->statename->name ?? $depot->state }}
                </div>
                @if($depot->user)
                <div class="detail-item">
                    <span class="detail-label">Manager:</span>
                    {{ $depot->user->name }}
                </div>
                @endif
            </div>

            <div class="detail-section">
                <h3>👤 Customer Information</h3>
                @if($invoice->customer)
                    <div class="detail-item">
                        <span class="detail-label">Name:</span>
                        {{ $invoice->customer->name }}
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Family ID:</span>
                        {{ $invoice->customer->family_id ?? 'N/A' }}
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Address:</span>
                        {{ $invoice->customer->address ?? 'Not provided' }}
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Mobile:</span>
                        {{ $invoice->customer->mobile ?? 'N/A' }}
                    </div>
                @else
                    <div class="detail-item">
                        <strong>Walk-in Customer</strong>
                    </div>
                @endif
            </div>

            <div class="detail-section">
                <h3>📅 Invoice Details</h3>
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    {{ $invoice->created_at->format('d M, Y') }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Time:</span>
                    {{ $invoice->created_at->format('h:i A') }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Items:</span>
                    {{ $summary['total_items'] }}
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <strong style="color: #28a745;">PAID</strong>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 45%">Product</th>
                    <th style="width: 15%">Quantity</th>
                    <th style="width: 17.5%">Unit Price</th>
                    <th style="width: 17.5%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $item->stock->product_name ?? 'Product Deleted' }}</strong>
                        @if($item->stock && $item->stock->product_code)
                            <br><small style="color: #666;">Code: {{ $item->stock->product_code }}</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="amount">₹{{ number_format($summary['subtotal'], 2) }}</td>
                </tr>
                @if($summary['tax'] > 0)
                <tr>
                    <td class="label">Tax:</td>
                    <td class="amount">₹{{ number_format($summary['tax'], 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="label">TOTAL AMOUNT:</td>
                    <td class="amount">₹{{ number_format($summary['total'], 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Notes Section -->
        @if($invoice->note)
        <div class="notes-section">
            <h4>📝 Notes</h4>
            <p>{{ $invoice->note }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated invoice. No signature required.</p>
            <p>Generated on {{ now()->format('d M, Y h:i A') }} | Invoice #{{ $invoice->invoice_no }}</p>
            <p>{{ config('app.name') }} - Professional POS & Inventory Management System</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
        
        // Print function
        function printInvoice() {
            window.print();
        }
    </script>
</body>
</html>