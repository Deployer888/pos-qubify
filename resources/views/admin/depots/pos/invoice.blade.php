@extends('admin.layouts.master')

@push('style')
<style>
    .invoice-container { 
        max-width: 800px; 
        margin: 0 auto; 
        background: white; 
        border-radius: 15px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .invoice-header { 
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); 
        color: white; 
        padding: 30px; 
        text-align: center;
    }
    .invoice-logo { 
        width: 80px; 
        height: 80px; 
        background: rgba(255,255,255,0.2); 
        border-radius: 50%; 
        margin: 0 auto 20px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-size: 36px;
    }
    .invoice-body { padding: 30px; }
    .invoice-info { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 30px; 
        margin-bottom: 30px;
    }
    .info-section h6 { 
        color: #6c5ce7; 
        font-weight: 600; 
        margin-bottom: 15px; 
        text-transform: uppercase; 
        font-size: 12px; 
        letter-spacing: 1px;
    }
    .info-item { margin-bottom: 8px; font-size: 14px; }
    .info-label { font-weight: 600; color: #2d3436; }
    .info-value { color: #636e72; }
    
    .items-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin: 30px 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .items-table th { 
        background: #f8f9fa; 
        padding: 15px; 
        font-weight: 600; 
        color: #2d3436; 
        border-bottom: 2px solid #e9ecef;
    }
    .items-table td { 
        padding: 15px; 
        border-bottom: 1px solid #e9ecef; 
    }
    .items-table tr:last-child td { border-bottom: none; }
    .items-table tr:hover { background: #f8f9fa; }
    
    .totals-section { 
        background: #f8f9fa; 
        border-radius: 8px; 
        padding: 20px; 
        margin-top: 30px;
    }
    .total-row { 
        display: flex; 
        justify-content: space-between; 
        margin-bottom: 10px;
        font-size: 14px;
    }
    .total-row.grand-total { 
        font-size: 18px; 
        font-weight: 700; 
        color: #6c5ce7; 
        border-top: 2px solid #e9ecef; 
        padding-top: 15px; 
        margin-top: 15px;
    }
    
    .barcode-section { 
        text-align: center; 
        margin: 30px 0; 
        padding: 20px; 
        background: #f8f9fa; 
        border-radius: 8px;
    }
    .barcode-image { margin-bottom: 10px; }
    .barcode-text { 
        font-family: 'Courier New', monospace; 
        font-weight: bold; 
        letter-spacing: 2px;
    }
    
    .action-buttons { 
        text-align: center; 
        padding: 30px; 
        background: #f8f9fa; 
        gap: 15px;
    }
    .btn-print { 
        background: #6c5ce7; 
        color: white; 
        border: none; 
        padding: 12px 30px; 
        border-radius: 25px; 
        font-weight: 600;
    }
    .btn-thermal { 
        background: #00b894; 
        color: white; 
        border: none; 
        padding: 12px 30px; 
        border-radius: 25px; 
        font-weight: 600;
    }
    .btn-back { 
        background: #636e72; 
        color: white; 
        border: none; 
        padding: 12px 30px; 
        border-radius: 25px; 
        font-weight: 600;
    }
    
    @media print {
        .d-print-none { display: none !important; }
        .invoice-container { box-shadow: none; }
        .action-buttons { display: none; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="invoice-logo">
                <i class="mdi mdi-store"></i>
            </div>
            <h4 class="mb-2">{{ $depot->depot_type }}</h4>
            <p class="mb-0">Invoice #{{ $sale->invoice_no }}</p>
            <small>{{ $sale->created_at->format('F d, Y - h:i A') }}</small>
        </div>

        <!-- Invoice Body -->
        <div class="invoice-body">
            <!-- Invoice Info -->
            <div class="invoice-info">
                <div class="info-section">
                    <h6>From</h6>
                    <div class="info-item">
                        <div class="info-label">{{ $depot->depot_type }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value">{{ $depot->address }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value">{{ $depot->city }}, {{ $depot->state }}</div>
                    </div>
                    @if($depot->phone)
                    <div class="info-item">
                        <div class="info-value">Phone: {{ $depot->phone }}</div>
                    </div>
                    @endif
                </div>

                <div class="info-section">
                    <h6>To</h6>
                    <div class="info-item">
                        <div class="info-label">{{ $sale->customer->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value">Family ID: {{ $sale->customer->family_id }}</div>
                    </div>
                    @if($sale->customer->card_range)
                    <div class="info-item">
                        <div class="info-value">Card: {{ $sale->customer->card_range }}</div>
                    </div>
                    @endif
                    @if($sale->customer->address)
                    <div class="info-item">
                        <div class="info-value">{{ $sale->customer->address }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Item Description</th>
                        <th width="80">Unit</th>
                        <th width="100" class="text-right">Qty</th>
                        <th width="120" class="text-right">Unit Price</th>
                        <th width="120" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="font-weight-600">{{ $item->stock->product_name }}</div>
                            @if($item->stock->barcode)
                            <small class="text-muted">SKU: {{ $item->stock->barcode }}</small>
                            @endif
                        </td>
                        <td>{{ $item->stock->measurement_unit }}</td>
                        <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals Section -->
            <div class="totals-section">
                <div class="total-row">
                    <span>Sub Total:</span>
                    <span>₹{{ number_format($sale->subtotal, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax:</span>
                    <span>₹{{ number_format($sale->tax, 2) }}</span>
                </div>
                @if($sale->discount > 0)
                <div class="total-row">
                    <span>Discount:</span>
                    <span>-₹{{ number_format($sale->discount, 2) }}</span>
                </div>
                @endif
                <div class="total-row grand-total">
                    <span>Grand Total:</span>
                    <span>₹{{ number_format($sale->total, 2) }}</span>
                </div>
            </div>

            @if($sale->note)
            <div class="mt-4">
                <h6 class="text-muted">Note:</h6>
                <p class="mb-0">{{ $sale->note }}</p>
            </div>
            @endif

            <!-- Barcode Section -->
            <div class="barcode-section">
                <div class="barcode-image">
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($sale->invoice_no, 'C128', 2, 60) }}" alt="Invoice Barcode">
                </div>
                <div class="barcode-text">{{ $sale->invoice_no }}</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons d-print-none">
            <button onclick="window.print()" class="btn btn-print">
                <i class="mdi mdi-printer mr-2"></i>Print Invoice
            </button>
            <a href="{{ route('admin.depots.pos.invoice.print', [$depot, $sale]) }}" target="_blank" class="btn btn-thermal">
                <i class="mdi mdi-receipt mr-2"></i>Thermal Print
            </a>
            <a href="{{ route('admin.depots.pos.index', $depot) }}" class="btn btn-back">
                <i class="mdi mdi-arrow-left mr-2"></i>Back to POS
            </a>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="mdi mdi-check-circle text-success" style="font-size: 64px;"></i>
                </div>
                <h5 class="mb-3">Payment Successful!</h5>
                <p class="text-muted mb-4">Invoice #{{ $sale->invoice_no }} has been generated successfully.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="mdi mdi-printer mr-1"></i> Print
                    </button>
                    <a href="{{ route('admin.depots.pos.index', $depot) }}" class="btn btn-outline-secondary">
                        New Transaction
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Show success modal if redirected from payment
    @if(session('payment_success'))
        $('#successModal').modal('show');
    @endif
    
    // Auto-focus print button
    setTimeout(function() {
        $('.btn-print').focus();
    }, 500);
});

// Print functionality
function printInvoice() {
    window.print();
}

// Keyboard shortcuts
$(document).on('keydown', function(e) {
    // Ctrl+P for print
    if (e.ctrlKey && e.keyCode === 80) {
        e.preventDefault();
        printInvoice();
    }
    
    // Escape to go back
    if (e.keyCode === 27) {
        window.location.href = '{{ route('admin.depots.pos.index', $depot) }}';
    }
});
</script>
@endpush