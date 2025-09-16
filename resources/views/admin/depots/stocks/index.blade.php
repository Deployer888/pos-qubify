@extends('admin.layouts.master')

@section('content')
<!-- Page Header -->
<div class="page-title-box mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="page-title">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.depots.index') }}" class="text-muted">
                                    <i class="mdi mdi-home-outline"></i> Depots
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Stock Management</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="page-title-right d-flex justify-content-end">
                    <a href="{{ route('admin.depots.stocks.update-form', $depot) }}" 
                       class="btn btn-gradient-primary btn-rounded shadow-sm px-4 py-2">
                        <i class="mdi mdi-plus-circle me-2"></i> Add New Stock
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Depot Overview Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="bg-gradient-primary text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="depot-icon mr-3">
                                    <div class="avatar-lg">
                                        <div class="avatar-title rounded-circle bg-white-alpha-25">
                                            <i class="mdi mdi-warehouse font-size-20 text-white"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1 font-weight-600">{{ $depot->depot_type }} Depot</h3>
                                    <p class="text-white-75 mb-0">
                                        <i class="mdi mdi-map-marker-outline mr-1"></i>
                                        {{ $depot->city }}, {{ $depot->state }}
                                    </p>
                                    <small class="text-white-50">
                                        <i class="mdi mdi-clock-outline mr-1"></i>
                                        Last updated: {{ now()->format('M d, Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end border-white-alpha-25 pe-3">
                                        <h2 class="text-white mb-0 font-weight-700">{{ $stocks->total() }}</h2>
                                        <p class="text-white-75 mb-0 font-size-13">Total Items</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="ps-3">
                                        <span class="badge badge-{{ $depot->status == 'active' ? 'success' : 'warning' }} badge-pill px-3 py-2">
                                            {{ ucfirst($depot->status) }}
                                        </span>
                                        <p class="text-white-75 mb-0 font-size-13 mt-1">Depot Status</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md">
                            <div class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                <i class="mdi mdi-alert-circle font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $stocks->where('current_stock', '<=', 10)->count() }}</h4>
                        <p class="text-muted mb-0">Low Stock Alert</p>
                        <span class="text-danger font-size-12">
                            <i class="mdi mdi-arrow-down mr-1"></i>Critical Level
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md">
                            <div class="avatar-title rounded-circle bg-warning-subtle text-warning">
                                <i class="mdi mdi-alert-outline font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $stocks->whereBetween('current_stock', [11, 50])->count() }}</h4>
                        <p class="text-muted mb-0">Medium Stock</p>
                        <span class="text-warning font-size-12">
                            <i class="mdi mdi-minus mr-1"></i>Monitor Closely
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md">
                            <div class="avatar-title rounded-circle bg-success-subtle text-success">
                                <i class="mdi mdi-check-circle font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $stocks->where('current_stock', '>', 50)->count() }}</h4>
                        <p class="text-muted mb-0">Good Stock</p>
                        <span class="text-success font-size-12">
                            <i class="mdi mdi-arrow-up mr-1"></i>Optimal Level
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md">
                            <div class="avatar-title rounded-circle bg-info-subtle text-info">
                                <i class="mdi mdi-currency-inr font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">₹{{ number_format($stocks->sum(function($stock) { return $stock->current_stock * $stock->price; }), 0) }}</h4>
                        <p class="text-muted mb-0">Total Inventory Value</p>
                        <span class="text-info font-size-12">
                            <i class="mdi mdi-trending-up mr-1"></i>Current Worth
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-4">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="card-title mb-0 font-weight-600">
                            <i class="mdi mdi-view-grid-outline text-primary mr-2"></i>
                            Stock Inventory Management
                        </h4>
                        <p class="text-muted mb-0 font-size-13">Manage and monitor your stock levels</p>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0 justify-content-end">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary btn-sm" id="refreshTable">
                                <i class="mdi mdi-refresh mr-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0" id="stockTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="120px">
                                    <i class="mdi mdi-barcode mr-1"></i>BARCODE
                                </th>
                                <th width="250px">
                                    <i class="mdi mdi-package-variant mr-1"></i>Product Details
                                </th>
                                <th class="text-center" width="140px">
                                    <i class="mdi mdi-barcode-scan mr-1"></i>Barcode
                                </th>
                                <th class="text-center" width="120px">
                                    <i class="mdi mdi-cube-outline mr-1"></i>Stock
                                </th>
                                <th class="text-center" width="80px">Unit</th>
                                <th class="text-center" width="120px">
                                    <i class="mdi mdi-currency-inr mr-1"></i>Price
                                </th>
                                <th class="text-center" width="120px">Customer Price</th>
                                <th class="text-center" width="120px">Status</th>
                                <th class="text-center" width="140px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                            <tr class="stock-row">
                                <td class="text-center">
                                    <div class="barcode-id">
                                        <span class="badge bg-primary-subtle text-primary font-size-12 px-3 py-2 font-monospace">
                                            {{ $stock->barcode ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-3 flex-shrink-0">
                                            <div class="avatar-title rounded bg-primary-subtle text-primary">
                                                <i class="mdi mdi-package-variant font-size-16"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 font-size-15 font-weight-600">{{ $stock->product_name }}</h6>
                                            <p class="text-muted mb-0 font-size-12">
                                                <i class="mdi mdi-identifier mr-1"></i>
                                                ID: {{ $stock->barcode ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="barcode-display">
                                        @if($stock->barcode_image)
                                            <div class="barcode-wrapper p-2 bg-light rounded">
                                                <img src="{{ env('IMG_URL').'storage/depot_barcodes/'.$stock->barcode_image }}" 
                                                     alt="Barcode" 
                                                     class="barcode-image" 
                                                     height="35"
                                                     style="max-width: 100px;">
                                            </div>
                                        @else
                                            <span class="text-muted font-size-12">
                                                <i class="mdi mdi-barcode-off"></i><br>No Barcode
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="stock-quantity">
                                        <h5 class="mb-0 
                                            @if($stock->current_stock <= 10) text-danger 
                                            @elseif($stock->current_stock <= 50) text-warning 
                                            @else text-success @endif font-weight-600">
                                            {{ number_format($stock->current_stock, 0) }}
                                        </h5>
                                        <small class="text-muted">units</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info px-3 py-2">
                                        {{ $stock->measurement_unit }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="pricing">
                                        <h6 class="mb-0 text-dark font-weight-600">₹{{ number_format($stock->price, 2) }}</h6>
                                        <small class="text-muted">base price</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="customer-pricing">
                                        <h6 class="mb-0 text-success font-weight-600">₹{{ number_format($stock->customer_price, 2) }}</h6>
                                        <small class="text-muted">retail price</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($stock->current_stock <= 10)
                                        <span class="badge bg-danger text-white px-3 py-2">
                                            <i class="mdi mdi-alert-circle-outline mr-1"></i>Critical
                                        </span>
                                    @elseif($stock->current_stock <= 50)
                                        <span class="badge bg-warning text-white px-3 py-2">
                                            <i class="mdi mdi-alert-outline mr-1"></i>Medium
                                        </span>
                                    @else
                                        <span class="badge bg-success text-white px-3 py-2">
                                            <i class="mdi mdi-check-circle mr-1"></i>Good
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group-sm d-flex justify-content-center gap-1" role="group">
                                        <a href="{{ route('admin.depots.stocks.update-form', [$depot, $stock]) }}" 
                                           class="btn btn-outline-primary btn-sm" 
                                           data-bs-toggle="tooltip" 
                                           title="Update Stock">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        
                                        @if($stock->barcode_image)
                                        <button type="button" 
                                                class="btn btn-outline-info btn-sm" 
                                                onclick="downloadBarcode('{{ $stock->barcode_image }}', '{{ addslashes($stock->product_name) }}')"
                                                data-bs-toggle="tooltip" 
                                                title="Download Barcode">
                                            <i class="mdi mdi-download"></i>
                                        </button>
                                        @endif
                                        
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                onclick="deleteStock({{ $stock->id }}, '{{ addslashes($stock->product_name) }}')"
                                                data-bs-toggle="tooltip" 
                                                title="Delete Stock">
                                            <i class="mdi mdi-delete"></i>
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
</div>

@endsection

@push('style')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
/* Professional Typography & Spacing */
.font-monospace { font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace; }
.font-weight-600 { font-weight: 600; }
.font-weight-700 { font-weight: 700; }

/* Enhanced Professional Cards */
.card {
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.06);
    overflow: hidden;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
}

.shadow-sm {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
}

/* Premium Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-gradient-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Professional Table Design */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    padding: 1.25rem 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.8px;
    color: #374151;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table tbody td {
    padding: 1.25rem 1rem;
    border-top: 1px solid #f1f5f9;
    border-bottom: none;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
    transform: scale(1.01);
    transition: all 0.2s ease;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

/* Enhanced Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

/* Professional Button Styling */
.btn-outline-primary {
    border-color: #e5e7eb;
    color: #6b7280;
    background: white;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Action Buttons - Simplified Design */
.btn-group-sm .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    border-width: 1px;
    margin: 0 1px;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-outline-info {
    border-color: #17a2b8;
    color: #17a2b8;
}

.btn-outline-info:hover {
    background: #17a2b8;
    border-color: #17a2b8;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

.btn-outline-danger:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

/* Tooltip Enhancement */
.tooltip {
    font-size: 0.75rem;
}

.tooltip-inner {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}

/* Table Container with Higher Z-index */
.table-responsive {
    padding: 0.5rem;
    border-radius: 12px;
    background: #fafafa;
    position: relative;
    z-index: 1;
}

/* Fix DataTable wrapper z-index issues */
.dataTables_wrapper {
    position: relative;
    z-index: 1;
}

.dataTables_wrapper .dt-buttons {
    z-index: 100;
}

/* Avatar Enhancements */
.avatar-lg { height: 4rem; width: 4rem; }
.avatar-md { height: 3.5rem; width: 3.5rem; }
.avatar-sm { height: 2.75rem; width: 2.75rem; }

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
    border-radius: 50%;
    font-weight: 500;
}

/* Color Utilities */
.bg-white-alpha-25 { background-color: rgba(255,255,255,0.25); }
.text-white-75 { color: rgba(255,255,255,0.75); }
.text-white-50 { color: rgba(255,255,255,0.5); }

.bg-primary-subtle { background-color: rgba(79, 70, 229, 0.1); }
.bg-success-subtle { background-color: rgba(5, 150, 105, 0.1); }
.bg-warning-subtle { background-color: rgba(217, 119, 6, 0.1); }
.bg-danger-subtle { background-color: rgba(220, 38, 38, 0.1); }
.bg-info-subtle { background-color: rgba(8, 145, 178, 0.1); }

/* Table Enhancements */
.table-hover tbody tr:hover {
    background-color: rgba(79, 70, 229, 0.03);
    transition: background-color 0.15s ease-in-out;
}

.table thead th {
    border-bottom: 2px solid #e2e8f0;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.stock-row:hover .barcode-wrapper {
    background-color: #fff !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

.barcode-image {
    filter: contrast(1.1);
}

/* Badge Enhancements */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 500;
}

.badge-pill {
    border-radius: 50px;
}

/* Button Enhancements */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 6px;
}

.btn-rounded {
    border-radius: 50px;
}

/* DataTables Professional Styling */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    margin: 1rem 0;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.dt-buttons {
    margin-bottom: 1rem;
}

.dt-button {
    margin-right: 0.5rem;
    border-radius: 6px !important;
    font-size: 0.75rem !important;
    padding: 0.375rem 0.75rem !important;
}

/* Page Header */
.page-title-box {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 0;
}

.breadcrumb-item a {
    text-decoration: none;
    color: var(--medium-gray);
    transition: color 0.15s ease-in-out;
}

.breadcrumb-item a:hover {
    color: var(--primary-color);
}

.breadcrumb-item.active {
    font-weight: 600;
    color: var(--dark-gray);
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .page-title-right {
        text-align: center;
        margin-top: 1rem;
    }
    
    .btn-gradient-primary {
        width: 100%;
    }
    
    .card-header .row > div:last-child {
        text-align: center !important;
    }
}

/* Loading States */
.loading-overlay {
    position: relative;
}

.loading-overlay::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

/* Animation Enhancements */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
}

/* Print Styles */
@media print {
    .btn, .dropdown, .dt-buttons { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>
@endpush

@push('script')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize tooltips for action buttons
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize DataTable with professional settings
    var table = $('#stockTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"B>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="mdi mdi-file-excel mr-1"></i> Export Excel',
                className: 'btn btn-success btn-sm shadow-sm',
                exportOptions: {
                    columns: [0, 1, 3, 4, 5, 6, 7]
                },
                title: 'Stock Inventory - {{ $depot->depot_type }}',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row:first c', sheet).attr('s', '42');
                }
            },
            {
                extend: 'pdf',
                text: '<i class="mdi mdi-file-pdf mr-1"></i> Export PDF',
                className: 'btn btn-danger btn-sm shadow-sm',
                exportOptions: {
                    columns: [0, 1, 3, 4, 5, 6, 7]
                },
                title: 'Stock Inventory Report',
                messageTop: 'Stock inventory for {{ $depot->depot_type }} depot in {{ $depot->city }}, {{ $depot->state }}',
                customize: function(doc) {
                    doc.styles.title.fontSize = 20;
                    doc.styles.title.alignment = 'center';
                }
            },
            {
                extend: 'print',
                text: '<i class="mdi mdi-printer mr-1"></i> Print',
                className: 'btn btn-info btn-sm shadow-sm',
                exportOptions: {
                    columns: [0, 1, 3, 4, 5, 6, 7]
                },
                title: 'Stock Inventory Report'
            }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search inventory...",
            lengthMenu: "Display _MENU_ items per page",
            info: "Showing _START_ to _END_ of _TOTAL_ stock items",
            infoEmpty: "No stock items found",
            infoFiltered: "(filtered from _MAX_ total items)",
            paginate: {
                first: '<i class="mdi mdi-chevron-double-left"></i>',
                previous: '<i class="mdi mdi-chevron-left"></i>',
                next: '<i class="mdi mdi-chevron-right"></i>',
                last: '<i class="mdi mdi-chevron-double-right"></i>'
            },
            emptyTable: "No stock data available in depot",
            zeroRecords: "No matching stock items found"
        },
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 3, targets: -1 },
            { responsivePriority: 4, targets: 3 },
            { orderable: false, targets: [2, -1] }
        ],
        order: [[3, 'desc']],
        drawCallback: function() {
            // Add fade-in animation to rows
            $('.stock-row').addClass('fade-in');
        }
    });

    // Enhanced refresh functionality
    $('#refreshTable').click(function() {
        var $btn = $(this);
        var originalHtml = $btn.html();
        
        // Show loading state
        $btn.html('<i class="mdi mdi-loading mdi-spin mr-1"></i> Refreshing...').prop('disabled', true);
        
        // Simulate refresh (replace with actual AJAX call if needed)
        setTimeout(function() {
            table.draw(false);
            $btn.html(originalHtml).prop('disabled', false);
            
            Swal.fire({
                icon: 'success',
                title: 'Refreshed Successfully!',
                text: 'Stock inventory data has been updated.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#f8f9fa',
                color: '#495057'
            });
        }, 1000);
    });

    // Enhanced search functionality
    $('#stockTable_filter input').on('input', function() {
        var searchTerm = this.value;
        table.search(searchTerm).draw();
    });

    // Auto-refresh every 5 minutes
    setInterval(function() {
        table.draw(false);
        console.log('Stock data auto-refreshed');
    }, 300000);
});

