@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-edit me-3"></i>Edit Product</h1>
            <p class="text-muted mb-0">Update product information and inventory details</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>Product Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        @if($product->image)
                        <div class="col-12 text-center">
                            <div class="mb-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 2px solid var(--dark-border);">
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Product Name
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ $product->name }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-barcode"></i>
                                Barcode
                            </label>
                            <input type="text" class="form-control" name="barcode" value="{{ $product->barcode }}" placeholder="Product barcode (optional)">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-folder"></i>
                                Category
                            </label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-truck"></i>
                                Supplier
                            </label>
                            <select class="form-select" name="supplier_id" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i>
                                Cost Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="cost_price" value="{{ $product->cost_price }}" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-money-bill"></i>
                                Sale Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="sale_price" value="{{ $product->sale_price }}" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-warehouse"></i>
                                Current Stock
                            </label>
                            <input type="number" class="form-control" name="qty" value="{{ $product->qty }}" min="0" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                Product Image
                            </label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div class="form-text">Upload a new image to replace the current one (JPG, PNG, GIF - Max 2MB)</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Product
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-text {
        color: var(--dark-text-muted);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
</style>
@endpush
@endsection
