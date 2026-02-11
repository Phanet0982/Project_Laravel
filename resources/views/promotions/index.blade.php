@extends('layouts.app')

@section('title', 'Promotions Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-tags me-3"></i>Promotions Management</h1>
            <p class="text-muted mb-0">Create and manage promotional campaigns, discounts, and special offers</p>
        </div>
        <a href="{{ route('promotions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Promotion
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1">{{ $totalPromotions }}</h3>
                <p class="text-muted mb-0">Total Promotions</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <h3 class="text-success mb-1">{{ $activePromotions }}</h3>
                <p class="text-muted mb-0">Active Promotions</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <h3 class="text-warning mb-1">{{ $upcomingPromotions }}</h3>
                <p class="text-muted mb-0">Upcoming Promotions</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h3 class="text-danger mb-1">{{ $expiredPromotions }}</h3>
                <p class="text-muted mb-0">Expired Promotions</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>All Promotions
            </h5>
            <div class="btn-group" role="group">
                <a href="{{ route('promotions.index', ['filter' => 'all']) }}" class="btn btn-outline-primary btn-sm {{ request('filter', 'all') == 'all' ? 'active' : '' }}">All</a>
                <a href="{{ route('promotions.index', ['filter' => 'active']) }}" class="btn btn-outline-success btn-sm {{ request('filter', 'all') == 'active' ? 'active' : '' }}">Active</a>
                <a href="{{ route('promotions.index', ['filter' => 'expired']) }}" class="btn btn-outline-danger btn-sm {{ request('filter', 'all') == 'expired' ? 'active' : '' }}">Expired</a>
                <a href="{{ route('promotions.index', ['filter' => 'upcoming']) }}" class="btn btn-outline-warning btn-sm {{ request('filter', 'all') == 'upcoming' ? 'active' : '' }}">Upcoming</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($promotions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Code</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Usage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promotions as $promotion)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $promotion->name }}</div>
                                        <small class="text-muted">{{ Str::limit($promotion->description, 50) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $promotion->type)) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $promotion->formatted_value }}</span>
                                    @if($promotion->minimum_amount)
                                        <br><small class="text-muted">Min: ${{ number_format($promotion->minimum_amount, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($promotion->code)
                                        <code>{{ $promotion->code }}</code>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div>{{ $promotion->start_date->format('M d, Y') }}</div>
                                        <small class="text-muted">to {{ $promotion->end_date->format('M d, Y') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($promotion->is_active && $promotion->start_date <= now() && $promotion->end_date >= now())
                                        <span class="badge bg-success">Active</span>
                                    @elseif($promotion->end_date < now())
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($promotion->start_date > now())
                                        <span class="badge bg-warning">Upcoming</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($promotion->usage_limit)
                                        <div>{{ $promotion->used_count }} / {{ $promotion->usage_limit }}</div>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ ($promotion->used_count / $promotion->usage_limit) * 100 }}%"></div>
                                        </div>
                                    @else
                                        <span class="text-muted">Unlimited</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('promotions.show', $promotion->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('promotions.toggleStatus', $promotion->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $promotion->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $promotion->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas {{ $promotion->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this promotion?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $promotions->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5>No Promotions Found</h5>
                <p class="text-muted">Start by creating your first promotional campaign</p>
                <a href="{{ route('promotions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Promotion
                </a>
            </div>
        @endif
    </div>
</div>
@endsection