@extends('admin.layouts.master')
@section('title', 'Invoice Details - ' . $invoice->invoice_no)

@push('styles')
<style>
.invoice-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
}
.info-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}
.depot-badge {
    background: linear-gradient(45deg, #4e73df, #224abe);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
}
.customer-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, #1cc88a, #13855c);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
}
.items-table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.summary-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 25px;
}
.action-buttons .btn {
    margin: 0 5px;
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header -->
    <div class="invoice-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="mr-4">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="mb-1">Invoice #{{ $invoice->invoice_no }}</h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ $invoice->created_at->format('F d, Y \a\t h:i A') }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-clock mr-2"></i>
                            {{ $invoice->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <div class="action-buttons">
                    <a href="{{ route('admin.depots.invoices.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </a>
                    <a href="{{ route('admin.depots.invoices.print', $invoice->id) }}" 
                       class="btn btn-info" target="_blank">
                        <i class="fas fa-print mr-1"></i> Print
                    </a>
                    <button type="button" class="btn btn-success" onclick="downloadPDF()">
                        <i class="fas fa-file-pdf mr-1"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Depot & Customer Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <i class="fas fa-store fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 text-primary">Depot Information</h5>
                                    <small class="text-muted">Point of Sale Location</small>
                                </div>
                            </div>
                            
                            <div class="depot-info">
                                <div class="mb-3">
                                    <span class="depot-badge">{{ $invoice->depot->depot_type }}</span>
                                </div>
                                
                                <div class="info-item mb-2">
                                    <i class="fas fa-map-marker-alt text-muted mr-2"></i>
                                    <strong>Address:</strong><br>
                                    <span class="ml-3">{{ $invoice->depot->address }}</span>
                                </div>
                                
                                <div class="info-item mb-2">
                                    <i class="fas fa-city text-muted mr-2"></i>
                                    <strong>Location:</strong>
                                    <span>{{ $invoice->depot->city }}, {{ $invoice->depot->statename->name ?? $invoice->depot->state }}</span>
                                </div>
                                
                                @if($invoice->depot->user)
                                <div class="info-item mb-0">
                                    <i class="fas fa-user-tie text-muted mr-2"></i>
                                    <strong>Manager:</strong>
                                    <span>{{ $invoice->depot->user->name }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    @if($invoice->customer)
                                        <div class="customer-avatar">
                                            {{ strtoupper(substr($invoice->customer->name, 0, 1)) }}
                                        </div>
                                    @else
                                        <i class="fas fa-user-slash fa-2x text-muted"></i>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-0 text-success">Customer Information</h5>
                                    <small class="text-muted">Billing Details</small>
                                </div>
                            </div>
                            
                            @if($invoice->customer)
                                <div class="customer-info">
                                    <div class="info-item mb-2">
                                        <i class="fas fa-user text-muted mr-2"></i>
                                        <strong>Name:</strong>
                                        <span>{{ $invoice->customer->name }}</span>
                                    </div>
                                    
                                    @if($invoice->customer->family_id)
                                    <div class="info-item mb-2">
                                        <i class="fas fa-id-card text-muted mr-2"></i>
                                        <strong>Family ID:</strong>
                                        <span class="badge badge-info">{{ $invoice->customer->family_id }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->customer->mobile)
                                    <div class="info-item mb-2">
                                        <i class="fas fa-phone text-muted mr-2"></i>
                                        <strong>Mobile:</strong>
                                        <span>{{ $invoice->customer->mobile }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($invoice->customer->address)
                                    <div class="info-item mb-0">
                                        <i class="fas fa-home text-muted mr-2"></i>
                                        <strong>Address:</strong><br>
                                        <span class="ml-3">{{ $invoice->customer->address }}</span>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-2"></i>
                                    <h6 class="text-muted">Walk-in Customer</h6>
                                    <small class="text-muted">No customer information available</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Invoice Meta Info -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Invoice Date</span>
                                    <span class="info-box-number">{{ $invoice->created_at->format('d M, Y') }}</span>
                                    <small class="text-muted">{{ $invoice->created_at->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Items</span>
                                    <span class="info-box-number">{{ $summary['total_items'] }}</span>
                                    <small class="text-muted">{{ $summary['unique_products'] }} unique products</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Payment Status</span>
                                    <span class="info-box-number">
                                        <span class="badge badge-success">{{ $summary['payment_status'] }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text">Created By</span>
                                    <span class="info-box-number text-sm">{{ $summary['created_by'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="40%">Product</th>
                                    <th width="15%">Quantity</th>
                                    <th width="20%">Unit Price</th>
                                    <th width="20%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->stock->product_name ?? 'Product Deleted' }}</strong>
                                            @if($item->stock && $item->stock->product_code)
                                                <br><small class="text-muted">Code: {{ $item->stock->product_code }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $item->quantity }}</span>
                                    </td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calculator mr-2"></i>
                        Invoice Summary
                    </h4>
                </div>
                <div class="card-body">
                    <div class="summary-item d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span class="font-weight-bold">₹{{ number_format($summary['subtotal'], 2) }}</span>
                    </div>
                    
                    @if($summary['tax'] > 0)
                    <div class="summary-item d-flex justify-content-between mb-3">
                        <span>Tax:</span>
                        <span class="font-weight-bold text-info">₹{{ number_format($summary['tax'], 2) }}</span>
                    </div>
                    @endif
                    
                    <hr>
                    <div class="summary-item d-flex justify-content-between mb-3">
                        <span class="h5">Total Amount:</span>
                        <span class="h4 text-success font-weight-bold">₹{{ number_format($summary['total'], 2) }}</span>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted">Additional Information</h6>
                        <small class="text-muted">
                            <div class="mb-2">
                                <i class="fas fa-clock mr-1"></i>
                                Created: {{ $invoice->created_at->diffForHumans() }}
                            </div>
                            @if($invoice->updated_at != $invoice->created_at)
                            <div class="mb-2">
                                <i class="fas fa-edit mr-1"></i>
                                Updated: {{ $invoice->updated_at->diffForHumans() }}
                            </div>
                            @endif
                        </small>
                    </div>
                </div>
            </div>

            @if($invoice->note)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Notes
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $invoice->note }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function downloadPDF() {
    window.open(`{{ route('admin.depots.invoices.print', $invoice->id) }}?format=pdf`, '_blank');
}
</script>
@endpush
