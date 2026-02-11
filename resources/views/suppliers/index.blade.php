@extends('layouts.app')

@section('title', 'Suppliers Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-truck me-3"></i>Suppliers Management</h1>
            <p class="text-muted mb-0">Manage your suppliers and vendor relationships</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Add Supplier
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-truck text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $suppliers->total() ?? 0 }}</h4>
                        <p class="text-muted mb-0">Total Suppliers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle p-3 me-3">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $activeProducts ?? 0 }}</h4>
                        <p class="text-muted mb-0">Active Products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info rounded-circle p-3 me-3">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">${{ number_format($totalValue ?? 0, 2) }}</h4>
                        <p class="text-muted mb-0">Total Value</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded-circle p-3 me-3">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $lowStockItems ?? 0 }}</h4>
                        <p class="text-muted mb-0">Low Stock Items</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Supplier List
            </h5>
            <div class="d-flex gap-3">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Search suppliers..." id="supplierSearch">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-card me-2"></i>ID</th>
                        <th><i class="fas fa-building me-2"></i>Supplier Name</th>
                        <th><i class="fas fa-phone me-2"></i>Phone</th>
                        <th><i class="fas fa-map-marker-alt me-2"></i>Address</th>
                        <th><i class="fas fa-box me-2"></i>Products</th>
                        <th><i class="fas fa-calendar me-2"></i>Created</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($suppliers) && $suppliers->count() > 0)
                        @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $supplier->name }}</div>
                                </td>
                                <td>
                                    @if($supplier->phone)
                                        <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i>{{ $supplier->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">No phone</span>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->address)
                                        <small>{{ Str::limit($supplier->address, 50) }}</small>
                                    @else
                                        <span class="text-muted">No address</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $supplier->products_count ?? 0 }} products</span>
                                </td>
                                <td>
                                    <small>{{ $supplier->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" 
                                                onclick="deleteSupplier({{ $supplier->id }})" 
                                                data-supplier-name="{{ $supplier->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $supplier->id }}" 
                                              action="{{ route('suppliers.destroy', $supplier->id) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-truck-loading fa-3x mb-3"></i>
                                    <h5>No Suppliers Found</h5>
                                    <p>Start by adding your first supplier</p>
                                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add Supplier
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if(isset($suppliers) && $suppliers->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function deleteSupplier(supplierId) {
        const button = event.target.closest('button');
        const supplierName = button.dataset.supplierName;
        
        showConfirm(
            `Are you sure you want to delete supplier "${supplierName}"? This action cannot be undone.`,
            function() {
                document.getElementById('delete-form-' + supplierId).submit();
            },
            'Delete Supplier'
        );
    }
    
    // Supplier search functionality
    document.getElementById('supplierSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const suppliers = document.querySelectorAll('tbody tr:not(.no-results)');
        
        let visibleCount = 0;
        
        suppliers.forEach(row => {
            const supplierName = row.querySelector('td:nth-child(2) .fw-bold')?.textContent.toLowerCase() || '';
            const supplierAddress = row.querySelector('td:nth-child(4) small')?.textContent.toLowerCase() || '';
            
            if (searchTerm === '' || 
                supplierName.includes(searchTerm) || 
                supplierAddress.includes(searchTerm)) {
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
        
        if (searchTerm !== '' && visibleCount === 0) {
            const tbody = document.querySelector('tbody');
            const noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-results';
            noResultsRow.innerHTML = `
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No suppliers found matching "${searchTerm}"</p>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    });
</script>
@endpush
@endsection