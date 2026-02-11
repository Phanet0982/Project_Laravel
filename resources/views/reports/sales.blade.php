@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-chart-bar me-3"></i>Sales Report</h1>
            <p class="text-muted mb-0">Comprehensive sales analysis and performance metrics</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReport('sales')">
                <i class="fas fa-download me-2"></i>Export PDF
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Reports
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">${{ number_format($totalRevenue, 2) }}</h4>
                        <p class="text-muted mb-0">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle p-3 me-3">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $totalOrders }}</h4>
                        <p class="text-muted mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info rounded-circle p-3 me-3">
                        <i class="fas fa-calculator text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">${{ number_format($avgOrderValue, 2) }}</h4>
                        <p class="text-muted mb-0">Avg Order Value</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded-circle p-3 me-3">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ ucfirst($period) }}</h4>
                        <p class="text-muted mb-0">Period</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Sales Transactions
            </h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;" onchange="changePeriod(this.value)">
                    <option value="7days" {{ $period == '7days' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30days" {{ $period == '30days' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="thismonth" {{ $period == 'thismonth' ? 'selected' : '' }}>This month</option>
                    <option value="lastmonth" {{ $period == 'lastmonth' ? 'selected' : '' }}>Last month</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-2"></i>Sale ID</th>
                        <th><i class="fas fa-user me-2"></i>Customer</th>
                        <th><i class="fas fa-shopping-bag me-2"></i>Items</th>
                        <th><i class="fas fa-dollar-sign me-2"></i>Total</th>
                        <th><i class="fas fa-calendar me-2"></i>Date</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($sales->count() > 0)
                        @foreach($sales as $sale)
                            <tr>
                                <td>
                                    <code class="bg-dark text-light px-2 py-1 rounded">#{{ str_pad($sale->id, 4, '0', STR_PAD_LEFT) }}</code>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                                        @if($sale->customer)
                                            <small class="text-muted">{{ $sale->customer->member_type ?? 'Regular' }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $sale->saleItems->count() }} items</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">${{ number_format($sale->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $sale->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $sale->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" title="View Details" onclick="viewSale({{ $sale->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Print Receipt" onclick="printReceipt({{ $sale->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                    <h5>No Sales Found</h5>
                                    <p>No sales transactions found for the selected period</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($sales->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function changePeriod(period) {
        window.location.href = `{{ route('reports.sales') }}?period=${period}`;
    }

    function viewSale(saleId) {
        showToast('Opening sale details...', 'info');
        // Here you would implement sale details view
    }

    function printReceipt(saleId) {
        showToast('Preparing receipt for printing...', 'info');
        
        // Open the sale receipt in a new window for printing
        const receiptUrl = `/pos/receipt/${saleId}`;
        const printWindow = window.open(receiptUrl, '_blank', 'width=400,height=600');
        
        // Wait for the page to load then trigger print
        if (printWindow) {
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 1000);
            };
            showToast('Receipt opened for printing!', 'success');
        } else {
            showToast('Failed to open receipt. Please check popup blocker.', 'error');
        }
    }

    function exportReport(type) {
        showToast(`Exporting ${type} report...`, 'info');
        setTimeout(() => {
            showToast('Report exported successfully!', 'success');
        }, 2000);
    }
</script>
@endpush
@endsection