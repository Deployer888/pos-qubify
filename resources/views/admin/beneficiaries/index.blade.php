@extends('admin.layouts.master')
@section('title', 'Beneficiaries Management')

@push('style')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

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
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
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

.btn-gradient-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    color: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-gradient-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
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

/* Depot Badge Styling */
.badge-depot {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 4px;
}

/* Family Grouping Styles */
.customer-row[data-is-head="true"] {
    border-left: 4px solid #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
}

.customer-row[data-is-head="false"] {
    border-left: 2px solid #e5e7eb;
    background: rgba(0,0,0,0.01);
}

.table-hover tbody tr:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%) !important;
    transform: scale(1.005);
    transition: all 0.2s ease;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
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

.bg-primary-subtle { background-color: rgba(102, 126, 234, 0.1); }
.bg-success-subtle { background-color: rgba(5, 150, 105, 0.1); }
.bg-warning-subtle { background-color: rgba(217, 119, 6, 0.1); }
.bg-danger-subtle { background-color: rgba(220, 38, 38, 0.1); }
.bg-info-subtle { background-color: rgba(8, 145, 178, 0.1); }
.bg-secondary-subtle { background-color: rgba(108, 117, 125, 0.1); }

/* Enhanced Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    letter-spacing: 0.3px;
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

/* Button Groups */
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
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* DataTables Buttons */
.dt-buttons {
    margin-bottom: 15px;
}

