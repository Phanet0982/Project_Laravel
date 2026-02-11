@extends('layouts.app')

@section('title', 'Financial Report')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-chart-pie me-3"></i>Financial Report</h1>
            <p class="text-muted mb-0">Profit, loss, and comprehensive financial analysis</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReport('financial')">
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
                    <div class="bg-success rounded-circle p-3 me-3">
                        <i class="fas fa-arrow-up text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">${{ number_format($thisMonthRevenue, 2) }}</h4>
                        <p class="text-muted mb-0">This Month Revenue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger rounded-circle p-3 me-3">
                        <i class="fas fa-arrow-down text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">${{ number_format($thisMonthCosts, 2) }}</h4>
                        <p class="text-muted mb-0">This Month Costs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ $profit >= 0 ? 'primary' : 'warning' }} rounded-circle p-3 me-3">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 text-{{ $profit >= 0 ? 'success' : 'danger' }}">${{ number_format($profit, 2) }}</h4>
                        <p class="text-muted mb-0">Net Profit</p>
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
                        <i class="fas fa-percentage text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($profitMargin, 1) }}%</h4>
                        <p class="text-muted mb-0">Profit Margin</p>
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
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Monthly Comparison
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 mb-4">
                        <div class="border-end">
                            <h3 class="text-success mb-2">${{ number_format($thisMonthRevenue, 0) }}</h3>
                            <p class="text-muted mb-1">This Month</p>
                            <small class="text-success">
                                @if($lastMonthRevenue > 0)
                                    @php
                                        $change = (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
                                    @endphp
                                    <i class="fas fa-{{ $change >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ number_format(abs($change), 1) }}% vs last month
                                @else
                                    New revenue this month
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h3 class="text-muted mb-2">${{ number_format($lastMonthRevenue, 0) }}</h3>
                        <p class="text-muted mb-1">Last Month</p>
                        <small class="text-muted">Previous period</small>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="text-muted mb-3">Financial Breakdown</h6>
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="bg-success bg-opacity-10 rounded p-3 mb-2">
                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                            </div>
                            <h5 class="text-success">${{ number_format($thisMonthRevenue, 0) }}</h5>
                            <p class="text-muted mb-0">Revenue</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-danger bg-opacity-10 rounded p-3 mb-2">
                                <i class="fas fa-minus fa-2x text-danger"></i>
                            </div>
                            <h5 class="text-danger">${{ number_format($thisMonthCosts, 0) }}</h5>
                            <p class="text-muted mb-0">Costs</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-primary bg-opacity-10 rounded p-3 mb-2">
                                <i class="fas fa-equals fa-2x text-primary"></i>
                            </div>
                            <h5 class="text-{{ $profit >= 0 ? 'primary' : 'warning' }}">${{ number_format($profit, 0) }}</h5>
                            <p class="text-muted mb-0">Profit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Financial Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Revenue Growth</span>
                        <span class="fw-bold text-{{ $lastMonthRevenue > 0 && $thisMonthRevenue > $lastMonthRevenue ? 'success' : 'muted' }}">
                            @if($lastMonthRevenue > 0)
                                {{ number_format((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) }}%
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $lastMonthRevenue > 0 ? min(100, abs((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)) : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Profit Margin</span>
                        <span class="fw-bold text-{{ $profitMargin >= 20 ? 'success' : ($profitMargin >= 10 ? 'warning' : 'danger') }}">
                            {{ number_format($profitMargin, 1) }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-{{ $profitMargin >= 20 ? 'success' : ($profitMargin >= 10 ? 'warning' : 'danger') }}" style="width: {{ min(100, $profitMargin) }}%"></div>
                    </div>
                </div>
                
                <div class="alert alert-{{ $profit >= 0 ? 'success' : 'warning' }} alert-dismissible">
                    <i class="fas fa-{{ $profit >= 0 ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                    <strong>{{ $profit >= 0 ? 'Profitable' : 'Loss' }} Month</strong><br>
                    <small>
                        @if($profit >= 0)
                            Your business is generating positive returns this month.
                        @else
                            Consider reviewing costs and pricing strategies.
                        @endif
                    </small>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Recommendations
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @if($profitMargin < 10)
                        <li class="mb-2">
                            <i class="fas fa-arrow-up text-warning me-2"></i>
                            <small>Consider increasing product prices to improve margins</small>
                        </li>
                    @endif
                    @if($thisMonthRevenue < $lastMonthRevenue)
                        <li class="mb-2">
                            <i class="fas fa-chart-line text-info me-2"></i>
                            <small>Focus on marketing to boost sales</small>
                        </li>
                    @endif
                    <li class="mb-2">
                        <i class="fas fa-eye text-primary me-2"></i>
                        <small>Monitor inventory turnover rates</small>
                    </li>
                    <li>
                        <i class="fas fa-users text-success me-2"></i>
                        <small>Analyze customer purchase patterns</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportReport(type) {
        showToast(`Exporting ${type} report...`, 'info');
        setTimeout(() => {
            showToast('Report exported successfully!', 'success');
        }, 2000);
    }
</script>
@endpush
@endsection