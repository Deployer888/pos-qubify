@extends('admin.layouts.master')
@section('title', 'Depot Invoices Management')

@push('style')
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">

<style>
.summary-card {
    border: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.filter-card {
    background: #f8f9fa;
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.invoice-table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.badge-depot {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}
.invoice-icon {
    width: 40px;
    height: 40px;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

/* DataTables custom styling */
.dt-buttons {
    margin-bottom: 15px;
}
.dt-button {
    border-radius: 5px !important;
    margin-right: 5px !important;
    border: none !important;
    padding: 8px 15px !important;
    font-size: 14px !important;
}
.dt-button.buttons-excel {
    background: #28a745 !important;
    color: white !important;
}
.dt-button.buttons-csv {
    background: #17a2b8 !important;
    color: white !important;
}
.dt-button.buttons-pdf {
    background: #dc3545 !important;
    color: white !important;
}
.dt-button.buttons-print {
    background: #6c757d !important;
    color: white !important;
}
.dt-button.buttons-copy {
    background: #fd7e14 !important;
    color: white !important;
}

/* Custom loading */
.dt-processing {
    background: rgba(255,255,255,0.9) !important;
    border-radius: 10px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 15px;
}

.table td {
    vertical-align: middle;
}

@media (max-width: 768px) {
    .dt-buttons {
        flex-wrap: wrap;
    }
    .dt-button {
        margin-bottom: 5px !important;
        font-size: 12px !important;
        padding: 6px 10px !important;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-primary mb-1">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Depot Invoice Management
                    </h2>
                    <p class="text-muted mb-0">
                        Manage and track all depot invoices across your network
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.depots.invoices.daily-report') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar mr-1"></i> Daily Report
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportAllData()">
                        <i class="fas fa-download mr-1"></i> Export All Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($summary['total_invoices']) }}</div>
                            <div class="small">Total Invoices</div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h4 mb-0 font-weight-bold">₹{{ number_format($summary['total_amount'], 0) }}</div>
                            <div class="small">Total Revenue</div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-rupee-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($summary['total_items']) }}</div>
                            <div class="small">Items Sold</div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h4 mb-0 font-weight-bold">₹{{ number_format($summary['average_invoice_value'], 0) }}</div>
                            <div class="small">Avg Invoice</div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h4 mb-0 font-weight-bold">{{ number_format($summary['unique_customers']) }}</div>
                            <div class="small">Customers</div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-white bg-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h4 mb-0 font-weight-bold">₹{{ number_format($summary['total_tax'], 0) }}</div>
                            <div class="small">Total Tax</div>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-calendar-alt mr-1"></i>Date From
                        </label>
                        <input type="date" class="form-control" name="date_from" id="date_from"
                               value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-calendar-alt mr-1"></i>Date To
                        </label>
                        <input type="date" class="form-control" name="date_to" id="date_to"
                               value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-store mr-1"></i>Depot
                        </label>
                        <select class="form-control" name="depot_id" id="depotFilter">
                            <option value="">All Depots ({{ $depots->count() }})</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}" {{ request('depot_id') == $depot->id ? 'selected' : '' }}>
                                    {{ $depot->depot_type }} - {{ $depot->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-receipt mr-1"></i>Invoice No
                        </label>
                        <input type="text" class="form-control" name="invoice_no" id="invoice_no"
                               placeholder="Search invoice..." value="{{ request('invoice_no') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-user mr-1"></i>Customer
                        </label>
                        <input type="text" class="form-control" name="customer" id="customer_filter"
                               placeholder="Name/Mobile/Family ID..." value="{{ request('customer') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">&nbsp;</label>
                        <div class="d-flex">
                            <button type="button" class="btn btn-primary mr-2" onclick="applyFilters()">
                                <i class="fas fa-search mr-1"></i> Apply Filters
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="fas fa-times mr-1"></i> Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoice Table -->
    <div class="card invoice-table">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-table mr-2"></i>
                    Invoice Records
                </h4>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-sm" onclick="refreshTable()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="invoicesTable" style="width:100%">
                    <thead class="bg-light">
                        <tr>
                            <th>Invoice Details</th>
                            <th>Depot Information</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr class="invoice-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="invoice-icon mr-3">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-primary">{{ $invoice->invoice_no }}</div>
                                        <div class="text-muted small">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $invoice->created_at->format('d M, Y') }}
                                            <span class="mx-1">•</span>
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $invoice->created_at->format('h:i A') }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-history mr-1"></i>
                                            {{ $invoice->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="depot-info">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge-depot mr-2">{{ $invoice->depot->depot_type }}</span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $invoice->depot->city }}
                                    </div>
                                    @if($invoice->depot->user)
                                    <div class="text-muted small">
                                        <i class="fas fa-user-tie mr-1"></i>
                                        {{ $invoice->depot->user->name }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($invoice->customer)
                                    <div class="customer-info">
                                        <div class="font-weight-medium">{{ $invoice->customer->name }}</div>
                                        @if($invoice->customer->family_id)
                                        <div class="text-muted small">
                                            <i class="fas fa-id-card mr-1"></i>
                                            ID: {{ $invoice->customer->family_id }}
                                        </div>
                                        @endif
                                        @if($invoice->customer->mobile)
                                        <div class="text-muted small">
                                            <i class="fas fa-phone mr-1"></i>
                                            {{ $invoice->customer->mobile }}
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-user-slash text-muted"></i>
                                        <div class="text-muted small">Walk-in Customer</div>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="items-info">
                                    <span class="badge badge-primary badge-pill">
                                        {{ $invoice->items->sum('quantity') }}
                                    </span>
                                    <div class="text-muted small mt-1">items</div>
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="amount-info">
                                    <div class="h6 mb-1 text-success font-weight-bold">
                                        ₹{{ number_format($invoice->total, 2) }}
                                    </div>
                                    @if($invoice->tax > 0)
                                    <div class="text-muted small">
                                        Tax: ₹{{ number_format($invoice->tax, 2) }}
                                    </div>
                                    @endif
                                    <div class="text-muted small">
                                        Subtotal: ₹{{ number_format($invoice->subtotal, 2) }}
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.depots.invoices.show', $invoice->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.depots.invoices.print', $invoice->id) }}" 
                                       class="btn btn-sm btn-outline-secondary" target="_blank" title="Print Invoice">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            onclick="downloadPDF({{ $invoice->id }})" title="Download PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
