@extends('layouts.app')

@section('title', 'View Category')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-eye me-3"></i>View Category</h1>
            <p class="text-muted mb-0">Review category details and associated products</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm" title="View Products">
                <i class="fas fa-box me-2"></i>Products
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Category Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-{{ $category->icon ?? 'folder' }}" style="color: var(--{{ $category->color ?? 'primary' }}); font-size: 1.5rem;"></i>
                        <h5 class="mb-0">{{ $category->name }}</h5>
                    </div>
                    <span class="badge bg-{{ $category->color ?? 'primary' }}">{{ ucfirst($category->color ?? 'primary') }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label text-muted">
                            <i class="fas fa-tag me-1"></i>Category Name
                        </label>
                        <p class="fw-bold fs-5">{{ $category->name }}</p>
                    </div>

                    @if($category->description)
                        <div class="col-12">
                            <label class="form-label text-muted">
                                <i class="fas fa-align-left me-1"></i>Description
                            </label>
                            <p class="fw-normal">{{ $category->description }}</p>
                        </div>
                    @endif

                    <!-- Display Settings -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Display Settings</h6>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="fas fa-palette me-1"></i>Color
                        </label>
                        <p><span class="badge bg-{{ $category->color ?? 'primary' }}" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ ucfirst($category->color ?? 'primary') }}</span></p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">
                            <i class="fas fa-icons me-1"></i>Icon
                        </label>
                        <p><i class="fas fa-{{ $category->icon ?? 'folder' }}" style="font-size: 1.5rem; color: var(--{{ $category->color ?? 'primary' }});"></i> <code>{{ $category->icon ?? 'folder' }}</code></p>
                    </div>

                    <!-- Dates Info -->
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Time Information</h6>
                            <p class="mb-1">Created: {{ $category->created_at->format('M j, Y g:i A') }}</p>
                            <p class="mb-0">Last Updated: {{ $category->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Products Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Associated Products ({{ $category->products->count() }})</h5>
                    <a href="{{ route('products.create') }}" class="btn btn-sm btn-outline-primary" title="Add Product">
                        <i class="fas fa-plus me-1"></i>Add Product
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Barcode</th>
                                    <th>Sale Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                                                {{ $product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <code>{{ $product->barcode ?? 'N/A' }}</code>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format($product->sale_price, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($product->qty <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($product->qty <= 10)
                                                <span class="badge bg-warning">{{ $product->qty }} Low</span>
                                            @else
                                                <span class="badge bg-success">{{ $product->qty }} In Stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No products are currently in this category.
                        <a href="{{ route('products.create') }}" class="alert-link">
                            Add a product to this category.
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <div class="d-flex gap-2 ms-auto">
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Category
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
