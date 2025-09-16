@extends('admin.layouts.master')

@section('content')

<!-- Error Messages -->
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <div class="d-flex align-items-center">
        <div class="alert-icon me-3">
            <i class="mdi mdi-alert-circle font-size-20"></i>
        </div>
        <div>
            <h6 class="mb-1 font-weight-600">Please fix the following errors:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Page Header -->
<div class="page-title-box mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="page-title">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-muted">
                                    <i class="mdi mdi-home-outline"></i> Depots
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Depot Management</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="page-title-right d-flex justify-content-end">
                    <x-permissions.check permission="Add Depot">
                        <a href="{{ route('admin.depots.create') }}" 
                           class="btn btn-gradient-primary btn-rounded shadow-sm px-4 py-2">
                            <i class="mdi mdi-plus-circle me-2"></i> Add New Depot
                        </a>
                    </x-permissions.check>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-md">
                            <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                <i class="mdi mdi-warehouse font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $depots->total() }}</h4>
                        <p class="text-muted mb-0">Total Depots</p>
                        <span class="text-primary font-size-12">
                            <i class="mdi mdi-trending-up mr-1"></i>All Locations
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
                        <h4 class="mb-1 font-weight-700">{{ $depots->where('status', 'active')->count() }}</h4>
                        <p class="text-muted mb-0">Active Depots</p>
                        <span class="text-success font-size-12">
                            <i class="mdi mdi-arrow-up mr-1"></i>Operational
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
                                <i class="mdi mdi-pause-circle font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $depots->where('status', 'inactive')->count() }}</h4>
                        <p class="text-muted mb-0">Inactive Depots</p>
                        <span class="text-warning font-size-12">
                            <i class="mdi mdi-pause mr-1"></i>On Hold
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
                                <i class="mdi mdi-map-marker-multiple font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $depots->unique('state')->count() }}</h4>
                        <p class="text-muted mb-0">States Covered</p>
                        <span class="text-info font-size-12">
                            <i class="mdi mdi-map mr-1"></i>Geographic Reach
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Depot Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-4">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="card-title mb-0 font-weight-600">
                            <i class="mdi mdi-view-grid-outline text-primary mr-2"></i>
                            Depot Management System
                        </h4>
                        <p class="text-muted mb-0 font-size-13">Manage and monitor your depot network</p>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
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
                    <table class="table table-hover align-middle table-nowrap mb-0" id="depotTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="60px">ID</th>
                                <th width="180px">
                                    <i class="mdi mdi-warehouse mr-1"></i>Depot Details
                                </th>
                                <th width="150px">
                                    <i class="mdi mdi-account mr-1"></i>Manager
                                </th>
                                <th width="120px">
                                    <i class="mdi mdi-map-marker mr-1"></i>Location
                                </th>
                                <th class="text-center" width="100px">Status</th>
                                <th class="text-center" width="300px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse($depots as $depot)
                            <tr class="depot-row">
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary font-size-12 px-3 py-2">
                                        #{{ str_pad($i++, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-3 flex-shrink-0">
                                            <div class="avatar-title rounded bg-primary-subtle text-primary">
                                                <i class="mdi mdi-warehouse font-size-16"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 font-size-15 font-weight-600">{{ $depot->depot_type }} Depot</h6>
                                            <p class="text-muted mb-0 font-size-12">
                                                <i class="mdi mdi-clock-outline mr-1"></i>
                                                Established: {{ $depot->created_at->format('M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="manager-info">
                                        @if($depot->user)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs mr-2">
                                                    <div class="avatar-title rounded-circle bg-success-subtle text-success">
                                                        <i class="mdi mdi-account font-size-12"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-size-13">{{ $depot->user->name }}</h6>
                                                    <small class="text-muted">Manager</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="mdi mdi-account-off mr-1"></i>No Manager
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="location-info">
                                        <h6 class="mb-1 font-size-14 font-weight-600">{{ $depot->city }}</h6>
                                        <p class="text-muted mb-0 font-size-12">
                                            {{ $depot->statename->name ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($depot->status === 'active')
                                        <span class="badge bg-success text-white px-3 py-2">
                                            <i class="mdi mdi-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-white px-3 py-2">
                                            <i class="mdi mdi-pause-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group-sm d-flex justify-content-center gap-1 flex-wrap" role="group">
                                        <x-permissions.check permission="Manage Depot Stock">
                                            <a href="{{ route('admin.depots.stocks.index', $depot) }}" 
                                               class="btn btn-outline-info btn-sm mb-1" 
                                               data-bs-toggle="tooltip" 
                                               title="Manage Stock">
                                                <i class="mdi mdi-package-variant"></i>
                                            </a>
                                        </x-permissions.check>
                                        
                                        <x-permissions.check permission="Manage Depot Customers">
                                            <a href="{{ route('admin.depots.customers.index', $depot) }}" 
                                               class="btn btn-outline-warning btn-sm mb-1" 
                                               data-bs-toggle="tooltip" 
                                               title="Manage Customers">
                                                <i class="mdi mdi-account-group"></i>
                                            </a>
                                        </x-permissions.check>
                                        
                                        <x-permissions.check permission="Access Depot POS">
                                            <a href="{{ route('admin.depots.pos.index', $depot) }}" 
                                               class="btn btn-outline-success btn-sm mb-1" 
                                               data-bs-toggle="tooltip" 
                                               title="Point of Sale">
                                                <i class="mdi mdi-cart"></i>
                                            </a>
                                        </x-permissions.check>
                                        
                                        <x-permissions.check permission="Edit Depot">
                                            <a href="{{ route('admin.depots.edit', $depot) }}" 
                                               class="btn btn-outline-primary btn-sm mb-1" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit Depot">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </x-permissions.check>
                                        
                                        <x-permissions.check permission="Delete Depot">
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm mb-1"
                                                    onclick="deleteDepot({{ $depot->id }}, '{{ addslashes($depot->depot_type) }} - {{ addslashes($depot->city) }}')"
                                                    data-bs-toggle="tooltip" 
                                                    title="Delete Depot">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </x-permissions.check>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title rounded-circle bg-light text-muted">
                                                <i class="mdi mdi-warehouse font-size-24"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No Depots Found</h5>
                                        <p class="text-muted mb-0">Start by adding your first depot to the system.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
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

/* Avatar Enhancements */
.avatar-lg { height: 4rem; width: 4rem; }
.avatar-md { height: 3.5rem; width: 3.5rem; }
.avatar-sm { height: 2.75rem; width: 2.75rem; }
.avatar-xs { height: 2rem; width: 2rem; }

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
.bg-primary-subtle { background-color: rgba(102, 126, 234, 0.1); }
.bg-success-subtle { background-color: rgba(5, 150, 105, 0.1); }
.bg-warning-subtle { background-color: rgba(217, 119, 6, 0.1); }
.bg-danger-subtle { background-color: rgba(220, 38, 38, 0.1); }
.bg-info-subtle { background-color: rgba(8, 145, 178, 0.1); }

/* Enhanced Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

/* Action Buttons Enhancement */
.btn-group-sm .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    border-width: 1px;
    margin: 0 1px;
}

.btn-outline-info:hover {
    background: #17a2b8;
    border-color: #17a2b8;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
}

.btn-outline-warning:hover {
    background: #d97706;
    border-color: #d97706;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
}

.btn-outline-success:hover {
    background: #059669;
    border-color: #059669;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-outline-danger:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

/* Table Container Padding */
.table-responsive {
    padding: 0.5rem;
    border-radius: 12px;
    background: #fafafa;
    position: relative;
    z-index: 1;
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
    color: #64748b;
    transition: color 0.15s ease-in-out;
}

.breadcrumb-item a:hover {
    color: #667eea;
}

.breadcrumb-item.active {
    font-weight: 600;
    color: #334155;
}

/* Alert Enhancements */
.alert {
    border-radius: 12px;
    border: none;
}

.alert-danger {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    color: #dc2626;
}

.alert-icon {
    flex-shrink: 0;
}

/* Empty State */
.empty-state {
    padding: 3rem 0;
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
    
    .btn-group-sm {
        flex-direction: column;
        gap: 0.25rem !important;
    }
    
    .btn-group-sm .btn {
        margin: 0;
    }
}

/* Animation Enhancements */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.5s ease-out;
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
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Tooltip Enhancement */
.tooltip {
    font-size: 0.75rem;
}

.tooltip-inner {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}
</style>
@endpush

@push('script')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize tooltips for action buttons
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize DataTable
    var table = $('#depotTable').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
        language: {
            search: "",
            searchPlaceholder: "Search depots...",
            lengthMenu: "Display _MENU_ depots per page",
            info: "Showing _START_ to _END_ of _TOTAL_ depots",
            infoEmpty: "No depots found",
            infoFiltered: "(filtered from _MAX_ total depots)",
            paginate: {
                first: '<i class="mdi mdi-chevron-double-left"></i>',
                previous: '<i class="mdi mdi-chevron-left"></i>',
                next: '<i class="mdi mdi-chevron-right"></i>',
                last: '<i class="mdi mdi-chevron-double-right"></i>'
            },
            emptyTable: "No depot data available",
            zeroRecords: "No matching depots found"
        },
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 3, targets: -1 },
            { orderable: false, targets: [-1] }
        ],
        order: [[0, 'asc']],
        drawCallback: function() {
            $('.depot-row').addClass('fade-in');
        }
    });

    // Refresh functionality
    $('#refreshTable').click(function() {
        var $btn = $(this);
        var originalHtml = $btn.html();
        
        $btn.html('<i class="mdi mdi-loading mdi-spin mr-1"></i> Refreshing...').prop('disabled', true);
        
        setTimeout(function() {
            table.draw(false);
            $btn.html(originalHtml).prop('disabled', false);
            
            Swal.fire({
                icon: 'success',
                title: 'Refreshed Successfully!',
                text: 'Depot data has been updated.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#f8f9fa',
                color: '#495057'
            });
        }, 1000);
    });
});

// Delete depot function
function deleteDepot(depotId, depotName) {
    Swal.fire({
        title: 'Confirm Depot Deletion',
        html: `
            <div class="text-center">
                <div class="mb-3">
                    <i class="mdi mdi-warehouse-off text-danger" style="font-size: 48px;"></i>
                </div>
                <p class="mb-2">You are about to permanently delete:</p>
                <h5 class="text-danger mb-3">${depotName}</h5>
                <p class="text-muted small">This action cannot be undone and will remove all depot data including stocks, customers, and transaction history.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="mdi mdi-delete mr-1"></i> Yes, Delete Depot',
        cancelButtonText: '<i class="mdi mdi-close mr-1"></i> Cancel',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger btn-lg me-2',
            cancelButton: 'btn btn-secondary btn-lg'
        },
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: () => {
            return fetch(`{{ url('admin/depots') }}/${depotId}`, {
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
                        Failed to delete depot: ${error.message}
                    </div>
                `);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Depot Deleted Successfully!',
                html: `<p>The depot <strong>${depotName}</strong> has been removed from the system.</p>`,
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
</script>
@endpush