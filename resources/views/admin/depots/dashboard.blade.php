@extends('admin.layouts.master')

@section('title', 'Depot Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ $title ?? 'Depot Dashboard' }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Total Depots</h5>
                <h3>{{ $stats['total_depots'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Active Depots</h5>
                <h3>{{ $stats['active_depots'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Customers</h5>
                <h3>{{ $stats['total_customers'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Total Stock</h5>
                <h3>{{ number_format($stats['total_stocks'] ?? 0) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Today's Revenue</h5>
                <h3>₹ {{ number_format($stats['today_revenue'] ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>This Month</h5>
                <h3>₹ {{ number_format($stats['month_revenue'] ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Total Sales</h5>
                <h3>{{ $stats['total_sales'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5>Low Stock Threshold</h5>
                <h3>{{ $lowStockThreshold ?? 10 }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Revenue - Last 30 Days</div>
            <div class="card-body">
                <canvas id="salesTrend" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Stock Distribution</div>
            <div class="card-body">
                <canvas id="stockByDepot" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Sales</div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Depot</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_no ?? 'N/A' }}</td>
                            <td>{{ optional($sale->depot)->id ?? $sale->depot_id ?? 'N/A' }}</td>
                            <td>{{ optional($sale->customer)->name ?? 'N/A' }}</td>
                            <td>₹{{ number_format($sale->total ?? 0, 2) }}</td>
                            <td>{{ $sale->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No sales found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Top Products (by Qty)</div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Total Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $item)
                        <tr>
                            <td>{{ $item->product_name ?? 'N/A' }}</td>
                            <td>{{ number_format($item->total_qty ?? 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">No data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Low Stock Items (≤ {{ $lowStockThreshold ?? 10 }})
                @if($lowStocks->count() > 0)
                    <span class="badge badge-warning">{{ $lowStocks->count() }} items</span>
                @endif
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Depot</th>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Unit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStocks as $stock)
                        <tr>
                            <td>
                                @if($stock->depot)
                                    Depot {{ $stock->depot->id }}
                                @else
                                    {{ $stock->depot_id }}
                                @endif
                            </td>
                            <td>{{ $stock->product_name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $stock->current_stock <= 5 ? 'danger' : 'warning' }}">
                                    {{ $stock->current_stock ?? 0 }}
                                </span>
                            </td>
                            <td>{{ $stock->measurement_unit ?? 'N/A' }}</td>
                            <td>
                                @if($stock->current_stock <= 5)
                                    <span class="badge badge-danger">Critical</span>
                                @else
                                    <span class="badge badge-warning">Low</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-success">
                                <i class="mdi mdi-check-circle"></i> 
                                No low stock items
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasRole('Super Admin') && isset($stats['critical_alerts']))
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Critical Alerts</div>
            <div class="card-body">
                @forelse($stats['critical_alerts'] as $alert)
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                    <strong>{{ $alert['title'] }}:</strong> {{ $alert['message'] }}
                    <small class="text-muted float-right">{{ $alert['timestamp'] }}</small>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @empty
                <div class="alert alert-success">
                    <i class="mdi mdi-check-circle"></i> No critical alerts at this time.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('script')
    <script src="{{ static_asset('admin/plugins/chartjs/Chart.js?v=' . config('app.version')) }}"></script>
    <script>
        (function(){
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                return;
            }

            // Sales Trend Chart
            const trendCanvas = document.getElementById('salesTrend');
            if (trendCanvas) {
                const trendCtx = trendCanvas.getContext('2d');
                const trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labels ?? []) !!},
                        datasets: [{
                            label: 'Revenue (₹)',
                            data: {!! json_encode($series ?? []) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            tension: 0.25,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { 
                            legend: { display: false } 
                        },
                        scales: { 
                            y: { 
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return '₹' + value.toLocaleString();
                                    }
                                }
                            } 
                        },
                        elements: {
                            point: {
                                radius: 4,
                                hoverRadius: 6
                            }
                        }
                    }
                });
            }

            // Stock Distribution Chart
            const depotCanvas = document.getElementById('stockByDepot');
            if (depotCanvas) {
                const depotCtx = depotCanvas.getContext('2d');
                const stockLabels = {!! json_encode($stockByDepotLabels ?? []) !!};
                const stockValues = {!! json_encode($stockByDepotValues ?? []) !!};
                
                if (stockLabels.length > 0 && stockValues.length > 0) {
                    const depotChart = new Chart(depotCtx, {
                        type: 'doughnut',
                        data: {
                            labels: stockLabels,
                            datasets: [{
                                label: 'Stock',
                                data: stockValues,
                                backgroundColor: [
                                    '#007bff', '#28a745', '#ffc107', '#dc3545', 
                                    '#17a2b8', '#6f42c1', '#20c997', '#fd7e14'
                                ],
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: { 
                            responsive: true,
                            plugins: { 
                                legend: { 
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed.toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    // Show a message when no data is available
                    depotCtx.font = '16px Arial';
                    depotCtx.fillStyle = '#6c757d';
                    depotCtx.textAlign = 'center';
                    depotCtx.fillText('No data available', depotCanvas.width / 2, depotCanvas.height / 2);
                }
            }

            // Auto-refresh functionality (optional)
            setInterval(function() {
                // You can implement auto-refresh here if needed
                console.log('Dashboard refresh check...');
            }, 300000); // 5 minutes
        })();
    </script>
@endpush