// Download barcode function
function downloadBarcode(barcodeImage, productName) {
    if (!barcodeImage) {
        Swal.fire({
            icon: 'error',
            title: 'No Barcode Available',
            text: 'This product does not have a barcode image to download.',
            timer: 3000,
            showConfirmButton: false
        });
        return;
    }

    // Show loading state
    Swal.fire({
        title: 'Downloading Barcode...',
        text: 'Please wait while we prepare your barcode image.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    const imageUrl = `{{ env('IMG_URL') }}storage/depot_barcodes/${barcodeImage}`;
    
    // Create a temporary link element
    const link = document.createElement('a');
    link.href = imageUrl;
    link.download = `barcode_${productName.replace(/[^a-z0-9]/gi, '_').toLowerCase()}_${barcodeImage}`;
    link.target = '_blank';
    
    // Try to download using fetch for better error handling
    fetch(imageUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch barcode image');
            }
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            link.href = url;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
            
            Swal.fire({
                icon: 'success',
                title: 'Download Started!',
                text: `Barcode for ${productName} is being downloaded.`,
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(error => {
            console.error('Download error:', error);
            
            // Fallback: try direct link download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            Swal.fire({
                icon: 'info',
                title: 'Download Initiated',
                text: 'If download didn\'t start automatically, please check your browser settings.',
                timer: 3000,
                showConfirmButton: false
            });
        });
}

// Enhanced delete function with professional SweetAlert
function deleteStock(stockId, productName) {
    Swal.fire({
        title: 'Confirm Stock Deletion',
        html: `
            <div class="text-center">
                <div class="mb-3">
                    <i class="mdi mdi-delete-variant text-danger" style="font-size: 48px;"></i>
                </div>
                <p class="mb-2">You are about to permanently delete:</p>
                <h5 class="text-danger mb-3">${productName}</h5>
                <p class="text-muted small">This action cannot be undone and will remove all associated stock data.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="mdi mdi-delete mr-1"></i> Yes, Delete Stock',
        cancelButtonText: '<i class="mdi mdi-close mr-1"></i> Cancel',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger btn-lg me-2',
            cancelButton: 'btn btn-secondary btn-lg'
        },
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: () => {
            return fetch(`{{ route('admin.depots.stocks.destroy', [$depot, ':id']) }}`.replace(':id', stockId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`
                    <div class="text-danger">
                        <i class="mdi mdi-alert-circle mr-1"></i>
                        Failed to delete stock: ${error.message}
                    </div>
                `);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Stock Deleted Successfully!',
                html: `<p>The stock item <strong>${productName}</strong> has been removed from inventory.</p>`,
                timer: 3000,
                showConfirmButton: false,
                customClass: {
                    popup: 'animated fadeInUp'
                }
            }).then(() => {
                window.location.reload();
            });
        }
    });
}

// Professional toast notifications for session messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#f0fdf4',
        color: '#166534'
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#fef2f2',
        color: '#dc2626'
    });
@endif

// Add loading state to action buttons
$(document).on('click', '.dropdown-item', function(e) {
    if ($(this).attr('href') && $(this).attr('href') !== 'javascript:void(0);') {
        var $this = $(this);
        var originalHtml = $this.html();
        $this.html('<i class="mdi mdi-loading mdi-spin mr-2"></i>Loading...');
        
        setTimeout(function() {
            $this.html(originalHtml);
        }, 2000);
    }
});
</script>
@endpush