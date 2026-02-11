@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-box me-3"></i>Products Management</h1>
            <p class="text-muted mb-0">Manage your inventory, track stock levels, and organize products efficiently</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="fas fa-plus me-2"></i>Add Product
            </button>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $products->total() ?? 0 }}</h4>
                        <p class="text-muted mb-0">Total Products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle p-3 me-3">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $inStock ?? 0 }}</h4>
                        <p class="text-muted mb-0">In Stock</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded-circle p-3 me-3">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $lowStock ?? 0 }}</h4>
                        <p class="text-muted mb-0">Low Stock</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger rounded-circle p-3 me-3">
                        <i class="fas fa-times-circle text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $outOfStock ?? 0 }}</h4>
                        <p class="text-muted mb-0">Out of Stock</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Product Inventory
            </h5>
            <div class="d-flex flex-column flex-md-row gap-3 w-100 w-md-auto">
                <div class="input-group" style="min-width: 250px;">
                    <input type="text" class="form-control" placeholder="Search products..." id="productSearchInput">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <select class="form-select" style="min-width: 200px;" id="categoryFilter">
                    <option value="">All Categories</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request()->category == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    @endif
                </select>
                <select class="form-select" style="min-width: 150px;" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active" {{ request()->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request()->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-image me-2"></i>Image</th>
                        <th><i class="fas fa-tag me-2"></i>Product</th>
                        <th><i class="fas fa-folder me-2"></i>Category</th>
                        <th><i class="fas fa-barcode me-2"></i>Barcode</th>
                        <th><i class="fas fa-dollar-sign me-2"></i>Cost Price</th>
                        <th><i class="fas fa-money-bill me-2"></i>Sale Price</th>
                        <th><i class="fas fa-warehouse me-2"></i>Stock</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($products) && $products->count() > 0)
                        @foreach($products as $product)
                            <tr class="{{ $product->is_active ? '' : 'table-secondary' }}">
                                <td>
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->supplier->name ?? 'No Supplier' }}</small>
                                        @if(!$product->is_active)
                                            <span class="badge bg-secondary ms-2">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $product->category->name ?? 'No Category' }}</span>
                                </td>
                                <td>
                                    <code class="bg-dark text-light px-2 py-1 rounded">{{ $product->barcode ?? 'N/A' }}</code>
                                </td>
                                <td>
                                    <span class="text-muted">${{ number_format($product->cost_price, 2) }}</span>
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
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="editProduct({{ $product->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" title="View" onclick="viewProduct({{ $product->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteProduct({{ $product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <h5>No Products Found</h5>
                                    <p>Start by adding your first product to the inventory</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
                                        <i class="fas fa-plus me-2"></i>Add Product
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if(isset($products) && $products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">
                    <i class="fas fa-plus-circle"></i>
                    Add New Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Product Name
                            </label>
                            <input type="text" class="form-control" name="name" placeholder="Enter product name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-barcode"></i>
                                Barcode
                            </label>
                            <input type="text" class="form-control" name="barcode" placeholder="Product barcode (optional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-folder"></i>
                                Category
                            </label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Select Category</option>
                                @if(isset($categories))
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-truck"></i>
                                Supplier
                            </label>
                            <select class="form-select" name="supplier_id" required>
                                <option value="">Select Supplier</option>
                                @if(isset($suppliers))
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i>
                                Cost Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="cost_price" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-money-bill"></i>
                                Sale Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="sale_price" step="0.01" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-warehouse"></i>
                                Initial Stock
                            </label>
                            <input type="number" class="form-control" name="qty" value="0" min="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                Product Image
                            </label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div class="form-text">Upload a product image (JPG, PNG, GIF - Max 2MB)</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Product
                    </button>
                </div>
            </form>
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
    
    .table img {
        border: 2px solid var(--dark-border);
    }
    
    code {
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
    }
    
    .pagination {
        --bs-pagination-bg: var(--dark-surface);
        --bs-pagination-border-color: var(--dark-border);
        --bs-pagination-color: var(--dark-text);
        --bs-pagination-hover-bg: var(--primary-color);
        --bs-pagination-hover-border-color: var(--primary-color);
        --bs-pagination-active-bg: var(--primary-color);
        --bs-pagination-active-border-color: var(--primary-color);
    }
    
    /* Responsive Table */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.8rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .card-header {
            padding: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .table {
            font-size: 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 0.25rem;
        }
        
        .input-group,
        .form-select {
            min-width: 200px;
        }
        
        .page-header h1 {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Form validation and enhancement
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const costPrice = parseFloat(document.querySelector('input[name="cost_price"]').value);
        const salePrice = parseFloat(document.querySelector('input[name="sale_price"]').value);
        
        if (salePrice <= costPrice) {
            e.preventDefault();
            showToast('Sale price should be higher than cost price for profit margin.', 'warning');
            return false;
        }
    });
    
    // Product search functionality
    function filterProducts() {
        const searchTerm = document.getElementById('productSearchInput').value.toLowerCase().trim();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
        const products = document.querySelectorAll('tbody tr:not(.no-results)');
        let visibleCount = 0;
        
        products.forEach(row => {
            const productName = row.querySelector('td:nth-child(2) .fw-bold')?.textContent.toLowerCase() || '';
            const productCategory = row.querySelector('td:nth-child(3) .badge')?.textContent.toLowerCase() || '';
            const productSupplier = row.querySelector('td:nth-child(2) small')?.textContent.toLowerCase() || '';
            const rowCategory = row.querySelector('td:nth-child(3) .badge')?.closest('td').dataset.category || '';
            const isActive = !row.classList.contains('table-secondary');
            
            // Check search term
            const matchesSearch = searchTerm === '' || 
                productName.includes(searchTerm) || 
                productCategory.includes(searchTerm) || 
                productSupplier.includes(searchTerm);
            
            // Check category filter
            const matchesCategory = categoryFilter === '' || rowCategory === categoryFilter;
            
            // Check status filter
            const matchesStatus = statusFilter === '' || 
                (statusFilter === 'active' && isActive) || 
                (statusFilter === 'inactive' && !isActive);
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show "no results" message if needed
        const noResultsRow = document.querySelector('.no-results');
        if (noResultsRow) {
            noResultsRow.remove();
        }
        
        if ((searchTerm !== '' || categoryFilter !== '' || statusFilter !== '') && visibleCount === 0) {
            const tbody = document.querySelector('tbody');
            const noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-results';
            noResultsRow.innerHTML = `
                <td colspan="8" class="text-center py-4">
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No products found matching your criteria</p>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    }
    
    // Attach event listeners
    document.getElementById('productSearchInput').addEventListener('input', filterProducts);
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);
    document.getElementById('statusFilter').addEventListener('change', filterProducts);

    // Image preview
    document.querySelector('input[name="image"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview functionality here
                console.log('Image selected:', file.name);
            };
            reader.readAsDataURL(file);
        }
    });

    // Edit product function
    function editProduct(productId) {
        // Redirect to edit page or open edit modal
        window.location.href = `/products/${productId}/edit`;
    }

    // View product function
    function viewProduct(productId) {
        // Redirect to view page or open view modal
        window.location.href = `/products/${productId}`;
    }

    // Delete product function
    function deleteProduct(productId) {
        showConfirm(
            'Are you sure you want to delete this product? This action cannot be undone.',
            function() {
                document.getElementById('delete-form-' + productId).submit();
            },
            'Delete Product'
        );
    }
</script>
@endpush
@endsection