let invoicesTable;

$(document).ready(function() {
    // Initialize DataTable
    invoicesTable = $('#invoicesTable').DataTable({
        responsive: true,
        processing: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-info',
                filename: function() {
                    return 'depot_invoices_' + moment().format('YYYY-MM-DD_HH-mm-ss');
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    format: {
                        body: function(data, row, column, node) {
                            // Clean HTML tags for export
                            return $('<div>').html(data).text();
                        }
                    }
                }
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success',
                filename: function() {
                    return 'depot_invoices_' + moment().format('YYYY-MM-DD_HH-mm-ss');
                },
                title: 'Depot Invoices Report',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    format: {
                        body: function(data, row, column, node) {
                            return $('<div>').html(data).text();
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger',
                filename: function() {
                    return 'depot_invoices_' + moment().format('YYYY-MM-DD_HH-mm-ss');
                },
                title: 'Depot Invoices Report',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                    format: {
                        body: function(data, row, column, node) {
                            return $('<div>').html(data).text();
                        }
                    }
                },
                customize: function(doc) {
                    doc.styles.tableHeader.fontSize = 10;
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableBodyEven.fontSize = 8;
                    doc.styles.tableBodyOdd.fontSize = 8;
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-secondary',
                title: 'Depot Invoices Report',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Columns',
                className: 'btn btn-outline-secondary'
            }
        ],
        columnDefs: [
            {
                targets: 5, // Actions column
                orderable: false,
                searchable: false
            },
            {
                targets: 3, // Items column
                className: 'text-center'
            },
            {
                targets: 4, // Amount column
                className: 'text-right'
            }
        ],
        order: [[0, 'desc']], // Sort by invoice details (date) descending
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            search: '<i class="fas fa-search"></i>',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ invoices',
            infoEmpty: 'No invoices found',
            infoFiltered: '(filtered from _MAX_ total invoices)',
            zeroRecords: 'No matching invoices found',
            emptyTable: 'No invoices available'
        },
        initComplete: function() {
            // Custom search functionality
            this.api().columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup change clear', function() {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        }
    });

    // Date range validation
    $('#date_from, #date_to').on('change', function() {
        const dateFrom = $('#date_from').val();
        const dateTo = $('#date_to').val();
        
        if (dateFrom && dateTo && dateFrom > dateTo) {
            alert('Date From cannot be greater than Date To');
            $(this).val('');
        }
    });

    // Add moment.js for date formatting in exports
    if (typeof moment === 'undefined') {
        $('<script>').attr('src', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js').appendTo('head');
    }
});

function applyFilters() {
    const filters = {
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val(),
        depot_id: $('#depotFilter').val(),
        invoice_no: $('#invoice_no').val(),
        customer: $('#customer_filter').val()
    };

    // Build query string
    const queryString = Object.keys(filters)
        .filter(key => filters[key])
        .map(key => key + '=' + encodeURIComponent(filters[key]))
        .join('&');

    // Reload page with filters
    if (queryString) {
        window.location.href = '{{ route("admin.depots.invoices.index") }}?' + queryString;
    } else {
        window.location.href = '{{ route("admin.depots.invoices.index") }}';
    }
}

function clearFilters() {
    $('#filterForm')[0].reset();
    window.location.href = '{{ route("admin.depots.invoices.index") }}';
}

function refreshTable() {
    if (invoicesTable) {
        invoicesTable.ajax.reload(null, false);
    } else {
        window.location.reload();
    }
}

function exportAllData() {
    // Get current filters
    const filters = {
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val(),
        depot_id: $('#depotFilter').val(),
        invoice_no: $('#invoice_no').val(),
        customer: $('#customer_filter').val()
    };

    // Build query string
    const queryString = Object.keys(filters)
        .filter(key => filters[key])
        .map(key => key + '=' + encodeURIComponent(filters[key]))
        .join('&');

    // Export with filters
    const exportUrl = `{{ route('admin.depots.invoices.export') }}${queryString ? '?' + queryString : ''}`;
    window.open(exportUrl, '_blank');
}

function downloadPDF(invoiceId) {
    window.open(`{{ url('admin/depots/invoices') }}/${invoiceId}/print?format=pdf`, '_blank');
}

// Auto-refresh table every 5 minutes (optional)
setInterval(function() {
    if (invoicesTable && !$('#invoicesTable_processing').is(':visible')) {
        invoicesTable.ajax.reload(null, false);
    }
}, 300000); // 5 minutes
</script>
@endpush