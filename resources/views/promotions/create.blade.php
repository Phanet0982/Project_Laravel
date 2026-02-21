@extends('layouts.app')

@section('title', 'Create New Promotion')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-plus-circle me-3"></i>Create New Promotion</h1>
            <p class="text-muted mb-0">Set up a new promotional campaign or discount offer</p>
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

                        <!-- Product Selection -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Select Products</h6>
                        </div>

                        @if(isset($products) && $products->count() > 0)
                            <div class="col-12">
                                <div class="row g-3">
                                    @foreach($products as $product)
                                        <div class="col-md-6">
                                            <div class="form-check card border p-3">
                                                <input class="form-check-input products-checkbox" type="checkbox" name="selected_products[]" value="{{ $product->id }}" id="product{{ $product->id }}">
                                                <label class="form-check-label w-100" for="product{{ $product->id }}">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong>{{ $product->name }}</strong>
                                                            <div class="small text-muted">
                                                                Category: {{ $product->category->name ?? 'Uncategorized' }}
                                                            </div>
                                                            <div class="small text-muted">
                                                                Stock: {{ $product->qty }} units
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="fw-bold">${{ number_format($product->sale_price, 2) }}</div>
                                                            <div class="small text-success" id="discount{{ $product->id }}">
                                                                Save: $<span class="discount-amount" data-price="{{ $product->sale_price }}">0.00</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong id="selectedCount">0</strong> product(s) selected
                                </div>

                                <div class="d-flex gap-2 mb-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAll">
                                        <i class="fas fa-check-double me-1"></i>Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                        <i class="fas fa-times me-1"></i>Deselect All
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>No active products available. 
                                    <a href="{{ route('products.create') }}" class="alert-link">Create a product first.</a>
                                </div>
                            </div>
                        @endif
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

<script>
// Product selection management
const discountPercentInput = document.getElementById('discountPercent');
const productsCheckboxes = document.querySelectorAll('.products-checkbox');
const selectedCountSpan = document.getElementById('selectedCount');
const selectAllBtn = document.getElementById('selectAll');
const deselectAllBtn = document.getElementById('deselectAll');

// Update discount amounts when percentage changes
discountPercentInput.addEventListener('change', updateDiscountAmounts);
discountPercentInput.addEventListener('input', updateDiscountAmounts);

// Update selected count when checkboxes change
productsCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
    checkbox.addEventListener('change', updateDiscountDisplay);
});

// Select All / Deselect All functionality
selectAllBtn.addEventListener('click', function() {
    productsCheckboxes.forEach(checkbox => checkbox.checked = true);
    updateSelectedCount();
    updateDiscountDisplay();
});

deselectAllBtn.addEventListener('click', function() {
    productsCheckboxes.forEach(checkbox => checkbox.checked = false);
    updateSelectedCount();
    updateDiscountDisplay();
});

function updateDiscountAmounts() {
    const discountPercent = parseFloat(discountPercentInput.value) || 0;
    
    document.querySelectorAll('.discount-amount').forEach(element => {
        const price = parseFloat(element.getAttribute('data-price')) || 0;
        const discountAmount = (price * discountPercent / 100).toFixed(2);
        element.textContent = discountAmount;
    });
}

function updateSelectedCount() {
    const checkedCount = document.querySelectorAll('.products-checkbox:checked').length;
    selectedCountSpan.textContent = checkedCount;
}

function updateDiscountDisplay() {
    // Update on initial load and when selections change
    updateDiscountAmounts();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateDiscountAmounts();
    updateSelectedCount();
});
</script>

@endsection