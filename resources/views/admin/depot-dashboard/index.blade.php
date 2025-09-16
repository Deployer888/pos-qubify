@extends('admin.layouts.master')

@section('title', 'Depot Dashboard')

@push('styles')
<style>
.dashboard-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: none;
    border-radius: 10px;
    overflow: hidden;
}

.dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.metric-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

.growth-indicator {
    font-size: 0.85rem;
    font-weight: 600;
}

.growth-positive {
    color: #28a745;
}

.growth-negative {
    color: #dc3545;
}

.chart-container {
    position: relative;
    height: 300px;
    margin: 20px 0;
}

.alert-item {
    border-left: 4px solid;
    padding: 12px 16px;
    margin-bottom: 10px;
    border-radius: 4px;
}

.alert-warning {
    border-left-color: #ffc107;
    background-color: #fff3cd;
}

.alert-danger {
    border-left-color: #dc3545;
    background-color: #f8d7da;
}

.alert-info {
    border-left-color: #17a2b8;
    background-color: #d1ecf1;
}

.depot-status-badge {
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 12px;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
}

.status-inactive {
    background-color: #f8d7da;
    color: #721c24;
}

.refresh-btn {
    transition: all 0.3s ease;
}

.refresh-btn:hover {
    transform: rotate(180deg);
}

/* Enhanced Professional Styling */
.dashboard-metric-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.revenue-growth-positive {
    color: #28a745;
    animation: pulse-green 2s infinite;
}

.revenue-growth-negative {
    color: #dc3545;
    animation: pulse-red 2s infinite;
}

@keyframes pulse-green {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

@keyframes pulse-red {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.data-table-wrapper {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.data-table-wrapper .table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}

.data-table-wrapper .table tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.alert-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@media (max-width: 768px) {
    .metric-value { font-size: 1.8rem; }
    .card-icon { font-size: 2rem; }
    .chart-container { height: 250px; }
}

.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Depot Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <button id="refresh-btn" class="btn btn-outline-primary btn-sm refresh-btn mr-2" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Depot Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(isset($error))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Warning!</strong> {{ $error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(Auth::user()->hasRole('Super Admin'))
                @include('admin.depot-dashboard.partials.super-admin-dashboard')
            @elseif(Auth::user()->hasRole('Depot Manager'))
                @include('admin.depot-dashboard.partials.depot-manager-dashboard')
            @else
                <div class="alert alert-danger">
                    <h4><i class="icon fa fa-ban"></i> Access Denied!</h4>
                    You don't have permission to access this dashboard.
                </div>
            @endif
        </div>
    </section>
</div>

<div id="last-updated" class="text-muted text-center mt-3">
    Last updated: {{ now()->format('M d, Y H:i') }}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dashboard refresh functionality
function refreshDashboard() {
    const refreshBtn = document.getElementById('refresh-btn');
    const refreshIcon = refreshBtn.querySelector('i');
    
    refreshBtn.disabled = true;
    refreshIcon.classList.add('fa-spin');
    
    // Show loading state
    showLoadingState();
    
    fetch('{{ route("admin.depot-dashboard.refresh") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update dashboard data without full page reload
            updateDashboardData(data.data);
            showSuccessMessage('Dashboard updated successfully');
        } else {
            showErrorMessage('Failed to refresh dashboard: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Failed to refresh dashboard');
    })
    .finally(() => {
        refreshBtn.disabled = false;
        refreshIcon.classList.remove('fa-spin');
        hideLoadingState();
    });
}

function showLoadingState() {
    document.querySelectorAll('.metric-value').forEach(el => {
        el.classList.add('loading-skeleton');
    });
}

function hideLoadingState() {
    document.querySelectorAll('.metric-value').forEach(el => {
        el.classList.remove('loading-skeleton');
    });
}

function updateDashboardData(data) {
    // Update metric values
    if (data.total_depots !== undefined) {
        const el = document.getElementById('total-depots');
        if (el) el.textContent = data.total_depots;
    }
    if (data.month_revenue_formatted !== undefined) {
        const el = document.getElementById('month-revenue');
        if (el) el.textContent = data.month_revenue_formatted;
    }
    if (data.revenue_growth_formatted !== undefined) {
        const growthEl = document.getElementById('revenue-growth');
        if (growthEl) {
            growthEl.textContent = data.revenue_growth_formatted;
            growthEl.className = data.revenue_growth >= 0 ? 'growth-indicator growth-positive' : 'growth-indicator growth-negative';
        }
    }
    
    // Update timestamp
    document.getElementById('last-updated').textContent = 'Last updated: ' + data.timestamp;
}

function showSuccessMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-check-circle mr-2"></i>${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

function showErrorMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-exclamation-circle mr-2"></i>${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// Auto-refresh every 5 minutes
setInterval(refreshDashboard, 300000);

// Initialize tooltips
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush