@extends('admin.layouts.master')
@section('title', 'Daily Invoice Report')

@push('style')
<style>
.report-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
}
.summary-card {
    background: linear-gradient(135deg, var(--card-bg), var(--card-bg-secondary));
    border: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    color: white;
}
.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.filter-card {
    background: #f8f9fa;
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.report-table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}
.hourly-chart {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Report Header -->
    <div class="report-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-1">
                    <i class="fas fa-chart-line mr-3"></i>
                    Daily Invoice Report
                </h1>
                <p class="mb-0 opacity-75">
                    Comprehensive daily sales analysis for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                    @if(request('depot_id'))
                        <span class="badge badge-light ml-2">{{ $depots[request('depot_id')] ?? 'Selected Depot' }}</span>
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group">
                    <button class="btn btn-light" onclick="exportToExcel()">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </button>
                    <button class="btn btn-light" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </button>
                    <button class="btn btn-light" onclick="printReport()">
                        <i class="fas fa-print mr-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filter Form -->
    <div class="filter-card card mb-4">
        <div class="card-body">
            <form method="GET" id="reportForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-calendar-alt mr-1"></i>Report Date
                        </label>
                        <input type="date" class="form-control" name="date" 
                               value="{{ $date }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-store mr-1"></i>Depot Filter
                        </label>
                        <select name="depot_id" class="form-control">
                            <option value="">All Depots ({{ count($depots) }})</option>
                            @foreach($depots as $id => $name)
                                <option value="{{ $id }}" {{ request('depot_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-clock mr-1"></i>Time Range
                        </label>
                        <select name="time_range" class="form-control">
                            <option value="">All Day</option>
                            <option value="morning" {{ request('time_range') == 'morning' ? 'selected' : '' }}>Morning (6 AM - 12 PM)</option>
                            <option value="afternoon" {{ request('time_range') == 'afternoon' ? 'selected' : '' }}>Afternoon (12 PM - 6 PM)</option>
                            <option value="evening" {{ request('time_range') == 'evening' ? 'selected' : '' }}>Evening (6 PM - 12 AM)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Generate Report
                            </button>
                            <a href="{{ route('admin.depots.invoices.daily-report') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
            <div class="summary-card card" style="--card-bg: #4e73df; --card-bg-secondary: #224abe;">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-file-invoice fa-2x opacity-75"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($summary['total_invoices']) }}</h3>
                    <p class="mb-0 small opacity-75">Total Invoices</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
            <div class="summary-card card" style="--card-bg: #1cc88a; --card-bg-secondary: #13855c;">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-rupee-sign fa-2x opacity-75"></i>
                    </div>
                    <h3 class="mb-1">₹{{ number_format($summary['total_amount'], 0) }}</h3>
                    <p class="mb-0 small opacity-75">Total Revenue</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
            <div class="summary-card card" style="--card-bg: #36b9cc; --card-bg-secondary: #258391;">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-boxes fa-2x opacity-75"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($summary['total_items']) }}</h3>
                    <p class="mb-0 small opacity-75">Items Sold</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
            <div class="summary-card card" style="--card-bg: #f6c23e; --card-bg-secondary: #dda20a;">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                    <h3 class="mb-1">₹{{ number_format($summary['average_invoice_value'], 0) }}</h3>
                    <p class="mb-0 small opacity-75">Avg Invoice</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
            <div class="summary-card card" style="--card-bg: #e74a3b; --card-bg-secondary: #c0392b;">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($summary['unique_customers']) }}</h3>
                    <p class="mb-0 small opacity-75">Customers</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
            <div class="summary-card card" style="--card-bg: #858796; --card-bg-secondary: #5a5c69;">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="fas fa-trophy fa-2x opacity-75"></i>
                    </div>
                    <h3 class="mb-1">₹{{ number_format($summary['highest_invoice'], 0) }}</h3>
                    <p class="mb-0 small opacity-75">Highest Sale</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hourly Sales Chart -->
    @if($invoices->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="hourly-chart">
                <h5 class="mb-3">
                    <i class="fas fa-chart-bar mr-2 text-primary"></i>
                    Hourly Sales Distribution
                </h5>
                <canvas id="hourlySalesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- Invoices Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daily Invoices - {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</h3>
            <div class="card-tools">
                <button class="btn btn-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="invoicesTable">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Time</th>
                            <th>Depot</th>
                            <th>Customer</th>
                            <th class="text-right">Items</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-right">Tax</th>
                            <th class="text-right">Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->created_at->format('H:i:s') }}</td>
                            <td>{{ $invoice->depot->name }}</td>
                            <td>
                                {{ $invoice->customer->name }}
                                <small class="d-block text-muted">{{ $invoice->customer->family_id }}</small>
                            </td>
                            <td class="text-right">{{ number_format($invoice->items->sum('quantity')) }}</td>
                            <td class="text-right">{{ number_format($invoice->subtotal, 2) }}</td>
                            <td class="text-right">{{ number_format($invoice->tax, 2) }}</td>
                            <td class="text-right">{{ number_format($invoice->total, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.depots.invoices.show', $invoice->id) }}" 
                                   class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.depots.invoices.print', $invoice->id) }}" 
                                   class="btn btn-sm btn-secondary" target="_blank" title="Print Invoice">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No invoices found for this date</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total:</th>
                            <th class="text-right">{{ number_format($summary['total_items']) }}</th>
                            <th class="text-right">{{ number_format($invoices->sum('subtotal'), 2) }}</th>
                            <th class="text-right">{{ number_format($summary['total_tax'], 2) }}</th>
                            <th class="text-right">{{ number_format($summary['total_amount'], 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    $('#depot_id, #date').on('change', function() {
        $(this).closest('form').submit();
    });
});

function exportToExcel() {
    // Create a workbook
    var wb = XLSX.utils.book_new();
    
    // Get the table element
    var table = document.getElementById('invoicesTable');
    
    // Convert table to worksheet
    var ws = XLSX.utils.table_to_sheet(table);
    
    // Add worksheet to workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Daily Report');
    
    // Generate filename with date
    var filename = 'Invoice_Report_{{ $date }}.xlsx';
    
    // Save the file
    XLSX.writeFile(wb, filename);
}
</script>
@endpush
