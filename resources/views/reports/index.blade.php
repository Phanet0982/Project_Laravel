@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-chart-line me-3"></i>Reports & Analytics</h1>
            <p class="text-muted mb-0">Comprehensive business insights and performance metrics</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="exportAllReports()">
                <i class="fas fa-download me-2"></i>Export All
            </button>
            <button class="btn btn-success" onclick="scheduleReport()">
                <i class="fas fa-calendar me-2"></i>Schedule Report
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Sales Report</h5>
                        <small class="text-muted">Daily, weekly, and monthly sales</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Comprehensive sales analysis with trends and comparisons.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" onclick="window.location.href='{{ route('reports.sales') }}'">
                        <i class="fas fa-eye me-1"></i>View
                    </button>
                    <button class="btn btn-outline-primary btn-sm" onclick="exportReport('sales')">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Inventory Report</h5>
                        <small class="text-muted">Stock levels and movements</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Monitor inventory levels, low stock alerts, and stock movements.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm flex-fill" onclick="window.location.href='{{ route('reports.inventory') }}'">
                        <i class="fas fa-eye me-1"></i>View
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="exportReport('inventory')">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Customer Report</h5>
                        <small class="text-muted">Customer analytics and behavior</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Customer purchase patterns, loyalty metrics, and demographics.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-info btn-sm flex-fill" onclick="window.location.href='{{ route('reports.customers') }}'">
                        <i class="fas fa-eye me-1"></i>View
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="exportReport('customers')">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-exchange-alt text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Transaction Report</h5>
                        <small class="text-muted">All transaction details</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Detailed transaction logs with payment methods and timestamps.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-warning btn-sm flex-fill" onclick="window.location.href='{{ route('reports.transactions') }}'">
                        <i class="fas fa-eye me-1"></i>View
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="exportReport('transactions')">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Employee Report</h5>
                        <small class="text-muted">Staff performance and attendance</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Employee sales performance, attendance records, and productivity.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-danger btn-sm flex-fill" onclick="window.location.href='{{ route('reports.employees') }}'">
                        <i class="fas fa-eye me-1"></i>View
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="exportReport('employees')">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-chart-pie text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Financial Report</h5>
                        <small class="text-muted">Profit, loss, and financial overview</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Complete financial analysis including profit margins and expenses.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary btn-sm flex-fill" onclick="window.location.href='{{ route('reports.financial') }}'">
                        <i class="fas fa-eye me-1"></i>View
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportReport('financial')">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area me-2"></i>Sales Analytics
                    </h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option>Last 3 months</option>
                            <option>Last year</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Sales Chart</h5>
                        <p class="text-muted">Interactive sales analytics chart would be displayed here</p>
                        <button class="btn btn-primary" onclick="loadChart()">
                            <i class="fas fa-chart-bar me-2"></i>Load Chart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Report Filters
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" name="date_range">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="7days" selected>Last 7 days</option>
                        <option value="30days">Last 30 days</option>
                        <option value="thismonth">This month</option>
                        <option value="lastmonth">Last month</option>
                        <option value="custom">Custom range</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Report Type</label>
                    <select class="form-select" name="report_type">
                        <option value="all">All Reports</option>
                        <option value="sales">Sales Only</option>
                        <option value="inventory">Inventory Only</option>
                        <option value="customers">Customer Only</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Format</label>
                    <select class="form-select" name="format">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
                
                <div class="d-grid">
                    <button class="btn btn-primary" onclick="generateReport()">
                        <i class="fas fa-search me-2"></i>Generate Report
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Quick Stats
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">${{ number_format($monthlyRevenue, 0) }}</h4>
                            <small class="text-muted">This Month</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success mb-1">{{ $totalOrders }}</h4>
                        <small class="text-muted">Total Orders</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-warning mb-1">{{ $totalCustomers }}</h4>
                            <small class="text-muted">Customers</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-1">{{ $totalProducts }}</h4>
                        <small class="text-muted">Products</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Export report functionality
    function exportReport(type) {
        showToast(`Preparing ${type} report export...`, 'info');
        
        // Simulate export process
        fetch(`/reports/${type}/export`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                format: 'pdf',
                date_range: 'last_7_days'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`${type.charAt(0).toUpperCase() + type.slice(1)} report exported successfully!`, 'success');
                // In a real implementation, you would trigger file download
            } else {
                showToast(`Export failed: ${data.message}`, 'error');
            }
        })
        .catch(error => {
            showToast('Export failed. Please try again.', 'error');
            console.error('Export error:', error);
        });
    }

    // Generate custom report
    function generateReport() {
        const dateRange = document.querySelector('select[name="date_range"]').value;
        const reportType = document.querySelector('select[name="report_type"]').value;
        const format = document.querySelector('select[name="format"]').value;
        
        showToast(`Generating ${reportType} report for ${dateRange} in ${format} format...`, 'info');
        
        // Redirect to appropriate report page with parameters
        let url = '/reports';
        switch(reportType) {
            case 'sales':
                url = '/reports/sales';
                break;
            case 'inventory':
                url = '/reports/inventory';
                break;
            case 'customers':
                url = '/reports/customers';
                break;
            default:
                url = '/reports';
        }
        
        // Add parameters
        const params = new URLSearchParams();
        params.set('period', dateRange);
        params.set('format', format);
        
        window.location.href = `${url}?${params.toString()}`;
    }

    // Load chart functionality
    function loadChart() {
        const chartContainer = document.querySelector('.chart-container');
        if (chartContainer) {
            chartContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">Loading chart...</p></div>';
            
            // Simulate API call to get chart data
            fetch('/reports/chart-data')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderChart(data.chartData);
                        showToast('Chart loaded successfully!', 'success');
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Chart loading error:', error);
                    chartContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5 class="text-warning">Chart Loading Failed</h5>
                            <p class="text-muted">Unable to load sales analytics data</p>
                            <button class="btn btn-primary" onclick="loadChart()">
                                <i class="fas fa-redo me-2"></i>Retry
                            </button>
                        </div>
                    `;
                });
        }
    }
    
    // Render chart (placeholder - would use Chart.js in real implementation)
    function renderChart(chartData) {
        const chartContainer = document.querySelector('.chart-container');
        chartContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="bg-light rounded p-4 mb-3">
                    <h6 class="text-primary mb-3">Sales Trend (Last 7 Days)</h6>
                    <div class="d-flex justify-content-between align-items-end" style="height: 150px;">
                        ${chartData.map((point, index) => `
                            <div class="text-center" style="flex: 1;">
                                <div class="bg-primary rounded-top mx-auto" style="width: 30px; height: ${Math.max(20, point.revenue / 100)}px;"></div>
                                <small class="d-block mt-2">${point.date}</small>
                                <small class="text-muted">$${Math.round(point.revenue)}</small>
                            </div>
                        `).join('')}
                    </div>
                </div>
                <p class="text-muted">Interactive chart with Chart.js would be displayed here</p>
            </div>
        `;
    }
    
    // Export all reports
    function exportAllReports() {
        showToast('Preparing all reports for export...', 'info');
        
        // Export all report types
        const reportTypes = ['sales', 'inventory', 'customers', 'employees', 'transactions', 'financial'];
        let completed = 0;
        
        reportTypes.forEach(type => {
            fetch(`/reports/${type}/export`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    format: 'pdf',
                    date_range: 'last_7_days'
                })
            })
            .then(response => response.json())
            .then(data => {
                completed++;
                if (completed === reportTypes.length) {
                    showToast('All reports exported successfully!', 'success');
                }
            })
            .catch(error => {
                console.error(`Export failed for ${type}:`, error);
            });
        });
    }
    
    // Schedule report
    function scheduleReport() {
        showToast('Report scheduling feature would be implemented here', 'info');
        // In a real implementation, this would open a modal for scheduling options
    }
    
    // Initialize date range selector
    document.addEventListener('DOMContentLoaded', function() {
        const dateRangeSelect = document.querySelector('select[name="date_range"]');
        if (dateRangeSelect) {
            dateRangeSelect.addEventListener('change', function() {
                showToast(`Date range changed to: ${this.options[this.selectedIndex].text}`, 'info');
            });
        }
    });
</script>
@endpush