@extends('layouts.app')

@section('title', 'Create New Category')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-plus-circle me-3"></i>Create New Category</h1>
            <p class="text-muted mb-0">Add a new product category to your inventory</p>
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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Category Details</h5>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="e.g., Electronics, Clothing, Food" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-align-left me-1"></i>Description
                            </label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Enter category description (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Display Settings -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Display Settings</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-palette me-1"></i>Color
                            </label>
                            <select class="form-select" name="color">
                                <option value="primary" selected>Blue (Primary)</option>
                                <option value="success">Green (Success)</option>
                                <option value="danger">Red (Danger)</option>
                                <option value="warning">Orange (Warning)</option>
                                <option value="info">Cyan (Info)</option>
                                <option value="secondary">Gray (Secondary)</option>
                            </select>
                            @error('color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-icons me-1"></i>Icon
                            </label>
                            <input type="text" class="form-control" name="icon" value="{{ old('icon', 'folder') }}" placeholder="e.g., box, shopping-bag, clothes">
                            <small class="text-muted">Font Awesome icon name (without 'fa-')</small>
                            @error('icon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
