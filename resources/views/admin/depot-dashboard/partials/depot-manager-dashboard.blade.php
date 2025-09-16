{{-- Government Fair Price Shop/Ration Depot Dashboard --}}
@extends('admin.layouts.master')

@section('title', 'Fair Price Shop Dashboard')

@push('style')
<style>
    :root {
        --gov-primary: #FF9933;    /* Saffron */
        --gov-secondary: #138808;  /* Green */
        --gov-blue: #000080;       /* Navy Blue */
        --gov-white: #FFFFFF;
        --gov-light-gray: #F8F9FA;
        --gov-dark-gray: #495057;
        --gov-border: #DEE2E6;
        --shadow-light: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-medium: 0 4px 8px rgba(0,0,0,0.15);
    }

    body {
        font-family: 'Segoe UI', 'Arial', sans-serif;
        background-color: var(--gov-light-gray);
        color: var(--gov-dark-gray);
    }

    .gov-header {
        background: linear-gradient(135deg, var(--gov-primary) 0%, var(--gov-secondary) 100%);
        color: white;
        padding: 1rem 0;
        box-shadow: var(--shadow-medium);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .gov-logo-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .gov-emblem {
        width: 60px;
        height: 60px;
        background: var(--gov-white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--gov-primary);
        font-weight: bold;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        background: var(--gov-white);
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-light);
        border-left: 4px solid var(--gov-primary);
    }

    .depot-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gov-blue);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .depot-subtitle {
        color: var(--gov-dark-gray);
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .depot-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background: var(--gov-light-gray);
        border-radius: 6px;
    }

    .detail-icon {
        color: var(--gov-primary);
        width: 20px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-operational {
        background: #D4EDDA;
        color: #155724;
        border: 1px solid #C3E6CB;
    }

    .status-inactive {
        background: #F8D7DA;
        color: #721C24;
        border: 1px solid #F5C6CB;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: var(--gov-white);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--gov-border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .metric-card.beneficiaries::before {
        background: var(--gov-blue);
    }

    .metric-card.commodities::before {
        background: var(--gov-secondary);
    }

    .metric-card.distribution::before {
        background: var(--gov-primary);
    }

    .metric-card.revenue::before {
        background: #6F42C1;
    }

    .metric-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }

    .metric-icon.beneficiaries {
        background: var(--gov-blue);
    }

    .metric-icon.commodities {
        background: var(--gov-secondary);
    }

    .metric-icon.distribution {
        background: var(--gov-primary);
    }

    .metric-icon.revenue {
        background: #6F42C1;
    }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gov-blue);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .metric-label {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gov-dark-gray);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-subtitle {
        font-size: 0.9rem;
        color: #6C757D;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .analytics-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: var(--gov-white);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--gov-border);
    }

    .chart-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gov-light-gray);
    }

    .chart-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--gov-blue);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 1rem;
    }

    .chart-legend {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.25rem;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        flex-shrink: 0;
    }

    .performance-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .performance-item {
        text-align: center;
        padding: 1.5rem;
        background: var(--gov-light-gray);
        border-radius: 8px;
        border: 1px solid var(--gov-border);
    }

    .performance-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .performance-label {
        font-size: 0.9rem;
        color: var(--gov-dark-gray);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .activity-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .activity-card {
        background: var(--gov-white);
        border-radius: 8px;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--gov-border);
        overflow: hidden;
    }

    .activity-header {
        background: linear-gradient(135deg, var(--gov-blue) 0%, #4A5568 100%);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-title {
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .view-all-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .view-all-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
    }

    .activity-table {
        width: 100%;
        border-collapse: collapse;
    }

    .activity-table thead th {
        background: var(--gov-light-gray);
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--gov-dark-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--gov-border);
    }

    .activity-table tbody td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--gov-border);
        font-size: 0.9rem;
    }

    .activity-table tbody tr:hover {
        background: var(--gov-light-gray);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6C757D;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        color: var(--gov-primary);
    }

    .empty-state p {
        font-size: 1rem;
        font-weight: 500;
    }

    .govt-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-active {
        background: #D4EDDA;
        color: #155724;
        border: 1px solid #C3E6CB;
    }

    .badge-inactive {
        background: #F8D7DA;
        color: #721C24;
        border: 1px solid #F5C6CB;
    }

    .badge-pending {
        background: #FFF3CD;
        color: #856404;
        border: 1px solid #FFEAA7;
    }

    .no-depot-container {
        background: var(--gov-white);
        border-radius: 8px;
        padding: 3rem;
        text-align: center;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--gov-border);
        margin-top: 2rem;
    }

    .no-depot-icon {
        font-size: 4rem;
        color: var(--gov-primary);
        margin-bottom: 1rem;
    }

    .no-depot-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--gov-blue);
        margin-bottom: 1rem;
    }

    .no-depot-message {
        color: var(--gov-dark-gray);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .contact-btn {
        background: linear-gradient(135deg, var(--gov-primary) 0%, var(--gov-secondary) 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .contact-btn:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
        color: white;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .analytics-section,
        .activity-section {
            grid-template-columns: 1fr;
        }

        .depot-details {
            grid-template-columns: 1fr;
        }

        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .depot-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .metrics-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Government Header -->
<div class="gov-header">
    <div class="container">
        <div class="gov-logo-section">
            <div class="gov-emblem">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <h3 class="mb-0">Government of India</h3>
                <p class="mb-0">Ministry of Food and Public Distribution</p>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">
    @if(isset($stats['assigned_depot']) && $stats['assigned_depot'])
        <!-- Page Header -->
        <div class="page-header">
            <div class="depot-title">
                <i class="fas fa-store"></i>
                Fair Price Shop
                <span class="status-badge {{ ($stats['assigned_depot']->status ?? 'inactive') === 'active' ? 'status-operational' : 'status-inactive' }}">
                    <i class="fas fa-{{ ($stats['assigned_depot']->status ?? 'inactive') === 'active' ? 'check-circle' : 'times-circle' }}"></i>
                    {{ ($stats['assigned_depot']->status ?? 'inactive') === 'active' ? 'Operational' : 'Inactive' }}
                </span>
            </div>
            
            <div class="depot-subtitle">
                Shop Count: {{ $stats['assigned_depot']->id ?? 'N/A' }} | {{ $stats['assigned_depot']->depot_type ?? 'Fair Price Shop' }}
            </div>
            
            <div class="depot-details">
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt detail-icon"></i>
                    <span>{{ $stats['assigned_depot']->address ?? 'Address not available' }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-city detail-icon"></i>
                    <span>{{ $stats['assigned_depot']->city ?? 'City' }}, {{ $stats['assigned_depot']->state ?? 'State' }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-user-tie detail-icon"></i>
                    <span>Shop Keeper: {{ auth()->user()->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <i class="fas fa-calendar detail-icon"></i>
                    <span>Today: {{ now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card beneficiaries">
                <div class="metric-header">
                    <div>
                        <div class="metric-value">{{ $stats['customer_count'] ?? 0 }}</div>
                        <div class="metric-label">Beneficiaries</div>
                        <div class="metric-subtitle">
                            <i class="fas fa-user-check"></i>
                            <span>{{ $stats['active_customers_count'] ?? 0 }} Active</span>
                        </div>
                    </div>
                    <div class="metric-icon beneficiaries">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="metric-card commodities">
                <div class="metric-header">
                    <div>
                        <div class="metric-value">{{ $stats['stock_items_count'] ?? 0 }}</div>
                        <div class="metric-label">Commodities</div>
                        <div class="metric-subtitle">
                            @if(($stats['low_stock_items'] ?? 0) > 0)
                                <i class="fas fa-exclamation-triangle" style="color: #DC3545;"></i>
                                <span style="color: #DC3545;">{{ $stats['low_stock_items'] }} Low Stock</span>
                            @else
                                <i class="fas fa-check-circle" style="color: var(--gov-secondary);"></i>
                                <span>Stock Adequate</span>
                            @endif
                        </div>
                    </div>
                    <div class="metric-icon commodities">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>

            <div class="metric-card distribution">
                <div class="metric-header">
                    <div>
                        <div class="metric-value">{{ $stats['total_sales'] ?? 0 }}</div>
                        <div class="metric-label">Distributions</div>
                        <div class="metric-subtitle">
                            <i class="fas fa-chart-line"></i>
                            <span>{{ $stats['sales_growth'] ?? 0 }}% Growth</span>
                        </div>
                    </div>
                    <div class="metric-icon distribution">
                        <i class="fas fa-hand-holding"></i>
                    </div>
                </div>
            </div>

            <div class="metric-card revenue">
                <div class="metric-header">
                    <div>
                        <div class="metric-value">₹{{ number_format($stats['total_sales_amount'] ?? 0, 2) }}</div>
                        <div class="metric-label">Revenue</div>
                        <div class="metric-subtitle">
                            <i class="fas fa-calendar"></i>
                            <span>₹{{ number_format($stats['month_revenue'] ?? 0, 2) }} This Month</span>
                        </div>
                    </div>
                    <div class="metric-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Section -->
        <div class="analytics-section">
            <!-- Stock Distribution Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Commodity Distribution
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="stockChart"></canvas>
                </div>
                <div class="chart-legend" id="stockLegend">
                    <!-- Legend will be populated by JavaScript -->
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        Performance Indicators
                    </div>
                </div>
                <div class="performance-grid">
                    <div class="performance-item">
                        <div class="performance-value" style="color: var(--gov-blue);">{{ $stats['customer_growth'] ?? 0 }}%</div>
                        <div class="performance-label">Beneficiary Growth</div>
                    </div>
                    <div class="performance-item">
                        <div class="performance-value" style="color: var(--gov-secondary);">{{ $stats['sales_growth'] ?? 0 }}%</div>
                        <div class="performance-label">Distribution Growth</div>
                    </div>
                    <div class="performance-item" style="grid-column: 1 / -1;">
                        <div class="performance-value" style="color: var(--gov-primary);">₹{{ number_format($stats['today_revenue'] ?? 0, 2) }}</div>
                        <div class="performance-label">Today's Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="activity-section">
            <!-- Recent Distributions -->
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title">
                        <i class="fas fa-hand-holding"></i>
                        Recent Distributions
                    </div>
                    <a href="#" class="view-all-btn">
                        <i class="fas fa-eye"></i> View All
                    </a>
                </div>
                @if(isset($recentSales) && $recentSales->count() > 0)
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Receipt No.</th>
                                <th>Beneficiary</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales->take(5) as $sale)
                                <tr>
                                    <td><strong>{{ $sale->invoice_no ?? 'N/A' }}</strong></td>
                                    <td>{{ optional($sale->customer)->name ?? 'N/A' }}</td>
                                    <td><strong style="color: var(--gov-secondary);">₹{{ number_format($sale->total ?? 0, 2) }}</strong></td>
                                    <td><small>{{ $sale->created_at->format('d/m/Y H:i') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="fas fa-hand-holding"></i>
                        <p>No Recent Distributions</p>
                    </div>
                @endif
            </div>

            <!-- Recent Beneficiaries -->
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title">
                        <i class="fas fa-users"></i>
                        New Beneficiaries
                    </div>
                    <a href="#" class="view-all-btn">
                        <i class="fas fa-eye"></i> View All
                    </a>
                </div>
                @if(isset($stats['recent_customers']) && $stats['recent_customers']->count() > 0)
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Registration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_customers']->take(5) as $customer)
                                <tr>
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                        @if($customer->is_family_head ?? false)
                                            <span class="govt-badge badge-active">Head</span>
                                        @endif
                                    </td>
                                    <td>{{ $customer->mobile ?? 'N/A' }}</td>
                                    <td>
                                        <span class="govt-badge badge-{{ ($customer->status ?? 'inactive') === 'active' ? 'active' : 'inactive' }}">
                                            {{ ($customer->status ?? 'inactive') === 'active' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td><small>{{ $customer->created_at->format('d/m/Y') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <p>No New Beneficiaries</p>
                    </div>
                @endif
            </div>
        </div>

    @else
        <!-- No Depot Assigned Message -->
        <div class="no-depot-container">
            <div class="no-depot-icon">
                <i class="fas fa-store-slash"></i>
            </div>
            <div class="no-depot-title">
                No Shop Assigned
            </div>
            <div class="no-depot-message">
                {{ $stats['no_depot_message'] ?? 'आपके खाते में कोई उचित मूल्य दुकान आवंटित नहीं है। कृपया प्रशासक से संपर्क करें। | No Fair Price Shop is assigned to your account. Please contact the administrator.' }}
            </div>
            <a href="mailto:admin@example.com" class="contact-btn">
                <i class="fas fa-envelope"></i> Contact Administrator
            </a>
        </div>
    @endif
</div>
@endsection

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($stats['assigned_depot']) && $stats['assigned_depot'])
        // Prepare stock data - focusing on government ration items
        const stockSummary = @json($stats['stock_summary'] ?? []);
        const stockData = [];
        
        // Government ration items mapping
        const rationItems = {
            'Kg': ['चावल | Rice', 'गेहूं | Wheat', 'चीनी | Sugar', 'दाल | Pulses'],
            'Ltr': ['केरोसिन | Kerosene', 'खाना पकाने का तेल | Cooking Oil'],
            'Piece': ['नमक | Salt', 'मसाले | Spices']
        };
        
        // Get individual stock items from depot
        @if(isset($stats['assigned_depot']->stocks) && $stats['assigned_depot']->stocks->count() > 0)
            const individualStocks = @json($stats['assigned_depot']->stocks->take(8));
            individualStocks.forEach(stock => {
                stockData.push({
                    name: stock.product_name,
                    count: parseInt(stock.current_stock),
                    unit: stock.measurement_unit
                });
            });
        @endif

        // Fallback data if no individual stocks - use stock summary
        if (stockData.length === 0 && stockSummary.length > 0) {
            stockSummary.forEach(item => {
                stockData.push({
                    name: item.measurement_unit + ' वस्तुएं | ' + item.measurement_unit + ' Items',
                    count: parseInt(item.total_quantity),
                    unit: item.measurement_unit
                });
            });
        }

        // Final fallback - sample government ration data
        if (stockData.length === 0) {
            stockData.push(
                { name: 'चावल | Rice', count: 500, unit: 'Kg' },
                { name: 'गेहूं | Wheat', count: 450, unit: 'Kg' },
                { name: 'चीनी | Sugar', count: 200, unit: 'Kg' },
                { name: 'दाल | Pulses', count: 150, unit: 'Kg' },
                { name: 'केरोसिन | Kerosene', count: 100, unit: 'Ltr' },
                { name: 'नमक | Salt', count: 50, unit: 'Kg' }
            );
        }

        // Government-appropriate colors (tricolor inspired)
        const colors = [
            '#FF9933', // Saffron
            '#FFFFFF', // White (will be changed to light gray for visibility)
            '#138808', // Green
            '#000080', // Navy Blue
            '#FFD700', // Gold
            '#8B4513', // Brown
            '#FF6347', // Tomato
            '#4682B4'  // Steel Blue
        ];

        // Adjust white color for visibility
        colors[1] = '#E0E0E0';

        const ctx = document.getElementById('stockChart');
        if (ctx && stockData.length > 0) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: stockData.map(item => item.name),
                    datasets: [{
                        data: stockData.map(item => item.count),
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#FFFFFF',
                        hoverBorderWidth: 3,
                        hoverBorderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            cornerRadius: 6,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const item = stockData[context.dataIndex];
                                    return `${item.name}: ${item.count} ${item.unit}`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 1500
                    }
                }
            });

            // Create custom legend with government styling
            const legendContainer = document.getElementById('stockLegend');
            if (legendContainer) {
                legendContainer.innerHTML = '';
                stockData.forEach((item, index) => {
                    const legendItem = document.createElement('div');
                    legendItem.className = 'legend-item';
                    legendItem.innerHTML = `
                        <div class="legend-color" style="background-color: ${colors[index % colors.length]}"></div>
                        <span>${item.name} (${item.count} ${item.unit})</span>
                    `;
                    legendContainer.appendChild(legendItem);
                });
            }
        } else {
            // Show no data message
            const chartContainer = document.querySelector('#stockChart').parentElement;
            chartContainer.innerHTML = '<div class="empty-state"><i class="fas fa-chart-pie"></i><p>कोई स्टॉक डेटा उपलब्ध नहीं | No Stock Data Available</p></div>';
        }
    @endif

    // Add smooth hover effects for metric cards
    document.querySelectorAll('.metric-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Counter animation for metric values
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const currentValue = Math.floor(progress * (end - start) + start);
            
            // Handle currency formatting
            if (element.textContent.includes('₹')) {
                element.innerHTML = '₹' + currentValue.toLocaleString('en-IN');
            } else {
                element.innerHTML = currentValue.toLocaleString('en-IN');
            }
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // Animate metric values on load
    setTimeout(() => {
        const metricValues = document.querySelectorAll('.metric-value');
        metricValues.forEach((element, index) => {
            const text = element.textContent;
            const numericValue = parseInt(text.replace(/[₹,]/g, ''));
            
            if (!isNaN(numericValue) && numericValue > 0) {
                if (text.includes('₹')) {
                    element.textContent = '₹0';
                } else {
                    element.textContent = '0';
                }
                
                setTimeout(() => {
                    animateValue(element, 0, numericValue, 2000);
                }, index * 300);
            }
        });
    }, 500);
});
</script>
@endpush