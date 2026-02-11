@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-box me-3"></i>Product Details</h1>
            <p class="text-muted mb-0">View complete product information and inventory status</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Product
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-image me-2"></i>Product Image
                </h5>
            </div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 300px; border: 2px solid var(--dark-border);">
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                        <div class="text-center">
                            <i class="fas fa-image fa-4x text-white mb-3"></i>
                            <p class="text-white mb-0">No Image Available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Stock Status
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($product->qty <= 0)
                        <span class="badge bg-danger fs-5 px-4 py-2">Out of Stock</span>
                    @elseif($product->qty <= 10)
                        <span class="badge bg-warning fs-5 px-4 py-2">Low Stock</span>
                    @else
                        <span class="badge bg-success fs-5 px-4 py-2">In Stock</span>
                    @endif
                </div>
                <div class="text-center">
                    <h2 class="mb-0">{{ $product->qty }}</h2>
                    <p class="text-muted mb-0">Units Available</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Product Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Product Name</label>
                        <p class="fw-bold fs-5">{{ $product->name }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Barcode</label>
                        <p class="fw-bold">
                            @if($product->barcode)
                                <code class="bg-dark text-light px-3 py-2 rounded fs-6">{{ $product->barcode }}</code>
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Category</label>
                        <p>
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-folder me-2"></i>{{ $product->category->name ?? 'No Category' }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Supplier</label>
                        <p class="fw-bold">
                            <i class="fas fa-truck me-2"></i>{{ $product->supplier->name ?? 'No Supplier' }}
                        </p>
                    </div>
                    
                    <div class="col-12"><hr></div>
                    
                    <div class="col-md-4">
                        <label class="form-label text-muted">Cost Price</label>
                        <p class="fs-4 text-muted mb-0">${{ number_format($product->cost_price, 2) }}</p>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label text-muted">Sale Price</label>
                        <p class="fs-4 text-success fw-bold mb-0">${{ number_format($product->sale_price, 2) }}</p>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label text-muted">Profit Margin</label>
                        <p class="fs-4 text-info fw-bold mb-0">
                            ${{ number_format($product->sale_price - $product->cost_price, 2) }}
                            <small class="text-muted fs-6">
                                ({{ $product->cost_price > 0 ? number_format((($product->sale_price - $product->cost_price) / $product->cost_price) * 100, 1) : 0 }}%)
                            </small>
                        </p>
                    </div>
                    
                    <div class="col-12"><hr></div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-bold">
                            <i class="fas fa-calendar me-2"></i>{{ $product->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="fw-bold">
                            <i class="fas fa-clock me-2"></i>{{ $product->updated_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i>Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </a>
                    <button type="button" class="btn btn-danger" onclick="deleteProduct({{ $product->id }})">
                        <i class="fas fa-trash me-2"></i>Delete Product
                    </button>
                    <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    code {
        font-family: 'Courier New', monospace;
    }
</style>
@endpush
@endsection


@push('scripts')
<script>
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
