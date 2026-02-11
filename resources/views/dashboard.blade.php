@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h1>Dashboard</h1>
            <p class="mb-0">Welcome back! Here's your business overview.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Sale
            </button>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1">Today's Sales</p>
                    <h3>${{ number_format($todaySales, 2) }}</h3>
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>+12% from yesterday
                    </small>
                </div>
                <div class="text-primary">
                    <i class="fas fa-dollar-sign fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1">Total Products</p>
                    <h3>{{ $totalProducts }}</h3>
                    <small>Active inventory</small>
                </div>
                <div class="text-success">
                    <i class="fas fa-box fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1">Low Stock Items</p>
                    <h3>{{ $lowStock }}</h3>
                    <small class="text-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>Needs attention
                    </small>
                </div>
                <div class="text-warning">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1">Total Customers</p>
                    <h3>{{ $totalCustomers }}</h3>
                    <small>Growing base</small>
                </div>
                <div class="text-info">
                    <i class="fas fa-users fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Sales</h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sale ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td><span class="fw-medium">#{{ str_pad($sale->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                    <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                                    <td><span class="fw-medium">${{ number_format($sale->total_amount, 2) }}</span></td>
                                    <td>
                                        <span class="badge bg-success">
                                            Cash
                                        </span>
                                    </td>
                                    <td>{{ $sale->created_at->format('M d, H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No recent sales
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('pos.index') }}" class="btn btn-primary">
                        <i class="fas fa-cash-register me-2"></i>Open POS
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-box me-2"></i>Manage Products
                    </a>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>Manage Customers
                    </a>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar me-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Sales Overview</h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="mb-0">24</h4>
                            <small class="text-muted">Today</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-0">156</h4>
                        <small class="text-muted">This Week</small>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Cash</small>
                        <small class="fw-medium">65%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 65%"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Card</small>
                        <small class="fw-medium">25%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 25%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <small>QR</small>
                        <small class="fw-medium">10%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 10%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .progress {
        background-color: var(--bg-tertiary);
        border-radius: 3px;
    }
    
    /* Responsive Dashboard */
    @media (max-width: 768px) {
        .stat-card h3 {
            font-size: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .stat-card {
            padding: 15px;
        }
        
        .stat-card h3 {
            font-size: 1.25rem;
        }
        
        .page-header h1 {
            font-size: 1.25rem;
        }
        
        .row.g-3 > [class*="col"] {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endpush
@endsection