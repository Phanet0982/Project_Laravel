@extends('layouts.app')

@section('title', 'Edit Promotion')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-edit me-3"></i>Edit Promotion</h1>
            <p class="text-muted mb-0">Modify promotional campaign details</p>
        </div>
        <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Promotions
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Edit: {{ $promotion->name }}</h5>
            </div>
            <form action="{{ route('promotions.update', $promotion->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>Promotion Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $promotion->name) }}" placeholder="e.g., Summer Sale 2024" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-percent me-1"></i>Discount Percentage <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="discount_percent" id="discountPercent" value="{{ old('discount_percent', $promotion->discount_percent) }}" step="0.01" min="0" max="100" placeholder="20" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('discount_percent')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Date Range -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-start me-1"></i>Start Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $promotion->start_date->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-end me-1"></i>End Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $promotion->end_date->format('Y-m-d')) }}" required>
                            @error('end_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', $promotion->active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">
                                    <i class="fas fa-toggle-on me-1"></i>Active Promotion
                                </label>
                            </div>
                        </div>
                        
                        <!-- Current Status Info -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Current Status</h6>
                                <p class="mb-1">Created: {{ $promotion->created_at->format('M j, Y g:i A') }}</p>
                                <p class="mb-0">Last Updated: {{ $promotion->updated_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Promotion
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date validation
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');
    
    function validateDates() {
        if (startDate.value && endDate.value) {
            if (new Date(endDate.value) <= new Date(startDate.value)) {
                endDate.setCustomValidity('End date must be after start date');
            } else {
                endDate.setCustomValidity('');
            }
        }
    }
    
    startDate.addEventListener('change', validateDates);
    endDate.addEventListener('change', validateDates);
    
    // Set today as minimum date for start date
    const today = new Date().toISOString().split('T')[0];
    startDate.min = today;
});
</script>
@endpush
@endsection