@extends('layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-folder me-3"></i>Categories Management</h1>
            <p class="text-muted mb-0">Organize products by categories for better inventory management</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm" title="View Products">
                <i class="fas fa-box me-2"></i>Products
            </a>
            <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary btn-sm" title="View Promotions">
                <i class="fas fa-tags me-2"></i>Promotions
            </a>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Category
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1">{{ $totalCategories }}</h3>
                <p class="text-muted mb-0">Total Categories</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <h3 class="text-success mb-1">{{ $categoriesWithProducts }}</h3>
                <p class="text-muted mb-0">With Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <h3 class="text-warning mb-1">{{ $emptyCategoriesCount }}</h3>
                <p class="text-muted mb-0">Empty Categories</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>All Categories
            </h5>
        </div>
    </div>
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Color</th>
                            <th>Products</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-{{ $category->icon ?? 'folder' }}" style="color: var(--{{ $category->color ?? 'primary' }}); font-size: 1.2rem;"></i>
                                        <div>
                                            <div class="fw-bold">{{ $category->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $category->description ? Str::limit($category->description, 50) : 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $category->color ?? 'primary' }}">{{ ucfirst($category->color ?? 'primary') }}</span>
                                </td>
                                <td>
                                    @if($category->products_count > 0)
                                        <span class="badge bg-success">{{ $category->products_count }} product(s)</span>
                                    @else
                                        <span class="badge bg-secondary">No products</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $category->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-category-btn" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="deleteForm-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder fa-3x text-muted mb-3"></i>
                <h5>No Categories Found</h5>
                <p class="text-muted">Start by creating your first category</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Category
                </a>
            </div>
        @endif
    </div>
</div>

<script>
// Delete category confirmation using showConfirm
document.querySelectorAll('.delete-category-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const categoryId = this.getAttribute('data-category-id');
        const categoryName = this.getAttribute('data-category-name');
        
        showConfirm(
            `Are you sure you want to delete "${categoryName}"? This action cannot be undone.`,
            function() {
                document.getElementById('deleteForm-' + categoryId).submit();
            },
            'Delete Category'
        );
    });
});

// Show success message with toast notification if exists
@if(session('success'))
    showToast("{{ session('success') }}", 'success');
@endif

// Show error message with toast notification if exists
@if(session('error'))
    showToast("{{ session('error') }}", 'error');
@endif
</script>

@endsection
