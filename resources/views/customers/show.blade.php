@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-user me-3"></i>Customer Details</h1>
            <p class="text-muted mb-0">View customer information and purchase history</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Customer
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Customers
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>Customer Profile
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                    <i class="fas fa-user fa-3x text-white"></i>
                </div>
                <h4 class="mb-2">{{ $customer->name }}</h4>
                <p class="text-muted mb-3">Customer ID: #{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</p>
                
                @if($customer->member_type === 'vip')
                    <span class="badge bg-warning fs-6 px-4 py-2">
                        <i class="fas fa-crown me-2"></i>VIP Member
                    </span>
                @else
                    <span class="badge bg-success fs-6 px-4 py-2">
                        <i class="fas fa-user me-2"></i>Regular Member
                    </span>
                @endif
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Total Purchases</label>
                    <h4 class="mb-0">0</h4>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Total Spent</label>
                    <h4 class="mb-0 text-success">$0.00</h4>
                </div>
                <div>
                    <label class="form-label text-muted">Member Since</label>
                    <p class="fw-bold mb-0">{{ $customer->created_at->format('M d, Y') }}</p>
                    <small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Contact Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Full Name</label>
                        <p class="fw-bold fs-5">{{ $customer->name }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Membership Type</label>
                        <p>
                            @if($customer->member_type === 'vip')
                                <span class="badge bg-warning fs-6 px-3 py-2">
                                    <i class="fas fa-crown me-2"></i>VIP Member
                                </span>
                            @else
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-user me-2"></i>Regular Member
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Phone Number</label>
                        <p class="fw-bold">
                            @if($customer->phone)
                                <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-2"></i>{{ $customer->phone }}
                                </a>
                            @else
                                <span class="text-muted">Not Provided</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email Address</label>
                        <p class="fw-bold">
                            @if($customer->email)
                                <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-2"></i>{{ $customer->email }}
                                </a>
                            @else
                                <span class="text-muted">Not Provided</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label text-muted">Address</label>
                        <p class="fw-bold">
                            @if($customer->address)
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $customer->address }}
                            @else
                                <span class="text-muted">Not Provided</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-12"><hr></div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Registered On</label>
                        <p class="fw-bold">
                            <i class="fas fa-calendar me-2"></i>{{ $customer->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="fw-bold">
                            <i class="fas fa-clock me-2"></i>{{ $customer->updated_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Purchase History
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Purchase History</h5>
                    <p class="text-muted">This customer hasn't made any purchases yet</p>
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
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Customer
                    </a>
                    @if($customer->email)
                    <a href="mailto:{{ $customer->email }}" class="btn btn-success">
                        <i class="fas fa-envelope me-2"></i>Send Email
                    </a>
                    @endif
                    <button type="button" class="btn btn-danger" onclick="deleteCustomer({{ $customer->id }})">
                        <i class="fas fa-trash me-2"></i>Delete Customer
                    </button>
                    <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: none;">
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
    a.text-decoration-none:hover {
        color: var(--primary-color) !important;
    }
</style>
@endpush
@endsection


@push('scripts')
<script>
    function deleteCustomer(customerId) {
        showConfirm(
            'Are you sure you want to delete this customer? This action cannot be undone.',
            function() {
                document.getElementById('delete-form-' + customerId).submit();
            },
            'Delete Customer'
        );
    }
</script>
@endpush
