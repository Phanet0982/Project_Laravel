@extends('layouts.app')

@section('title', 'Edit Promotion')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-edit me-3"></i>Edit Promotion</h1>
            <p class="text-muted mb-0">Modify promotional campaign details and associated products</p>
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

        <!-- Product Selection Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Associated Products</h5>
                <small class="text-muted d-block mt-2">Select which products will receive this {{ $promotion->discount_percent }}% discount</small>
            </div>
            <form action="{{ route('promotions.updateProducts', $promotion->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="row g-3 mb-4">
                            @foreach($products as $product)
                                <div class="col-md-6">
                                    <div class="form-check card border p-3">
                                        <input class="form-check-input products-checkbox" type="checkbox" name="selected_products[]" value="{{ $product->id }}" id="product{{ $product->id }}" {{ in_array($product->id, $selectedProducts) ? 'checked' : '' }}>
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
                                                        Save: $<span class="discount-amount" data-price="{{ $product->sale_price }}" data-product-id="{{ $product->id }}">
                                                            @if(in_array($product->id, $selectedProducts))
                                                                @php
                                                                    $productInPromotion = $promotion->products->firstWhere('id', $product->id);
                                                                    $discountAmount = $productInPromotion ? $productInPromotion->pivot->discount_amount : ($product->sale_price * $promotion->discount_percent / 100);
                                                                @endphp
                                                                {{ number_format($discountAmount, 2) }}
                                                            @else
                                                                {{ number_format($product->sale_price * $promotion->discount_percent / 100, 2) }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong id="selectedCount">0</strong> product(s) selected
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAll">
                                <i class="fas fa-check-double me-1"></i>Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                <i class="fas fa-times me-1"></i>Deselect All
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>No active products available. 
                            <a href="{{ route('products.create') }}" class="alert-link">Create a product first.</a>
                        </div>
                    @endif
                </div>
                @if($products->count() > 0)
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('promotions.show', $promotion->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success" id="saveProductsBtn" disabled>
                                <i class="fas fa-save me-2"></i>Save Product Selection
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
// Product selection management
const productsCheckboxes = document.querySelectorAll('.products-checkbox');
const selectedCountDisplay = document.getElementById('selectedCount');
const saveProductsBtn = document.getElementById('saveProductsBtn');
const selectAllBtn = document.getElementById('selectAll');
const deselectAllBtn = document.getElementById('deselectAll');

function updateSelectedCount() {
    const checkedCount = document.querySelectorAll('.products-checkbox:checked').length;
    selectedCountDisplay.textContent = checkedCount;
    saveProductsBtn.disabled = checkedCount === 0;
}

productsCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

selectAllBtn?.addEventListener('click', () => {
    productsCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
});

deselectAllBtn?.addEventListener('click', () => {
    productsCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
});

// Initialize
updateSelectedCount();
</script>

@endsection