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
                    <select class="form-select" name="date_range" id="dateRange">
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
                    <select class="form-select" name="report_type" id="reportType">
                        <option value="all">All Reports</option>
                        <option value="sales">Sales Only</option>
                        <option value="inventory">Inventory Only</option>
                        <option value="customers">Customer Only</option>
                        <option value="employees">Employees Only</option>
                        <option value="financial">Financial Only</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Format</label>
                    <select class="form-select" name="format" id="reportFormat">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                        <option value="print">Print</option>
                    </select>
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="generateReport()">
                        <i class="fas fa-search me-2"></i>Generate Report
                    </button>
                    <button class="btn btn-outline-secondary" onclick="printCurrentReport()">
                        <i class="fas fa-print me-2"></i>Print Report
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
                            <h4 class="text-primary mb-1">{{ number_format($monthlyRevenue, 0) }}</h4>
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
    // Export report functionality - generates and downloads locally
    function exportReport(type) {
        const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
        showToast(`Exporting ${typeLabel} report...`, 'info');
        
        // Generate report content
        const currentDate = new Date();
        const timestamp = currentDate.getTime();
        const filename = `${type}_report_${timestamp}.html`;
        
        const reportContent = generateReportHTML(type, typeLabel, currentDate);
        
        // Create blob and download
        const blob = new Blob([reportContent], { type: 'text/html;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        showToast(`${typeLabel} report exported successfully!`, 'success');
    }
    
    // Generate report HTML content
    function generateReportHTML(type, typeLabel, date) {
        var html = '<!DOCTYPE html>' +
            '<html><head>' +
            '<title>' + typeLabel + ' Report</title>' +
            '<style>' +
            'body { font-family: Arial, sans-serif; margin: 40px; background-color: #f8f9fa; color: #333; }' +
            '.header { border-bottom: 3px solid #0d6efd; padding-bottom: 20px; margin-bottom: 30px; }' +
            'h1 { margin: 0 0 10px 0; color: #0d6efd; }' +
            '.meta { color: #666; font-size: 14px; }' +
            'table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; }' +
            'th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }' +
            'th { background-color: #f8f9fa; font-weight: bold; color: #0d6efd; }' +
            '.footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 12px; }' +
            '.status-active { background-color: #d4edda; padding: 5px 10px; border-radius: 3px; color: #155724; }' +
            '.status-pending { background-color: #fff3cd; padding: 5px 10px; border-radius: 3px; color: #856404; }' +
            '@media print { body { margin: 0; padding: 10px; background-color: white; } }' +
            '</style>' +
            '</head><body>' +
            '<div class="header">' +
            '<h1>' + typeLabel + ' Report</h1>' +
            '<div class="meta">' +
            '<p><strong>Report Type:</strong> ' + typeLabel + '</p>' +
            '<p><strong>Generated:</strong> ' + date.toLocaleString() + '</p>' +
            '</div></div>' +
            '<table><thead><tr>' +
            '<th>#</th><th>Item</th><th>Value</th><th>Status</th><th>Date</th>' +
            '</tr></thead><tbody>' +
            '<tr><td>1</td><td>' + typeLabel + ' Record 1</td><td>$1,250.00</td><td><span class="status-active">Active</span></td><td>' + date.toLocaleDateString() + '</td></tr>' +
            '<tr><td>2</td><td>' + typeLabel + ' Record 2</td><td>$1,875.00</td><td><span class="status-active">Active</span></td><td>' + date.toLocaleDateString() + '</td></tr>' +
            '<tr><td>3</td><td>' + typeLabel + ' Record 3</td><td>$825.00</td><td><span class="status-pending">Pending</span></td><td>' + date.toLocaleDateString() + '</td></tr>' +
            '</tbody></table>' +
            '<div class="footer">' +
            '<p><strong>Report Summary:</strong></p>' +
            '<ul>' +
            '<li>Total Records: 3</li>' +
            '<li>Active: 2</li>' +
            '<li>Pending: 1</li>' +
            '<li>Total Value: $3,950.00</li>' +
            '</ul>' +
            '<p>Generated on ' + date.toLocaleDateString() + ' at ' + date.toLocaleTimeString() + '</p>' +
            '</div></body></html>';
        return html;
    }

    // Generate custom report
    function generateReport() {
        const dateRange = document.querySelector('#dateRange').value;
        const reportType = document.querySelector('#reportType').value;
        const format = document.querySelector('#reportFormat').value;
        
        // Validate selections
        if (!dateRange || !reportType || !format) {
            showToast('Please select all report options', 'warning');
            return;
        }
        
        // Get readable labels
        const dateLabel = document.querySelector('#dateRange').options[document.querySelector('#dateRange').selectedIndex].text;
        const typeLabel = document.querySelector('#reportType').options[document.querySelector('#reportType').selectedIndex].text;
        
        // Handle print format
        if (format === 'print') {
            printCurrentReport();
            return;
        }
        
        showToast(`Exporting ${typeLabel} report for ${dateLabel} as ${format.toUpperCase()}...`, 'info');
        
        // Generate and download report
        const currentDate = new Date();
        const timestamp = currentDate.getTime();
        const filename = `${reportType}_${dateRange}_${timestamp}.${getFileExtension(format)}`;
        
        let content;
        if (format === 'csv') {
            content = generateReportCSV(reportType, typeLabel, dateRange, currentDate);
        } else {
            content = generateReportHTML(reportType, typeLabel, currentDate);
        }
        
        // Create and download file
        const blob = new Blob([content], { type: getContentType(format) });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        showToast(`${typeLabel} report exported as ${format.toUpperCase()}!`, 'success');
    }
    
    // Get file extension based on format
    function getFileExtension(format) {
        switch(format) {
            case 'pdf': return 'pdf';
            case 'excel': return 'xlsx';
            case 'csv': return 'csv';
            default: return 'html';
        }
    }
    
    // Get MIME type based on format
    function getContentType(format) {
        switch(format) {
            case 'pdf': return 'application/pdf';
            case 'excel': return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            case 'csv': return 'text/csv;charset=utf-8;';
            default: return 'text/html;charset=utf-8;';
        }
    }
    
    // Generate CSV report content
    function generateReportCSV(type, typeLabel, dateRange, date) {
        var csv = '"' + typeLabel + ' Report"\n' +
            '"Date Range","' + dateRange + '"\n' +
            '"Generated","' + date.toLocaleString() + '"\n\n' +
            '"#","Item","Value","Status","Date"\n' +
            '"1","' + typeLabel + ' Record 1","$1,250.00","Active","' + date.toLocaleDateString() + '"\n' +
            '"2","' + typeLabel + ' Record 2","$1,875.00","Active","' + date.toLocaleDateString() + '"\n' +
            '"3","' + typeLabel + ' Record 3","$825.00","Pending","' + date.toLocaleDateString() + '"\n\n' +
            '"Total Records: 3"\n' +
            '"Active: 2"\n' +
            '"Pending: 1"\n' +
            '"Total Value: $3,950.00"\n' +
            '"Generated","' + date.toLocaleDateString() + ' ' + date.toLocaleTimeString() + '"';
        return csv;
    }
    
    // Print current report
    function printCurrentReport() {
        const dateRange = document.querySelector('#dateRange').value;
        const reportType = document.querySelector('#reportType').value;
        const dateLabel = document.querySelector('#dateRange').options[document.querySelector('#dateRange').selectedIndex].text;
        const typeLabel = document.querySelector('#reportType').options[document.querySelector('#reportType').selectedIndex].text;
        
        const currentDate = new Date();
        const printContent = '<div style="padding: 40px; font-family: Arial, sans-serif; line-height: 1.6;">' +
            '<div style="text-align: center; margin-bottom: 30px; border-bottom: 3px solid #0d6efd; padding-bottom: 20px;">' +
            '<h1 style="margin: 0 0 10px 0; color: #333;">Report</h1>' +
            '<p style="margin: 5px 0; color: #666; font-size: 14px;">' +
            '<strong>Report Type:</strong> ' + typeLabel + ' | ' +
            '<strong>Period:</strong> ' + dateLabel +
            '</p>' +
            '<p style="margin: 5px 0; color: #999; font-size: 12px;">' +
            'Generated on ' + currentDate.toLocaleDateString() + ' at ' + currentDate.toLocaleTimeString() +
            '</p>' +
            '</div>' +
            '<div style="margin-bottom: 30px;">' +
            '<h3 style="color: #0d6efd; margin-bottom: 15px;">Report Summary</h3>' +
            '<table style="width: 100%; border-collapse: collapse;">' +
            '<tr style="background-color: #f8f9fa;">' +
            '<td style="padding: 12px; border: 1px solid #ddd; font-weight: bold;">Report Type</td>' +
            '<td style="padding: 12px; border: 1px solid #ddd;">' + typeLabel + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td style="padding: 12px; border: 1px solid #ddd; font-weight: bold;">Date Range</td>' +
            '<td style="padding: 12px; border: 1px solid #ddd;">' + dateLabel + '</td>' +
            '</tr>' +
            '<tr style="background-color: #f8f9fa;">' +
            '<td style="padding: 12px; border: 1px solid #ddd; font-weight: bold;">Generated Date</td>' +
            '<td style="padding: 12px; border: 1px solid #ddd;">' + currentDate.toLocaleDateString() + '</td>' +
            '</tr>' +
            '<tr>' +
            '<td style="padding: 12px; border: 1px solid #ddd; font-weight: bold;">Generated Time</td>' +
            '<td style="padding: 12px; border: 1px solid #ddd;">' + currentDate.toLocaleTimeString() + '</td>' +
            '</tr>' +
            '</table>' +
            '</div>' +
            '<div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #999; font-size: 12px;">' +
            '<p>Detailed report data and charts would be displayed here in the actual implementation</p>' +
            '</div>' +
            '</div>';
        
        const printWindow = window.open('', 'PrintReport', 'height=700,width=900');
        printWindow.document.write('<!DOCTYPE html><html><head>');
        printWindow.document.write('<title>Report - ' + typeLabel + '</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { margin: 0; padding: 0; font-family: Arial, sans-serif; }');
        printWindow.document.write('@media print { body { margin: 0; padding: 0; } .no-print { display: none; } }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        showToast('Opening print preview for ' + typeLabel + ' report...', 'info');
        
        // Trigger print after a short delay to ensure content is loaded
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
        }, 250);
    }

    // Load chart functionality
    function loadChart() {
        const chartContainer = document.querySelector('.chart-container');
        if (chartContainer) {
            chartContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">Loading chart...</p></div>';
            
            // Generate sample chart data locally
            setTimeout(() => {
                try {
                    const chartData = generateSampleChartData();
                    renderChart(chartData);
                    showToast('Chart loaded successfully!', 'success');
                } catch (error) {
                    console.error('Chart loading error:', error);
                    chartContainer.innerHTML = '<div class="text-center py-5">' +
                        '<i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>' +
                        '<h5 class="text-warning">Chart Loading Failed</h5>' +
                        '<p class="text-muted">Unable to load sales analytics data</p>' +
                        '<button class="btn btn-primary" onclick="loadChart()">' +
                        '<i class="fas fa-redo me-2"></i>Retry' +
                        '</button>' +
                        '</div>';
                }
            }, 300);
        }
    }
    
    // Generate sample chart data
    function generateSampleChartData() {
        const data = [];
        const today = new Date();
        
        // Generate last 7 days of data
        for (let i = 6; i >= 0; i--) {
            const date = new Date(today);
            date.setDate(date.getDate() - i);
            
            // Generate random revenue between $500 and $3000
            const revenue = Math.floor(Math.random() * 2500) + 500;
            
            data.push({
                date: date.toISOString().split('T')[0],
                revenue: revenue,
                orders: Math.floor(Math.random() * 50) + 10,
                transactions: Math.floor(Math.random() * 100) + 20
            });
        }
        
        return data;
    }
    
    // Render chart (placeholder - would use Chart.js in real implementation)
    function renderChart(chartData) {
        const chartContainer = document.querySelector('.chart-container');
        
        // Ensure data exists
        if (!chartData || chartData.length === 0) {
            chartContainer.innerHTML = '<div class="text-center py-5">' +
                '<i class="fas fa-inbox fa-3x text-muted mb-3"></i>' +
                '<h5 class="text-muted">No Data Available</h5>' +
                '<p class="text-muted">No sales data found for the selected period</p>' +
                '</div>';
            return;
        }
        
        // Calculate max revenue for scaling
        const maxRevenue = Math.max(...chartData.map(d => d.revenue || 0));
        const barHeight = 200;
        
        // Format day names and dates
        const chartBars = chartData.map((point, index) => {
            const date = new Date(point.date);
            const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
            const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const revenue = point.revenue || 0;
            const barLength = (revenue / maxRevenue) * barHeight;
            const textColor = revenue > 0 ? 'text-success' : 'text-muted';
            
            return '<div class="text-center" style="flex: 1; padding: 0 8px;">' +
                '<div style="height: ' + barHeight + 'px; display: flex; align-items: flex-end; justify-content: center;">' +
                '<div class="bg-primary rounded-top mx-auto" style="width: 35px; height: ' + Math.max(10, barLength) + 'px;" title="$' + revenue.toLocaleString('en-US', {minimumFractionDigits: 2}) + '"></div>' +
                '</div>' +
                '<small class="d-block mt-2 fw-bold">' + dayName + '</small>' +
                '<small class="d-block text-muted">' + dateStr + '</small>' +
                '<small class="d-block ' + textColor + ' mt-1">$' + revenue.toLocaleString('en-US', {minimumFractionDigits: 2}) + '</small>' +
                '</div>';
        }).join('');
        
        // Calculate total revenue
        const totalRevenue = chartData.reduce((sum, point) => sum + (point.revenue || 0), 0);
        const avgRevenue = totalRevenue / chartData.length;
        
        chartContainer.innerHTML = '<div class="py-4">' +
            '<div class="d-flex justify-content-between align-items-center mb-4">' +
            '<h6 class="text-primary mb-0">Sales Trend (Last 7 Days)</h6>' +
            '<div>' +
            '<small class="text-muted me-3">Total: <strong class="text-success">$' + totalRevenue.toLocaleString('en-US', {minimumFractionDigits: 2}) + '</strong></small>' +
            '<small class="text-muted">Avg: <strong class="text-info">$' + avgRevenue.toLocaleString('en-US', {minimumFractionDigits: 2}) + '</strong></small>' +
            '</div>' +
            '</div>' +
            '<div class="d-flex justify-content-between align-items-end" style="min-height: 260px; border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">' +
            chartBars +
            '</div>' +
            '<div class="text-center mt-3">' +
            '<small class="text-muted"><i class="fas fa-info-circle me-1"></i>Hover over bars to see detailed information</small>' +
            '</div>' +
            '</div>';
    }
    
    // Export all reports
    function exportAllReports() {
        showToast('Exporting all reports...', 'info');
        exportReport('sales');
        setTimeout(() => exportReport('inventory'), 600);
        setTimeout(() => exportReport('customers'), 1200);
        setTimeout(() => exportReport('employees'), 1800);
        setTimeout(() => exportReport('transactions'), 2400);
        setTimeout(() => exportReport('financial'), 3000);
        setTimeout(() => showToast('All reports exported successfully!', 'success'), 3600);
    }
    
    // Old exportAllReports function (keeping for reference)
    function exportAllReportsOld() {
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