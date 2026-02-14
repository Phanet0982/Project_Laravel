@extends('layouts.app')

@section('title', 'Create New Promotion')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-plus-circle me-3"></i>Create New Promotion</h1>
            <p class="text-muted mb-0">Set up a new promotional campaign or discount offer</p>
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
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Promotion Details</h5>
            </div>
            <form action="{{ route('promotions.store') }}" method="POST">
                @csrf
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
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="e.g., Summer Sale 2024" required>
                            @if($errors->has('name'))
                                <div class="text-danger small mt-1">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-percent me-1"></i>Discount Percentage <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="discount_percent" id="discountPercent" value="{{ old('discount_percent') }}" step="0.01" min="0" max="100" placeholder="20" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @if($errors->has('discount_percent'))
                                <div class="text-danger small mt-1">{{ $errors->first('discount_percent') }}</div>
                            @endif
                        </div>
                        
                        <!-- Date Range -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-start me-1"></i>Start Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}" required>
                            @if($errors->has('start_date'))
                                <div class="text-danger small mt-1">{{ $errors->first('start_date') }}</div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar-end me-1"></i>End Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}" required>
                            @if($errors->has('end_date'))
                                <div class="text-danger small mt-1">{{ $errors->first('end_date') }}</div>
                            @endif
                        </div>
                        
                        <!-- Status -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">
                                    <i class="fas fa-toggle-on me-1"></i>Active Promotion
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Promotion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection