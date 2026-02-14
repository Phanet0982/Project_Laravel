@extends('layouts.app')

@section('title', 'Transaction Report')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-exchange-alt me-3"></i>Transaction Report</h1>
            <p class="text-muted mb-0">Detailed transaction logs and payment analysis</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReport('transactions')">
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
                        <i class="fas fa-receipt text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $transactions->total() }}</h4>
                        <p class="text-muted mb-0">Total Transactions</p>
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
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">${{ number_format($transactions->sum('total_amount'), 2) }}</h4>
                        <p class="text-muted mb-0">Total Value</p>
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
                        <h4 class="mb-1">${{ $transactions->count() > 0 ? number_format($transactions->sum('total_amount') / $transactions->count(), 2) : '0.00' }}</h4>
                        <p class="text-muted mb-0">Avg Transaction</p>
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
                <i class="fas fa-list me-2"></i>Transaction History
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
                        <th><i class="fas fa-hashtag me-2"></i>Transaction ID</th>
                        <th><i class="fas fa-user me-2"></i>Customer</th>
                        <th><i class="fas fa-shopping-bag me-2"></i>Items</th>
                        <th><i class="fas fa-dollar-sign me-2"></i>Amount</th>
                        <th><i class="fas fa-credit-card me-2"></i>Payment</th>
                        <th><i class="fas fa-clock me-2"></i>Date & Time</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($transactions->count() > 0)
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>
                                    <code class="bg-dark text-light px-2 py-1 rounded">#TXN{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $transaction->customer->name ?? 'Walk-in Customer' }}</div>
                                            @if($transaction->customer)
                                                <small class="text-muted">{{ $transaction->customer->member_type ?? 'Regular' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge bg-primary">{{ $transaction->saleItems->count() }} items</span>
                                        <div class="mt-1">
                                            @foreach($transaction->saleItems->take(2) as $item)
                                                <small class="text-muted d-block">{{ $item->product->name ?? 'Product' }} ({{ $item->quantity }}x)</small>
                                            @endforeach
                                            @if($transaction->saleItems->count() > 2)
                                                <small class="text-muted">+{{ $transaction->saleItems->count() - 2 }} more...</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-5">${{ number_format($transaction->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-credit-card me-1"></i>Cash
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $transaction->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('h:i:s A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" title="View Details" onclick="viewTransaction({{ $transaction->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Print Receipt" onclick="printReceipt({{ $transaction->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" title="Refund" onclick="processRefund({{ $transaction->id }})">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                                    <h5>No Transactions Found</h5>
                                    <p>No transactions found for the selected period</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function changePeriod(period) {
        window.location.href = `{{ route('reports.transactions') }}?period=${period}`;
    }

    function viewTransaction(transactionId) {
        showToast('Opening transaction details...', 'info');
        // Here you would implement transaction details view
    }

    function printReceipt(transactionId) {
        showToast('Preparing receipt for printing...', 'info');
        
        // Open the transaction receipt in a new window for printing
        const receiptUrl = `/pos/receipt/${transactionId}`;
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

    function processRefund(transactionId) {
        showConfirm(
            'Are you sure you want to process a refund for this transaction?',
            function() {
                showToast('Processing refund...', 'warning');
                // Here you would implement refund processing
            },
            'Process Refund'
        );
    }

    function exportReport(type) {
        // Export whole report page as PDF
        exportPagePDF('Transaction Report', { filename: 'transaction_report' });
    }
</script>
@endpush
@endsection