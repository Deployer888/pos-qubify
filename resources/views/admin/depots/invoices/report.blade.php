<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depot Invoices Report - {{ now()->format('d M, Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .report-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 15px;
        }
        
        .report-header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .report-date {
            font-size: 12px;
            color: #666;
        }
        
        .summary-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
        }
        
        .filters-section {
            margin-bottom: 15px;
            padding: 10px;
            background: #e9ecef;
            border-radius: 3px;
        }
        
        .filters-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .invoices-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .invoices-table th,
        .invoices-table td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        
        .invoices-table th {
            background: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .invoices-table td:nth-child(4),
        .invoices-table td:nth-child(5),
        .invoices-table td:nth-child(6),
        .invoices-table td:nth-child(7),
        .invoices-table td:nth-child(8) {
            text-align: right;
        }
        
        .invoices-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                font-size: 10px;
            }
            
            .report-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Report Header -->
        <div class="report-header">
            <div class="company-name">{{ config('app.name', 'POS System') }}</div>
            <div class="report-title">Depot Invoices Report</div>
            <div class="report-date">Generated on {{ now()->format('d M, Y h:i A') }}</div>
        </div>

        <!-- Applied Filters -->
        @if(!empty($filters) && (isset($filters['date_from']) || isset($filters['date_to']) || isset($filters['depot_id']) || isset($filters['customer']) || isset($filters['invoice_no'])))
        <div class="filters-section">
            <div class="filters-title">Applied Filters:</div>
            @if(isset($filters['date_from']) && isset($filters['date_to']))
                <strong>Date Range:</strong> {{ \Carbon\Carbon::parse($filters['date_from'])->format('d M, Y') }} - {{ \Carbon\Carbon::parse($filters['date_to'])->format('d M, Y') }}<br>
            @endif
            @if(isset($filters['depot_id']))
                <strong>Depot ID:</strong> {{ $filters['depot_id'] }}<br>
            @endif
            @if(isset($filters['customer']))
                <strong>Customer:</strong> {{ $filters['customer'] }}<br>
            @endif
            @if(isset($filters['invoice_no']))
                <strong>Invoice No:</strong> {{ $filters['invoice_no'] }}<br>
            @endif
        </div>
        @endif

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total Invoices</div>
                    <div class="summary-value">{{ number_format($summary['total_invoices']) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Amount</div>
                    <div class="summary-value">₹{{ number_format($summary['total_amount'], 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Tax</div>
                    <div class="summary-value">₹{{ number_format($summary['total_tax'], 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Total Items</div>
                    <div class="summary-value">{{ number_format($summary['total_items']) }}</div>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <table class="invoices-table">
            <thead>
                <tr>
                    <th style="width: 8%">Invoice No</th>
                    <th style="width: 10%">Date</th>
                    <th style="width: 15%">Depot</th>
                    <th style="width: 15%">Customer</th>
                    <th style="width: 8%">Items</th>
                    <th style="width: 12%">Subtotal</th>
                    <th style="width: 10%">Tax</th>
                    <th style="width: 12%">Total</th>
                    <th style="width: 10%">Manager</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_no }}</td>
                    <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                    <td>{{ $invoice->depot->depot_type ?? 'N/A' }}<br><small>{{ $invoice->depot->city ?? '' }}</small></td>
                    <td>
                        {{ $invoice->customer->name ?? 'Walk-in Customer' }}
                        @if($invoice->customer && $invoice->customer->family_id)
                            <br><small>ID: {{ $invoice->customer->family_id }}</small>
                        @endif
                    </td>
                    <td>{{ $invoice->items->sum('quantity') }}</td>
                    <td>₹{{ number_format($invoice->subtotal, 2) }}</td>
                    <td>₹{{ number_format($invoice->tax, 2) }}</td>
                    <td><strong>₹{{ number_format($invoice->total, 2) }}</strong></td>
                    <td>{{ $invoice->depot->user->name ?? 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                        No invoices found for the selected criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }} - Depot Invoices Report</strong></p>
            <p>Report generated on {{ now()->format('d M, Y h:i A') }} | Total Records: {{ $invoices->count() }}</p>
            @if($summary['total_invoices'] > 0)
            <p>Average Invoice Value: ₹{{ number_format($summary['average_invoice_value'], 2) }} | Unique Customers: {{ $summary['unique_customers'] }}</p>
            @endif
        </div>
    </div>
</body>
</html>