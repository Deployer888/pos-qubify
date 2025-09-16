<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->invoice_no }}</title>
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
            color: #000;
            background: white;
            width: 58mm; /* Thermal printer width */
            margin: 0 auto;
            padding: 10px;
        }
        
        .receipt-container {
            width: 100%;
            text-align: center;
        }
        
        .header {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .logo-section {
            margin-bottom: 10px;
        }
        
        .logo-icon {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .company-address {
            font-size: 10px;
            margin-bottom: 2px;
        }
        
        .invoice-info {
            margin-bottom: 15px;
            text-align: left;
        }
        
        .invoice-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .customer-info {
            margin-bottom: 15px;
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        
        .customer-row {
            margin-bottom: 2px;
        }
        
        .items-section {
            margin-bottom: 15px;
        }
        
        .items-header {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
            text-align: left;
            font-weight: bold;
        }
        
        .item {
            margin-bottom: 8px;
            text-align: left;
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        
        .item-line2 {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-top: 2px;
        }
        
        .totals-section {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .grand-total {
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
            font-weight: bold;
            font-size: 13px;
        }
        
        .payment-info {
            margin-bottom: 15px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        
        .barcode-section {
            margin-bottom: 15px;
        }
        
        .barcode-image {
            margin-bottom: 5px;
        }
        
        .barcode-text {
            font-size: 10px;
            letter-spacing: 1px;
        }
        
        .footer {
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }
        
        .footer-line {
            margin-bottom: 2px;
        }
        
        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        
        /* Print styles */
        @media print {
            body {
                width: 58mm;
                margin: 0;
                padding: 5px;
            }
            
            .no-print {
                display: none;
            }
        }
        
        /* Dotted line separator */
        .separator {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }
        
        .dots {
            border-bottom: 1px dotted #000;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo-icon">🏪</div>
                <div class="company-name">{{ strtoupper($depot->depot_type) }}</div>
                <div class="company-address">{{ $depot->address }}</div>
                <div class="company-address">{{ $depot->city }}, {{ $depot->state }}</div>
                @if($depot->phone)
                <div class="company-address">Phone: {{ $depot->phone }}</div>
                @endif
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="invoice-row">
                <span>Date:</span>
                <span>{{ $sale->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="invoice-row">
                <span>Invoice:</span>
                <span>{{ $sale->invoice_no }}</span>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <div class="customer-row">
                <strong>Customer: {{ $sale->customer->name }}</strong>
            </div>
            <div class="customer-row">
                Family ID: {{ $sale->customer->family_id }}
            </div>
            @if($sale->customer->card_range)
            <div class="customer-row">
                Card: {{ $sale->customer->card_range }}
            </div>
            @endif
        </div>

        <!-- Items -->
        <div class="items-section">
            <div class="items-header">
                ITEM DETAILS
            </div>
            
            @foreach($sale->items as $index => $item)
            <div class="item">
                <div class="item-name">{{ $item->stock->product_name }}</div>
                <div class="item-details">
                    <span>{{ number_format($item->quantity, 2) }} {{ $item->stock->measurement_unit }}</span>
                    <span>@ ₹{{ number_format($item->price, 2) }}</span>
                </div>
                <div class="item-line2">
                    <span>{{ $index + 1 }}. {{ $item->stock->barcode ?? 'N/A' }}</span>
                    <span class="bold">₹{{ number_format($item->total, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <span>Total QTY:</span>
                <span>{{ $sale->items->sum('quantity') }}</span>
            </div>
            <div class="separator"></div>
            <div class="total-row">
                <span>Sub Total:</span>
                <span>₹{{ number_format($sale->subtotal, 2) }}</span>
            </div>
            @if($sale->tax > 0)
            <div class="total-row">
                <span>Order Tax:</span>
                <span>₹{{ number_format($sale->tax, 2) }}</span>
            </div>
            @endif
            <div class="total-row">
                <span>Discount:</span>
                <span>₹0.00</span>
            </div>
            <div class="total-row">
                <span>Shipping:</span>
                <span>₹0.00</span>
            </div>
            <div class="total-row grand-total">
                <span>Total:</span>
                <span>₹{{ number_format($sale->total, 2) }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-row">
                <span>PAID BY:</span>
                <span>CASH</span>
            </div>
            <div class="total-row">
                <span>AMOUNT:</span>
                <span>₹{{ number_format($sale->total, 2) }}</span>
            </div>
            <div class="total-row">
                <span>CHANGE RETURN:</span>
                <span>₹0.00</span>
            </div>
        </div>

        @if($sale->note)
        <div class="separator"></div>
        <div class="center">
            <div class="bold">Note:</div>
            <div>{{ $sale->note }}</div>
        </div>
        @endif

        <!-- Barcode -->
        <div class="barcode-section center">
            <div class="barcode-image">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($sale->invoice_no, 'C128', 1, 30) }}" 
                     alt="barcode" style="max-width: 100%;">
            </div>
            <div class="barcode-text">{{ $sale->invoice_no }}</div>
        </div>

        <!-- Footer -->
        <div class="footer center">
            <div class="separator"></div>
            <div class="footer-line bold">Thank You for Shopping!</div>
            <div class="footer-line">Visit Again</div>
            <div class="dots"></div>
            <div class="footer-line">{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </div>

    <!-- Print Controls (hidden when printing) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Receipt
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };

        // Close window after printing
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 1000);
        };
    </script>
</body>
</html>