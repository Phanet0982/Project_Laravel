@extends('layouts.app')

@section('title', 'Promotion Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-tag me-3"></i>{{ $promotion->name }}</h1>
            <p class="text-muted mb-0">Detailed view of promotional campaign</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Edit Promotion
            </a>
            <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Promotions
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Promotion Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Name</h6>
                        <p class="fw-bold">{{ $promotion->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <p>
                            @if($promotion->active && $promotion->isActive())
                                <span class="badge bg-success fs-6">Active & Available</span>
                            @elseif($promotion->active)
                                <span class="badge bg-warning fs-6">Active but Expired</span>
                            @else
                                <span class="badge bg-secondary fs-6">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Discount</h6>
                        <p class="fw-bold fs-5 text-primary">{{ $promotion->formatted_discount }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Start Date</h6>
                        <p class="fw-bold">{{ $promotion->start_date->format('F j, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">End Date</h6>
                        <p class="fw-bold">{{ $promotion->end_date->format('F j, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Days Remaining</h6>
                        <p class="fw-bold">
                            @if($promotion->end_date >= now())
                                {{ now()->diffInDays($promotion->end_date) }} days
                            @else
                                <span class="text-danger">Expired</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                        <i class="fas fa-tag fa-2x text-white"></i>
                    </div>
                    <h4 class="mb-1">{{ $promotion->name }}</h4>
                    <p class="text-muted mb-0">{{ $promotion->formatted_discount }} OFF</p>
                </div>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h5 class="text-success mb-1">
                            @if($promotion->isActive())
                                <i class="fas fa-check-circle"></i>
                            @else
                                <i class="fas fa-times-circle"></i>
                            @endif
                        </h5>
                        <small class="text-muted">Currently Active</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h5 class="text-info mb-1">
                            @if($promotion->end_date >= now())
                                <i class="fas fa-clock"></i>
                            @else
                                <i class="fas fa-history"></i>
                            @endif
                        </h5>
                        <small class="text-muted">Valid Period</small>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Simple Promotion:</strong> Basic percentage discount applied to all products
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Promotion
                    </a>
                    
                    <form action="{{ route('promotions.toggleStatus', $promotion->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $promotion->active ? 'btn-warning' : 'btn-success' }} w-100">
                            <i class="fas {{ $promotion->active ? 'fa-toggle-on' : 'fa-toggle-off' }} me-2"></i>
                            {{ $promotion->active ? 'Deactivate' : 'Activate' }} Promotion
                        </button>
                    </form>
                    
                    <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this promotion? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete Promotion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection