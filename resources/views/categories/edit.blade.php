@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-edit me-3"></i>Edit Category</h1>
            <p class="text-muted mb-0">Modify category details and settings</p>
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
                <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Edit: {{ $category->name }}</h5>
            </div>
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
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
                            <input type="text" class="form-control" name="name" value="{{ old('name', $category->name) }}" placeholder="e.g., Electronics, Clothing, Food" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-align-left me-1"></i>Description
                            </label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Enter category description (optional)">{{ old('description', $category->description) }}</textarea>
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
                                <option value="primary" {{ old('color', $category->color) === 'primary' ? 'selected' : '' }}>Blue (Primary)</option>
                                <option value="success" {{ old('color', $category->color) === 'success' ? 'selected' : '' }}>Green (Success)</option>
                                <option value="danger" {{ old('color', $category->color) === 'danger' ? 'selected' : '' }}>Red (Danger)</option>
                                <option value="warning" {{ old('color', $category->color) === 'warning' ? 'selected' : '' }}>Orange (Warning)</option>
                                <option value="info" {{ old('color', $category->color) === 'info' ? 'selected' : '' }}>Cyan (Info)</option>
                                <option value="secondary" {{ old('color', $category->color) === 'secondary' ? 'selected' : '' }}>Gray (Secondary)</option>
                            </select>
                            @error('color')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-icons me-1"></i>Icon
                            </label>
                            <input type="text" class="form-control" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="e.g., box, shopping-bag, clothes">
                            <small class="text-muted">Font Awesome icon name (without 'fa-')</small>
                            @error('icon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Information</h6>
                                <p class="mb-1">Created: {{ $category->created_at->format('M j, Y g:i A') }}</p>
                                <p class="mb-0">Last Updated: {{ $category->updated_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
