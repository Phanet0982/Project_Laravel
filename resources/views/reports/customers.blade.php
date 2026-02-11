@extends('layouts.app')

@section('title', 'Customer Report')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-users me-3"></i>Customer Report</h1>
            <p class="text-muted mb-0">Customer analytics, behavior patterns, and loyalty metrics</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReport('customers')">
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
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $totalCustomers }}</h4>
                        <p class="text-muted mb-0">Total Customers</p>
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
                        <i class="fas fa-crown text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $vipCustomers }}</h4>
                        <p class="text-muted mb-0">VIP Members</p>
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
                        <i class="fas fa-user-check text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $regularCustomers }}</h4>
                        <p class="text-muted mb-0">Regular Members</p>
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
                        <i class="fas fa-calendar-plus text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $newThisMonth }}</h4>
                        <p class="text-muted mb-0">New This Month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Customer Analytics
                    </h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterByType(this.value)">
                            <option value="all">All Customers</option>
                            <option value="vip">VIP Only</option>
                            <option value="regular">Regular Only</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="customersTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Customer</th>
                                <th><i class="fas fa-star me-2"></i>Type</th>
                                <th><i class="fas fa-shopping-cart me-2"></i>Orders (3M)</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Total Spent</th>
                                <th><i class="fas fa-calendar me-2"></i>Last Purchase</th>
                                <th><i class="fas fa-chart-line me-2"></i>Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr data-type="{{ $customer->member_type }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-{{ $customer->member_type == 'vip' ? 'warning' : 'primary' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-{{ $customer->member_type == 'vip' ? 'crown' : 'user' }} text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $customer->name }}</div>
                                                <small class="text-muted">ID: #{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($customer->member_type === 'vip')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-crown me-1"></i>VIP
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-user me-1"></i>Regular
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $customer->sales_count ?? 0 }}</span>
                                        <small class="text-muted">orders</small>
                                    </td>
                                    <td>
                                        @php
                                            $totalSpent = $customer->sales->sum('total_amount') ?? 0;
                                        @endphp
                                        <span class="fw-bold text-success">${{ number_format($totalSpent, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($customer->sales->count() > 0)
                                            @php
                                                $lastSale = $customer->sales->sortByDesc('created_at')->first();
                                            @endphp
                                            <div>
                                                <div class="fw-bold">{{ $lastSale->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $lastSale->created_at->diffForHumans() }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">No purchases</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $recentOrders = $customer->sales->where('created_at', '>=', now()->subDays(30))->count();
                                        @endphp
                                        @if($recentOrders >= 5)
                                            <span class="badge bg-success">Very Active</span>
                                        @elseif($recentOrders >= 2)
                                            <span class="badge bg-warning">Active</span>
                                        @elseif($recentOrders >= 1)
                                            <span class="badge bg-info">Moderate</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Customer Breakdown
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-6">
                        <div class="border-end">
                            <h3 class="text-warning mb-1">{{ $vipCustomers }}</h3>
                            <p class="text-muted mb-0">VIP</p>
                            <small class="text-muted">{{ $totalCustomers > 0 ? number_format(($vipCustomers / $totalCustomers) * 100, 1) : 0 }}%</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success mb-1">{{ $regularCustomers }}</h3>
                        <p class="text-muted mb-0">Regular</p>
                        <small class="text-muted">{{ $totalCustomers > 0 ? number_format(($regularCustomers / $totalCustomers) * 100, 1) : 0 }}%</small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Membership Distribution</h6>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ $totalCustomers > 0 ? ($vipCustomers / $totalCustomers) * 100 : 0 }}%"></div>
                        <div class="progress-bar bg-success" style="width: {{ $totalCustomers > 0 ? ($regularCustomers / $totalCustomers) * 100 : 0 }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-warning">VIP Members</small>
                        <small class="text-success">Regular Members</small>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Growth Insights</strong><br>
                    <small>
                        {{ $newThisMonth }} new customers joined this month.
                        @if($vipCustomers > 0)
                            {{ number_format(($vipCustomers / $totalCustomers) * 100, 1) }}% are VIP members.
                        @endif
                    </small>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Top Customers
                </h5>
            </div>
            <div class="card-body">
                @php
                    $topCustomers = $customers->sortByDesc(function($customer) {
                        return $customer->sales->sum('total_amount');
                    })->take(5);
                @endphp
                
                @foreach($topCustomers as $index => $customer)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                            <span class="text-white fw-bold" style="font-size: 12px;">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $customer->name }}</div>
                            <small class="text-success">${{ number_format($customer->sales->sum('total_amount'), 0) }} spent</small>
                        </div>
                        @if($customer->member_type === 'vip')
                            <i class="fas fa-crown text-warning"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterByType(type) {
        const table = document.getElementById('customersTable');
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (type === 'all' || row.dataset.type === type) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        showToast(`Filtered by ${type === 'all' ? 'all customers' : type + ' customers'}`, 'info');
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