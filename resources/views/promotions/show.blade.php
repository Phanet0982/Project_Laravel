@extends('layouts.app')

@section('title', 'View Promotion')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-eye me-3"></i>View Promotion</h1>
            <p class="text-muted mb-0">Review promotion details and selected products</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm" title="View All Products">
                <i class="fas fa-box me-2"></i>Products
            </a>
            <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary btn-sm" title="Go to POS">
                <i class="fas fa-cash-register me-2"></i>POS
            </a>
            <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Promotions
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Promotion Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>{{ $promotion->name }}</h5>
                    @if($promotion->is_active && $promotion->start_date <= now() && $promotion->end_date >= now())
                        <span class="badge bg-success">Active</span>
                    @elseif($promotion->end_date < now())
                        <span class="badge bg-danger">Expired</span>
                    @elseif($promotion->start_date > now())
                        <span class="badge bg-warning">Upcoming</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    </div>
                    
                    <div class="col-md-8">
                        <label class="form-label text-muted">
                            <i class="fas fa-tag me-1"></i>Promotion Name
                        </label>
                        <p class="fw-bold fs-5">{{ $promotion->name }}</p>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label text-muted">
                            <i class="fas fa-percent me-1"></i>Discount
                        </label>
                        <p class="fw-bold fs-5">{{ $promotion->discount_percent }}%</p>
                    </div>
                    
                    <!-- Date Range -->
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="fas fa-calendar-start me-1"></i>Start Date
                        </label>
                        <p class="fw-bold">{{ $promotion->start_date->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="fas fa-calendar-end me-1"></i>End Date
                        </label>
                        <p class="fw-bold">{{ $promotion->end_date->format('M d, Y') }}</p>
                    </div>
                    
                    <!-- Status -->
                    <div class="col-12">
                        <label class="form-label text-muted">
                            <i class="fas fa-toggle-on me-1"></i>Status
                        </label>
                        <p>
                            @if($promotion->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    
                    <!-- Dates Info -->
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Time Information</h6>
                            <p class="mb-1">Created: {{ $promotion->created_at->format('M j, Y g:i A') }}</p>
                            <p class="mb-0">Last Updated: {{ $promotion->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Products Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Associated Products ({{ $promotion->products->count() }})</h5>
                    <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Manage Products
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($promotion->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Sale Price</th>
                                    <th>Discount ({{ $promotion->discount_percent }}%)</th>
                                    <th>Final Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promotion->products as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-danger">-${{ number_format($product->pivot->discount_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format($product->sale_price - $product->pivot->discount_amount, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>No products are currently associated with this promotion.
                        <a href="{{ route('promotions.edit', $promotion->id) }}" class="alert-link">
                            Select products to apply this discount.
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card-footer mt-4">
            <div class="d-flex justify-content-between gap-2">
                <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Promotion
                    </a>
                    <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this promotion?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