.dt-button {
    border-radius: 8px !important;
    margin-right: 8px !important;
    border: none !important;
    padding: 8px 16px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
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

/* Responsive Enhancements */
@media (max-width: 768px) {
    .page-title-right {
        text-align: center;
        margin-top: 1rem;
    }
    
    .page-title-right .btn {
        width: 48%;
        margin: 0.25rem;
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
<!-- Page Header -->
<div class="page-title-box mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="page-title">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-muted">
                                    <i class="mdi mdi-home-outline"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Beneficiaries Management</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="page-title-right d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.beneficiaries.create') }}" 
                       class="btn btn-gradient-primary btn-rounded shadow-sm px-4 py-2">
                        <i class="mdi mdi-account-plus me-2"></i> Add Beneficiary
                    </a>
                    <button type="button" class="btn btn-gradient-success btn-rounded shadow-sm px-4 py-2" onclick="exportData()">
                        <i class="mdi mdi-file-excel me-2"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overview Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="bg-gradient-primary text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <div class="depot-icon me-3">
                                    <div class="avatar-lg">
                                        <div class="avatar-title rounded-circle bg-white-alpha-25">
                                            <i class="mdi mdi-account-group font-size-20 text-white"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1 font-weight-600">Beneficiaries Management System</h3>
                                    <p class="text-white-75 mb-0">
                                        <i class="mdi mdi-database-outline me-1"></i>
                                        Unified customer database across all depots
                                    </p>
                                    <small class="text-white-50">
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        Last updated: {{ now()->format('M d, Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end border-white-alpha-25 pe-3">
                                        <h2 class="text-white mb-0 font-weight-700">{{ $statistics['total_customers'] ?? 0 }}</h2>
                                        <p class="text-white-75 mb-0 font-size-13">Total Beneficiaries</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="ps-3">
                                        <h2 class="text-white mb-0 font-weight-700">{{ $statistics['total_families'] ?? 0 }}</h2>
                                        <p class="text-white-75 mb-0 font-size-13">Total Families</p>
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

<!-- Statistics Cards -->
<div class="row mb-4">
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
                        <h4 class="mb-1 font-weight-700">{{ $statistics['active_customers'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Active Beneficiaries</p>
                        <span class="text-success font-size-12">
                            <i class="mdi mdi-arrow-up me-1"></i>Eligible
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
                                <i class="mdi mdi-card-outline font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $statistics['customers_with_ration_cards'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">With Ration Cards</p>
                        <span class="text-info font-size-12">
                            <i class="mdi mdi-card-account-details me-1"></i>Documented
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
                            <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                <i class="mdi mdi-account-star font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ $statistics['family_heads'] ?? 0 }}</h4>
                        <p class="text-muted mb-0">Family Heads</p>
                        <span class="text-primary font-size-12">
                            <i class="mdi mdi-account-star me-1"></i>Primary Members
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
                                <i class="mdi mdi-store font-size-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1 font-weight-700">{{ count($statistics['depot_stats'] ?? []) }}</h4>
                        <p class="text-muted mb-0">Active Depots</p>
                        <span class="text-warning font-size-12">
                            <i class="mdi mdi-map-marker me-1"></i>Locations
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Beneficiaries Table -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-4">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="card-title mb-0 font-weight-600">
                            <i class="mdi mdi-account-group text-primary me-2"></i>
                            Beneficiaries Database
                        </h4>
                        <p class="text-muted mb-0 font-size-13">Manage customers across all depot locations</p>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-primary btn-sm" id="refreshTable">
                                <i class="mdi mdi-refresh me-1"></i> Refresh
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" id="toggleView">
                                <i class="mdi mdi-view-list me-1"></i> Toggle View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0" id="beneficiariesTable">
                        <thead class="table-dark">
                            <tr>
                                <th width="120px">
                                    <i class="mdi mdi-identifier me-1"></i>Family ID
                                </th>
                                <th width="200px">
                                    <i class="mdi mdi-account me-1"></i>Customer Details
                                </th>
                                <th width="180px">
                                    <i class="mdi mdi-store me-1"></i>Depot Information
                                </th>
                                <th width="120px">
                                    <i class="mdi mdi-card-account-details me-1"></i>Card Info
                                </th>
                                <th width="130px">
                                    <i class="mdi mdi-phone me-1"></i>Contact
                                </th>
                                <th class="text-center" width="100px">Status</th>
                                <th class="text-center" width="120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers ?? [] as $customer)
                            <tr class="customer-row" data-family-id="{{ $customer->family_id }}" data-is-head="{{ $customer->is_family_head ? 'true' : 'false' }}">
                                <td>
                                    <div class="family-info">
                                        <span class="badge bg-primary-subtle text-primary font-size-12 px-3 py-2 font-monospace">
                                            {{ $customer->family_id }}
                                        </span>
                                        @if($customer->is_family_head)
                                        <div class="mt-1">
                                            <small class="text-primary">
                                                <i class="mdi mdi-account-star me-1"></i>
                                                Family Head
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 flex-shrink-0">
                                            <div class="avatar-title rounded {{ $customer->is_family_head ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }}">
                                                <i class="mdi {{ $customer->is_family_head ? 'mdi-account-star' : 'mdi-account' }} font-size-16"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 font-size-15 font-weight-600">
                                                {{ $customer->name }}
                                                @if($customer->is_family_head)
                                                    <span class="badge bg-primary ms-1 px-2 py-1">Head</span>
                                                @endif
                                            </h6>
                                            <p class="text-muted mb-0 font-size-12">
                                                <i class="mdi mdi-identifier me-1"></i>
                                                ID: {{ $customer->id }} | Age: {{ $customer->age ?? 'N/A' }}
                                            </p>
                                            @if($customer->adhaar_no)
                                            <p class="text-muted mb-0 font-size-12 font-monospace">
                                                <i class="mdi mdi-card-account-details me-1"></i>
                                                Aadhaar: {{ substr($customer->adhaar_no, 0, 4) }}****{{ substr($customer->adhaar_no, -4) }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="depot-info">
                                        <div class="mb-2">
                                            <span class="badge-depot">
                                                {{ $customer->depot->depot_type ?? 'Unknown' }}
                                            </span>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="mdi mdi-map-marker-alt me-1"></i>
                                            {{ $customer->depot->city ?? 'N/A' }}, {{ $customer->depot->state ?? 'N/A' }}
                                        </div>
                                        @if($customer->depot->user)
                                        <div class="text-muted small">
                                            <i class="mdi mdi-account-tie me-1"></i>
                                            {{ $customer->depot->user->name }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="card-info">
                                        @if($customer->card_range)
                                        <div class="mb-1">
                                            <span class="badge bg-info-subtle text-info px-3 py-1">
                                                {{ $customer->card_range }}
                                            </span>
                                        </div>
                                        @endif
                                        <small class="text-muted font-monospace">
                                            {{ $customer->ration_card_no ?: 'No Card' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        @if($customer->mobile)
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-phone text-success me-2"></i>
                                                <span class="font-monospace">{{ $customer->mobile }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="mdi mdi-phone-off me-1"></i>No Contact
                                            </span>
                                        @endif
                                        @if($customer->address)
                                        <div class="text-muted small mt-1">
                                            <i class="mdi mdi-home-outline me-1"></i>
                                            {{ Str::limit($customer->address, 30) }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($customer->status === 'active')
                                        <span class="badge bg-success text-white px-3 py-2">
                                            <i class="mdi mdi-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-white px-3 py-2">
                                            <i class="mdi mdi-pause-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group-sm d-flex justify-content-center gap-1" role="group">
                                        <a href="{{ route('admin.beneficiaries.edit', $customer) }}" 
                                           class="btn btn-outline-primary btn-sm" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit Beneficiary">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                onclick="deleteBeneficiary({{ $customer->id }}, '{{ addslashes($customer->name) }}')"
                                                data-bs-toggle="tooltip" 
                                                title="Delete Beneficiary">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title rounded-circle bg-light text-muted">
                                                <i class="mdi mdi-account-group font-size-24"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No Beneficiaries Found</h5>
                                        <p class="text-muted mb-0">Start by adding your first beneficiary to the system.</p>
                                        <a href="{{ route('admin.beneficiaries.create') }}" class="btn btn-gradient-primary mt-3">
                                            <i class="mdi mdi-account-plus me-2"></i>Add First Beneficiary
                                        </a>
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

@push('script')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let beneficiariesTable;

$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize DataTable
    beneficiariesTable = $('#beneficiariesTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="mdi mdi-content-copy"></i> Copy',
                className: 'btn btn-secondary',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'csv',
                text: '<i class="mdi mdi-file-delimited"></i> CSV',
                className: 'btn btn-info',
                filename: function() {
                    return 'beneficiaries_' + moment().format('YYYY-MM-DD_HH-mm-ss');
                },
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, column, node) {
                            return $('<div>').html(data).text();
                        }
                    }
                }
            },
            {
                extend: 'excel',
                text: '<i class="mdi mdi-file-excel"></i> Excel',
                className: 'btn btn-success',
                filename: function() {
                    return 'beneficiaries_' + moment().format('YYYY-MM-DD_HH-mm-ss');
                },
                title: 'Beneficiaries Report',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, column, node) {
                            return $('<div>').html(data).text();
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                text: '<i class="mdi mdi-file-pdf"></i> PDF',
                className: 'btn btn-danger',
                filename: function() {
                    return 'beneficiaries_' + moment().format('YYYY-MM-DD_HH-mm-ss');
                },
                title: 'Beneficiaries Report',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
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
                text: '<i class="mdi mdi-printer"></i> Print',
                className: 'btn btn-secondary',
                title: 'Beneficiaries Report',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        columnDefs: [
            {
                targets: 6, // Actions column
                orderable: false,
                searchable: false
            },
            {
                targets: 5, // Status column
                className: 'text-center'
            }
        ],
        order: [[1, 'asc']], // Sort by customer name
        language: {
            search: "",
            searchPlaceholder: "Search beneficiaries...",
            lengthMenu: "Display _MENU_ beneficiaries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ beneficiaries",
            infoEmpty: "No beneficiaries found",
            infoFiltered: "(filtered from _MAX_ total beneficiaries)",
            paginate: {
                first: '<i class="mdi mdi-chevron-double-left"></i>',
                previous: '<i class="mdi mdi-chevron-left"></i>',
                next: '<i class="mdi mdi-chevron-right"></i>',
                last: '<i class="mdi mdi-chevron-double-right"></i>'
            },
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            zeroRecords: 'No matching beneficiaries found',
            emptyTable: 'No beneficiaries available'
        }
    });

    // Refresh table functionality
    $('#refreshTable').on('click', function() {
        beneficiariesTable.ajax.reload(null, false);
        
        // Show loading toast
        Swal.fire({
            title: 'Refreshing...',
            text: 'Updating beneficiaries data',
            icon: 'info',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });

    // Toggle view functionality
    $('#toggleView').on('click', function() {
        // This can be enhanced to toggle between different view modes
        const button = $(this);
        const icon = button.find('i');
        
        if (icon.hasClass('mdi-view-list')) {
            icon.removeClass('mdi-view-list').addClass('mdi-view-grid');
            button.find('span').text(' Grid View');
        } else {
            icon.removeClass('mdi-view-grid').addClass('mdi-view-list');
            button.find('span').text(' List View');
        }
    });

    // Add moment.js for date formatting in exports
    if (typeof moment === 'undefined') {
        $('<script>').attr('src', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js').appendTo('head');
    }
});

// Export data functionality
function exportData() {
    Swal.fire({
        title: 'Export Beneficiaries Data',
        text: 'Choose export format',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Excel',
        cancelButtonText: 'CSV',
        showDenyButton: true,
        denyButtonText: 'PDF'
    }).then((result) => {
        if (result.isConfirmed) {
            beneficiariesTable.button('.buttons-excel').trigger();
        } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
            beneficiariesTable.button('.buttons-csv').trigger();
        } else if (result.isDenied) {
            beneficiariesTable.button('.buttons-pdf').trigger();
        }
    });
}

// Delete beneficiary functionality
function deleteBeneficiary(customerId, customerName) {
    Swal.fire({
        title: 'Delete Beneficiary',
        html: `
            <div class="text-center">
                <div class="avatar-lg mx-auto mb-4">
                    <div class="avatar-title rounded-circle bg-danger-subtle text-danger">
                        <i class="mdi mdi-delete font-size-24"></i>
                    </div>
                </div>
                <p class="mb-2">You are about to permanently delete:</p>
                <h5 class="text-danger mb-3">${customerName}</h5>
                <p class="text-muted small">This action cannot be undone and will remove all beneficiary data and transaction history.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="mdi mdi-delete me-1"></i> Yes, Delete',
        cancelButtonText: '<i class="mdi mdi-close me-1"></i> Cancel',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: () => {
            return fetch(`{{ route('admin.beneficiaries.destroy', ':id') }}`.replace(':id', customerId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleted!',
                text: `${customerName} has been successfully deleted.`,
                icon: 'success',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            
            // Refresh the table
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    });
}
</script>
@endpush