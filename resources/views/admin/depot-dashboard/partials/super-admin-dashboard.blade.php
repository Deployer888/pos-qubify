{{-- Super Admin Dashboard - Professional Government Interface --}}
@extends('admin.layouts.master')

@section('title', 'Central Depot Management System')

@push('style')
<style>
    :root {
        --gov-saffron: #FF9933;
        --gov-green: #138808;
        --gov-blue: #000080;
        --gov-white: #FFFFFF;
        --gov-light: #F8F9FA;
        --gov-dark: #495057;
        --gov-border: #DEE2E6;
        --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: var(--gov-light);
        color: var(--gov-dark);
        line-height: 1.6;
        font-size: 14px;
    }

    .header-banner {
        background: linear-gradient(135deg, var(--gov-saffron) 0%, var(--gov-green) 100%);
        color: white;
        padding: 1.5rem 0;
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 3px solid var(--gov-blue);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .gov-seal {
        width: 70px;
        height: 70px;
        background: var(--gov-white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--gov-saffron);
        font-weight: 800;
        box-shadow: var(--shadow-sm);
        flex-shrink: 0;
    }

    .header-text h1 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .header-text p {
        margin: 0;
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 500;
    }

    .dashboard-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        background: var(--gov-white);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        border-left: 5px solid var(--gov-saffron);
    }

    .page-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--gov-blue);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-description {
        color: var(--gov-dark);
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .breadcrumb-container {
        background: transparent;
        padding: 0;
        margin: 0;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        margin: 0 0.5rem;
        color: var(--gov-saffron);
        font-weight: 600;
        font-size: 1.1rem;
    }

    .breadcrumb-item.active {
        color: var(--gov-blue);
        font-weight: 600;
    }

    .breadcrumb-item a {
        color: var(--gov-saffron);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--gov-green);
        text-decoration: none;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .kpi-card {
        background: var(--gov-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gov-border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .kpi-card.total::before { background: var(--gov-blue); }
    .kpi-card.revenue::before { background: var(--gov-green); }
    .kpi-card.daily::before { background: var(--gov-saffron); }
    .kpi-card.managers::before { background: #6366F1; }

    .kpi-layout {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .kpi-details {
        flex: 1;
    }

    .kpi-number {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        color: var(--gov-blue);
        line-height: 1;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .kpi-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gov-dark);
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-meta {
        font-size: 0.875rem;
        color: #6B7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .kpi-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: white;
        flex-shrink: 0;
    }

    .kpi-icon.total { background: var(--gov-blue); }
    .kpi-icon.revenue { background: var(--gov-green); }
    .kpi-icon.daily { background: var(--gov-saffron); }
    .kpi-icon.managers { background: #6366F1; }

    .trend-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .trend-up {
        background: #D1FAE5;
        color: #065F46;
    }

    .trend-down {
        background: #FEE2E2;
        color: #991B1B;
    }

    .analytics-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .chart-container {
        background: var(--gov-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gov-border);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gov-light);
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gov-blue);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chart-canvas {
        position: relative;
        height: 350px;
    }

    .insights-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .insights-panel {
        background: var(--gov-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gov-border);
    }

    .panel-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gov-blue);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-matrix {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-cell {
        text-align: center;
        padding: 1.5rem;
        background: var(--gov-light);
        border-radius: 10px;
        border: 1px solid var(--gov-border);
        transition: all 0.2s ease;
    }

    .stat-cell:hover {
        background: var(--gov-white);
        box-shadow: var(--shadow-xs);
        transform: translateY(-1px);
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: var(--gov-blue);
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--gov-dark);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metrics-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .metric-cell {
        text-align: center;
        padding: 1.5rem;
        background: var(--gov-light);
        border-radius: 10px;
        border: 1px solid var(--gov-border);
    }

    .metric-number {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .metric-desc {
        font-size: 0.75rem;
        color: var(--gov-dark);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .alerts-section {
        background: var(--gov-white);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gov-border);
        overflow: hidden;
    }

    .alerts-header {
        background: linear-gradient(135deg, var(--gov-blue) 0%, #4F46E5 100%);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alerts-title {
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .alerts-count {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .alerts-list {
        max-height: 320px;
        overflow-y: auto;
    }

    .alert-row {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--gov-border);
        transition: background 0.2s ease;
    }

    .alert-row:hover {
        background: var(--gov-light);
    }

    .alert-row:last-child {
        border-bottom: none;
    }

    .alert-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-indicator {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .alert-warning .alert-indicator {
        background: #FEF3C7;
        color: #D97706;
    }

    .alert-danger .alert-indicator {
        background: #FEE2E2;
        color: #DC2626;
    }

    .alert-info .alert-indicator {
        background: #DBEAFE;
        color: #2563EB;
    }

    .alert-body {
        flex: 1;
    }

    .alert-title {
        font-weight: 700;
        color: var(--gov-blue);
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }

    .alert-message {
        color: var(--gov-dark);
        font-size: 0.85rem;
        margin-bottom: 0;
        line-height: 1.4;
    }

    .alert-timestamp {
        font-size: 0.75rem;
        color: #9CA3AF;
        font-weight: 500;
        margin-left: auto;
        flex-shrink: 0;
    }

    .empty-alerts {
        text-align: center;
        padding: 3rem 2rem;
        color: #9CA3AF;
    }

    .empty-alerts i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.6;
        color: var(--gov-green);
    }

    .data-section {
        background: var(--gov-white);
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gov-border);
        overflow: hidden;
    }

    .data-header {
        background: linear-gradient(135deg, var(--gov-blue) 0%, #4F46E5 100%);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .data-title {
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .export-action {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .export-action:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .data-table thead th {
        background: var(--gov-light);
        padding: 1rem 0.75rem;
        text-align: left;
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--gov-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--gov-border);
        white-space: nowrap;
    }

    .data-table tbody td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid var(--gov-border);
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: var(--gov-light);
    }

    .depot-id {
        font-weight: 800;
        color: var(--gov-blue);
        font-size: 0.9rem;
    }

    .location-cell {
        max-width: 200px;
    }

    .location-main {
        font-weight: 600;
        color: var(--gov-blue);
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
    }

    .location-sub {
        font-size: 0.75rem;
        color: #6B7280;
        line-height: 1.3;
    }

    .manager-cell {
        min-width: 120px;
    }

    .manager-name {
        font-weight: 600;
        color: var(--gov-blue);
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
    }

    .manager-status {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .assigned {
        color: var(--gov-green);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .unassigned {
        color: #DC2626;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-indicator {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }

    .status-inactive {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .count-indicator {
        padding: 0.375rem 0.75rem;
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 700;
        text-align: center;
        min-width: 40px;
        display: inline-block;
    }

    .customers { background: #DBEAFE; color: #1E40AF; }
    .stock-high { background: #D1FAE5; color: #065F46; }
    .stock-medium { background: #FEF3C7; color: #92400E; }
    .stock-low { background: #FEE2E2; color: #991B1B; }
    .sales { background: #EDE9FE; color: #5B21B6; }

    .revenue-cell {
        font-weight: 800;
        color: var(--gov-green);
        font-size: 0.9rem;
    }

    .actions-cell {
        display: flex;
        gap: 0.375rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .view-btn {
        background: var(--gov-white);
        color: var(--gov-blue);
        border-color: var(--gov-blue);
    }

    .view-btn:hover {
        background: var(--gov-blue);
        color: white;
    }

    .edit-btn {
        background: var(--gov-white);
        color: var(--gov-saffron);
        border-color: var(--gov-saffron);
    }

    .edit-btn:hover {
        background: var(--gov-saffron);
        color: white;
    }

    .empty-data {
        text-align: center;
        padding: 3rem 2rem;
        color: #9CA3AF;
    }

    .empty-data i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.6;
        color: var(--gov-saffron);
    }

    .floating-refresh {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--gov-saffron) 0%, var(--gov-green) 100%);
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 1.2rem;
        box-shadow: var(--shadow-lg);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
    }

    .floating-refresh:hover {
        transform: scale(1.1);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .debug-panel {
        position: fixed;
        bottom: 80px;
        right: 2rem;
        background: var(--gov-white);
        border: 2px solid #EF4444;
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.75rem;
        max-width: 280px;
        box-shadow: var(--shadow-lg);
        z-index: 1001;
        font-family: 'Courier New', monospace;
    }

    .debug-panel h6 {
        color: #EF4444;
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .debug-panel div {
        margin: 0.25rem 0;
        color: var(--gov-dark);
    }

    @media (max-width: 1200px) {
        .analytics-section,
        .insights-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 1rem;
        }

        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .page-title {
            font-size: 1.75rem;
        }

        .kpi-number {
            font-size: 2rem;
        }

        .chart-canvas {
            height: 280px;
        }

        .stats-matrix,
        .metrics-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .data-table {
            font-size: 0.75rem;
        }

        .data-table thead th,
        .data-table tbody td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Government Header Banner -->
<div class="header-banner">
    <div class="container">
        <div class="header-content">
            <div class="gov-seal">
                <i class="fas fa-university"></i>
            </div>
            <div class="header-text">
                <h1>Government of India</h1>
                <p>Central Depot Management System</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <!-- Page Header Section -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-cogs"></i>
            Central Administration Dashboard
        </div>
        
        <div class="page-description">
            Comprehensive oversight and management of all depot operations nationwide
        </div>
        
        <div class="breadcrumb-container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Central Depot Management
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="kpi-grid">
        <!-- Total Depots KPI -->
        <div class="kpi-card total">
            <div class="kpi-layout">
                <div class="kpi-details">
                    <div class="kpi-number" data-animate="{{ isset($dashboardData['total_depots']) ? $dashboardData['total_depots'] : 0 }}">
                        {{ isset($dashboardData['total_depots']) ? $dashboardData['total_depots'] : 0 }}
                    </div>
                    <div class="kpi-title">Total Depots</div>
                    <div class="kpi-meta">
                        <i class="fas fa-check-circle" style="color: var(--gov-green);"></i>
                        <span>{{ isset($dashboardData['active_depots']) ? $dashboardData['active_depots'] : 0 }} Active</span>
                        @if(isset($dashboardData['inactive_depots']) && $dashboardData['inactive_depots'] > 0)
                            <span style="color: #DC2626;">{{ $dashboardData['inactive_depots'] }} Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="kpi-icon total">
                    <i class="fas fa-warehouse"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue KPI -->
        <div class="kpi-card revenue">
            <div class="kpi-layout">
                <div class="kpi-details">
                    <div class="kpi-number" style="font-size: 1.75rem;">
                        {{ isset($dashboardData['month_revenue_formatted']) ? $dashboardData['month_revenue_formatted'] : '₹ 0.00' }}
                    </div>
                    <div class="kpi-title">Monthly Revenue</div>
                    <div class="kpi-meta">
                        @if(isset($dashboardData['revenue_growth']) && $dashboardData['revenue_growth'] !== null)
                            <span class="trend-badge {{ $dashboardData['revenue_growth'] >= 0 ? 'trend-up' : 'trend-down' }}">
                                {{ isset($dashboardData['revenue_growth_formatted']) ? $dashboardData['revenue_growth_formatted'] : '0%' }}
                            </span>
                        @endif
                        <small>from last month</small>
                    </div>
                </div>
                <div class="kpi-icon revenue">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
        </div>

        <!-- Daily Revenue KPI -->
        <div class="kpi-card daily">
            <div class="kpi-layout">
                <div class="kpi-details">
                    <div class="kpi-number" style="font-size: 1.75rem;">
                        {{ isset($dashboardData['today_revenue_formatted']) ? $dashboardData['today_revenue_formatted'] : '₹ 0.00' }}
                    </div>
                    <div class="kpi-title">Today's Revenue</div>
                    <div class="kpi-meta">
                        <i class="fas fa-shopping-cart"></i>
                        <span data-animate="{{ isset($dashboardData['daily_transactions']) ? $dashboardData['daily_transactions'] : 0 }}">{{ isset($dashboardData['daily_transactions']) ? $dashboardData['daily_transactions'] : 0 }}</span> 
                        <span>Transactions</span>
                    </div>
                </div>
                <div class="kpi-icon daily">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Depot Managers KPI -->
        <div class="kpi-card managers">
            <div class="kpi-layout">
                <div class="kpi-details">
                    <div class="kpi-number" data-animate="{{ isset($dashboardData['depot_managers']) ? $dashboardData['depot_managers'] : 0 }}">
                        {{ isset($dashboardData['depot_managers']) ? $dashboardData['depot_managers'] : 0 }}
                    </div>
                    <div class="kpi-title">Depot Managers</div>
                    <div class="kpi-meta">
                        @if(isset($dashboardData['inactive_depots']) && $dashboardData['inactive_depots'] > 0)
                            <i class="fas fa-user-times" style="color: #DC2626;"></i>
                            <span style="color: #DC2626;">{{ $dashboardData['inactive_depots'] }} Unassigned</span>
                        @else
                            <i class="fas fa-check-circle" style="color: var(--gov-green);"></i>
                            <span>All Assigned</span>
                        @endif
                    </div>
                </div>
                <div class="kpi-icon managers">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="analytics-section">
        <!-- Revenue Trend Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    Revenue Trend Analysis
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Stock Distribution Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Stock Distribution
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="stockChart"></canvas>
            </div>
        </div>
    </div>

    <!-- System Insights Section -->
    <div class="insights-grid">
        <!-- System Statistics Panel -->
        <div class="insights-panel">
            <div class="panel-title">
                <i class="fas fa-chart-bar"></i>
                System Statistics
            </div>
            
            <div class="stats-matrix">
                <div class="stat-cell">
                    <div class="stat-value">{{ isset($dashboardData['total_customers']) ? number_format($dashboardData['total_customers']) : '0' }}</div>
                    <div class="stat-label">Total Customers</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-value">{{ isset($dashboardData['total_stock_items']) ? number_format($dashboardData['total_stock_items']) : '0' }}</div>
                    <div class="stat-label">Stock Items</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-value">{{ isset($dashboardData['total_sales']) ? number_format($dashboardData['total_sales']) : '0' }}</div>
                    <div class="stat-label">Total Sales</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-value">{{ isset($dashboardData['active_depots'], $dashboardData['total_depots']) ? $dashboardData['active_depots'] . '/' . $dashboardData['total_depots'] : '0/0' }}</div>
                    <div class="stat-label">Active Ratio</div>
                </div>
            </div>

            <div class="metrics-row">
                <div class="metric-cell">
                    <div class="metric-number" style="color: var(--gov-green);">{{ isset($dashboardData['service_efficiency']) ? $dashboardData['service_efficiency'] : '95.2' }}%</div>
                    <div class="metric-desc">Service Efficiency</div>
                </div>
                <div class="metric-cell">
                    <div class="metric-number" style="color: var(--gov-saffron);">{{ isset($dashboardData['citizen_satisfaction']) ? $dashboardData['citizen_satisfaction'] : '4.7' }}/5</div>
                    <div class="metric-desc">Satisfaction</div>
                </div>
                <div class="metric-cell">
                    <div class="metric-number" style="color: var(--gov-blue);">{{ isset($dashboardData['avg_response_time']) ? $dashboardData['avg_response_time'] : '2.3' }}s</div>
                    <div class="metric-desc">Response Time</div>
                </div>
            </div>
        </div>

        <!-- Critical Alerts Panel -->
        <div class="alerts-section">
            <div class="alerts-header">
                <div class="alerts-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Critical Alerts
                </div>
                <div class="alerts-count">{{ isset($dashboardData['critical_alerts']) ? count($dashboardData['critical_alerts']) : 0 }}</div>
            </div>
            
            <div class="alerts-list">
                @if(isset($dashboardData['critical_alerts']) && !empty($dashboardData['critical_alerts']) && count($dashboardData['critical_alerts']) > 0)
                    @foreach($dashboardData['critical_alerts'] as $alert)
                        <div class="alert-row alert-{{ isset($alert['type']) ? $alert['type'] : 'info' }}">
                            <div class="alert-content">
                                <div class="alert-indicator">
                                    <i class="fas fa-{{ isset($alert['icon']) ? $alert['icon'] : 'info-circle' }}"></i>
                                </div>
                                <div class="alert-body">
                                    <div class="alert-title">{{ isset($alert['title']) ? $alert['title'] : 'Alert' }}</div>
                                    <div class="alert-message">{{ isset($alert['message']) ? $alert['message'] : 'No message available' }}</div>
                                </div>
                                <div class="alert-timestamp">{{ isset($alert['timestamp']) ? $alert['timestamp'] : 'Unknown' }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-alerts">
                        <i class="fas fa-check-circle"></i>
                        <h5>All Systems Operational</h5>
                        <p>No critical alerts at this time</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Depot Management Data Table -->
    <div class="data-section">
        <div class="data-header">
            <div class="data-title">
                <i class="fas fa-table"></i>
                Depot Management Overview
            </div>
            <button type="button" class="export-action" onclick="exportDepotData()">
                <i class="fas fa-download"></i>
                Export Report
            </button>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Depot ID</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th>Customers</th>
                        <th>Stock</th>
                        <th>Sales</th>
                        <th>Revenue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($dashboardData['all_depots']) && !empty($dashboardData['all_depots']) && $dashboardData['all_depots']->count() > 0)
                    
                        @foreach($dashboardData['all_depots'] as $depot)
                            <tr>
                                <td>
                                    <span class="depot-id">#{{ isset($depot->id) ? $depot->id : 'N/A' }}</span>
                                </td>
                                <td>{{ isset($depot->depot_type) ? $depot->depot_type : 'Fair Price Shop' }}</td>
                                <td class="location-cell">
                                    <div class="location-main">{{ isset($depot->city) ? $depot->city : 'N/A' }}, {{ isset($depot->state) ? $depot->state : 'N/A' }}</div>
                                    <div class="location-sub">{{ isset($depot->address) ? Str::limit($depot->address, 35) : 'Address not available' }}</div>
                                </td>
                                <td class="manager-cell">
                                    @if(isset($depot->user) && $depot->user)
                                        <div class="manager-name">{{ $depot->user->name }}</div>
                                        <div class="manager-status assigned">
                                            <i class="fas fa-check-circle"></i> Assigned
                                        </div>
                                    @else
                                        <div class="manager-status unassigned">
                                            <i class="fas fa-user-times"></i> Unassigned
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-indicator status-{{ isset($depot->status) ? $depot->status : 'inactive' }}">
                                        {{ isset($depot->status) ? ucfirst($depot->status) : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="count-indicator customers">{{ isset($depot->customers_count) ? $depot->customers_count : 0 }}</span>
                                </td>
                                <td>
                                    @php
                                        $stockLevel = isset($depot->stock_level) ? $depot->stock_level : 'medium';
                                        $stockClass = $stockLevel === 'low' ? 'stock-low' : ($stockLevel === 'high' ? 'stock-high' : 'stock-medium');
                                    @endphp
                                    <span class="count-indicator {{ $stockClass }}">
                                        {{ isset($depot->stocks_count) ? $depot->stocks_count : 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="count-indicator sales">{{ isset($depot->sales_count) ? $depot->sales_count : 0 }}</span>
                                </td>
                                <td>
                                    <span class="revenue-cell">{{ isset($depot->total_sales_formatted) ? $depot->total_sales_formatted : '₹ 0.00' }}</span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="#" class="action-btn view-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn edit-btn" title="Edit Depot">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="empty-data">
                                <i class="fas fa-inbox"></i>
                                <h5>No Depot Data Available</h5>
                                <p>Please add depots to see them here</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Floating Refresh Button -->
<button class="floating-refresh" onclick="refreshDashboard()" title="Refresh Dashboard Data">
    <i class="fas fa-sync-alt"></i>
</button>

@if(config('app.debug'))
<!-- Debug Panel (Only shown in debug mode) -->
<div class="debug-panel">
    <h6>🐛 DEBUG INFO</h6>
    <div><strong>Data Status:</strong> {{ isset($dashboardData) ? 'Available' : 'Missing' }}</div>
    @if(isset($dashboardData))
        <div><strong>Total Depots:</strong> {{ isset($dashboardData['total_depots']) ? $dashboardData['total_depots'] : 'null' }}</div>
        <div><strong>Active Depots:</strong> {{ isset($dashboardData['active_depots']) ? $dashboardData['active_depots'] : 'null' }}</div>
        <div><strong>Month Revenue:</strong> {{ isset($dashboardData['month_revenue']) ? $dashboardData['month_revenue'] : 'null' }}</div>
        <div><strong>Depot Collection:</strong> {{ isset($dashboardData['all_depots']) ? $dashboardData['all_depots']->count() . ' items' : 'null' }}</div>
        <div><strong>Alerts:</strong> {{ isset($dashboardData['critical_alerts']) ? count($dashboardData['critical_alerts']) : 'null' }}</div>
    @endif
    <div><strong>View:</strong> super-admin-dashboard</div>
</div>
@endif
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Dashboard Initialized');
    
    // Debug: Log received data
    const dashboardData = @json($dashboardData ?? []);
    console.log('📊 Dashboard Data:', dashboardData);

    // Government color scheme
    const govColors = {
        saffron: '#FF9933',
        green: '#138808',
        blue: '#000080',
        white: '#FFFFFF',
        gray: '#6B7280'
    };

    // Initialize Revenue Chart
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        const revenueLabels = dashboardData.revenue_labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const revenueData = dashboardData.revenue_data || [0, 0, 0, 0, 0, 0];

        console.log('📈 Revenue Chart Data:', { labels: revenueLabels, data: revenueData });

        new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Monthly Revenue (₹)',
                    data: revenueData,
                    borderColor: govColors.green,
                    backgroundColor: 'rgba(19, 136, 8, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: govColors.green,
                    pointBorderColor: govColors.white,
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 12, weight: 'bold' },
                            color: govColors.blue,
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 128, 0.95)',
                        titleColor: govColors.white,
                        bodyColor: govColors.white,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: ₹' + context.parsed.y.toLocaleString('en-IN');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.1)' },
                        ticks: {
                            color: govColors.blue,
                            font: { weight: 'bold' },
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            }
                        }
                    },
                    x: {
                        grid: { color: 'rgba(0, 0, 0, 0.1)' },
                        ticks: {
                            color: govColors.blue,
                            font: { weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    // Initialize Stock Chart
    const stockCanvas = document.getElementById('stockChart');
    if (stockCanvas) {
        const stockLabels = dashboardData.stock_distribution_labels || ['No Data'];
        const stockData = dashboardData.stock_distribution_data || [0];

        console.log('📊 Stock Chart Data:', { labels: stockLabels, data: stockData });

        if (stockLabels.length > 0 && stockData.reduce((a, b) => a + b, 0) > 0) {
            new Chart(stockCanvas, {
                type: 'doughnut',
                data: {
                    labels: stockLabels,
                    datasets: [{
                        data: stockData,
                        backgroundColor: [
                            govColors.blue, govColors.green, govColors.saffron, '#DC2626',
                            '#7C3AED', '#059669', '#EA580C', '#0891B2'
                        ],
                        borderWidth: 3,
                        borderColor: govColors.white,
                        hoverBorderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: { size: 11, weight: 'bold' },
                                color: govColors.blue
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 128, 0.95)',
                            titleColor: govColors.white,
                            bodyColor: govColors.white,
                            cornerRadius: 8,
                            padding: 12
                        }
                    }
                }
            });
        } else {
            stockCanvas.parentElement.innerHTML = '<div class="empty-alerts"><i class="fas fa-chart-pie"></i><p>No stock data available</p></div>';
        }
    }

    // Animate counters
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target.toLocaleString('en-IN');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start).toLocaleString('en-IN');
            }
        }, 16);
    }

    // Start animations
    setTimeout(() => {
        document.querySelectorAll('[data-animate]').forEach(element => {
            const target = parseInt(element.getAttribute('data-animate'));
            if (target > 0) {
                element.textContent = '0';
                animateCounter(element, target);
            }
        });
    }, 600);

    // Add hover effects
    document.querySelectorAll('.kpi-card').forEach(card => {
        card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-4px)');
        card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
    });

    console.log('✅ Dashboard Ready');
});

// Export Function
function exportDepotData() {
    try {
        const dashboardData = @json($dashboardData ?? []);
        const depots = dashboardData.all_depots || [];
        
        if (!depots || depots.length === 0) {
            alert('❌ No depot data available for export.');
            return;
        }
        
        console.log('📤 Exporting', depots.length, 'depot records');
        
        let csvContent = 'Depot ID,Type,City,State,Manager,Status,Customers,Stock Items,Sales,Revenue\n';
        
        depots.forEach(depot => {
            const row = [
                depot.id || 'N/A',
                `"${depot.depot_type || 'Fair Price Shop'}"`,
                `"${depot.city || 'N/A'}"`,
                `"${depot.state || 'N/A'}"`,
                `"${depot.user ? depot.user.name : 'Unassigned'}"`,
                `"${depot.status || 'inactive'}"`,
                depot.customers_count || 0,
                depot.stocks_count || 0,
                depot.sales_count || 0,
                `"${depot.total_sales_formatted || '₹ 0.00'}"`
            ].join(',');
            csvContent += row + '\n';
        });
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `depot-management-report-${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        console.log('✅ Export completed successfully');
    } catch (error) {
        console.error('❌ Export failed:', error);
        alert('Export failed. Please check the console for details.');
    }
}

// Refresh Function
function refreshDashboard() {
    const btn = document.querySelector('.floating-refresh');
    const icon = btn.querySelector('i');
    
    console.log('🔄 Refreshing dashboard...');
    icon.style.animation = 'spin 1s linear infinite';
    
    fetch(window.location.href, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        console.log('✅ Refresh successful');
        window.location.reload();
    })
    .catch(error => {
        console.error('❌ Refresh failed:', error);
        setTimeout(() => window.location.reload(), 1000);
    });
}

// Add CSS animations
const styles = document.createElement('style');
styles.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(styles);
</script>
@endpush