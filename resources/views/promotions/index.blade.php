@extends('layouts.app')

@section('title', 'Promotions Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-tags me-3"></i>Promotions Management</h1>
            <p class="text-muted mb-0">Create and manage promotional campaigns, discounts, and special offers</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm" title="View Products">
                <i class="fas fa-box me-2"></i>Products
            </a>
            <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary btn-sm" title="Go to POS">
                <i class="fas fa-cash-register me-2"></i>POS
            </a>
            <a href="{{ route('promotions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Promotion
            </a>
        </div>
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
                <a href="{{ route('promotions.index', ['filter' => 'all']) }}" class="btn btn-outline-primary btn-sm {{ $filter == 'all' ? 'active' : '' }}">All</a>
                <a href="{{ route('promotions.index', ['filter' => 'active']) }}" class="btn btn-outline-success btn-sm {{ $filter == 'active' ? 'active' : '' }}">Active</a>
                <a href="{{ route('promotions.index', ['filter' => 'expired']) }}" class="btn btn-outline-danger btn-sm {{ $filter == 'expired' ? 'active' : '' }}">Expired</a>
                <a href="{{ route('promotions.index', ['filter' => 'upcoming']) }}" class="btn btn-outline-warning btn-sm {{ $filter == 'upcoming' ? 'active' : '' }}">Upcoming</a>
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
                            <th>Discount</th>
                            <th>Period</th>
                            <th>Associated Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promotions as $promotion)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $promotion->name }}</div>
                                        <small class="text-muted">Created: {{ $promotion->created_at->format('M d, Y') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $promotion->discount_percent }}%</span>
                                </td>
                                <td>
                                    <div>
                                        <div class="small">{{ $promotion->start_date->format('M d') }} - {{ $promotion->end_date->format('M d, Y') }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($promotion->products->count() > 0)
                                        <div>
                                            <span class="badge bg-success">{{ $promotion->products->count() }} products</span>
                                            <div class="small text-muted mt-1" style="max-width: 200px;">
                                                {{ $promotion->products->take(2)->pluck('name')->implode(', ') }}
                                                @if($promotion->products->count() > 2)
                                                    +{{ $promotion->products->count() - 2 }} more
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">No products</span>
                                    @endif
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
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-promotion-btn" data-promotion-id="{{ $promotion->id }}" data-promotion-name="{{ $promotion->name }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteForm-{{ $promotion->id }}" action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
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

<script>
// Simple delete confirmation using showConfirm (matches product page style)
document.querySelectorAll('.delete-promotion-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const promotionId = this.getAttribute('data-promotion-id');
        const promotionName = this.getAttribute('data-promotion-name');
        
        showConfirm(
            `Are you sure you want to delete "${promotionName}"? This action cannot be undone.`,
            function() {
                document.getElementById('deleteForm-' + promotionId).submit();
            },
            'Delete Promotion'
        );
    });
});

// Show success message with toast notification if exists
@if(session('success'))
    showToast("{{ session('success') }}", 'success');
@endif

// Show error message with toast notification if exists
@if(session('error'))
    showToast("{{ session('error') }}", 'error');
@endif
</script>

@endsection