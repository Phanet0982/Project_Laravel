@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-truck me-3"></i>Supplier Details</h1>
            <p class="text-muted mb-0">View supplier information and products</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Supplier
            </a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Suppliers
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Supplier Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Supplier Name</label>
                        <p class="mb-0">{{ $supplier->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <p class="mb-0">
                            @if($supplier->phone)
                                <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>{{ $supplier->phone }}
                                </a>
                            @else
                                <span class="text-muted">No phone provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="mb-0">
                            @if($supplier->email)
                                <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>{{ $supplier->email }}
                                </a>
                            @else
                                <span class="text-muted">No email provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Created</label>
                        <p class="mb-0">
                            <i class="fas fa-calendar me-1"></i>{{ $supplier->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <p class="mb-0">
                            @if($supplier->address)
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $supplier->address }}
                            @else
                                <span class="text-muted">No address provided</span>
                            @endif
                        </p>
                    </div>
                    @if($supplier->notes)
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <p class="mb-0">
                            <i class="fas fa-sticky-note me-1"></i>{{ $supplier->notes }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Products ({{ $supplier->products->count() }})</h5>
            </div>
            <div class="card-body">
                @if($supplier->products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->image)
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $product->name }}</div>
                                                    <small class="text-muted">{{ $product->barcode ?? 'No barcode' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $product->category->name ?? 'No Category' }}</span>
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
                                            <span class="fw-bold text-success">${{ number_format($product->sale_price, 2) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5>No Products Found</h5>
                        <p class="text-muted">This supplier doesn't have any products yet.</p>
                        <a href="{{ route('products.create') }}?supplier_id={{ $supplier->id }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Product
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Products</span>
                    <span class="badge bg-primary fs-6">{{ $supplier->products->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>In Stock Products</span>
                    <span class="badge bg-success fs-6">{{ $supplier->products->where('qty', '>', 0)->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Out of Stock</span>
                    <span class="badge bg-danger fs-6">{{ $supplier->products->where('qty', '<=', 0)->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Total Inventory Value</span>
                    <span class="fw-bold text-success">
                        ${{ number_format($supplier->products->sum(function($product) { return $product->qty * $product->cost_price; }), 2) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('products.create') }}?supplier_id={{ $supplier->id }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Add Product
                    </a>
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Supplier
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteSupplier()">
                        <i class="fas fa-trash me-2"></i>Delete Supplier
                    </button>
                    <form id="delete-form" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deleteSupplier() {
        showConfirm(
            'Are you sure you want to delete this supplier? This action cannot be undone.',
            function() {
                document.getElementById('delete-form').submit();
            },
            'Delete Supplier'
        );
    }
</script>
@endpush
@endsection