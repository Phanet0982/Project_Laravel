@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-user-edit me-3"></i>Edit Customer</h1>
            <p class="text-muted mb-0">Update customer information and membership details</p>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Customers
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-12 text-center">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ $customer->name }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="form-control" name="phone" value="{{ $customer->phone }}" placeholder="Enter phone number">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                            </label>
                            <input type="email" class="form-control" name="email" value="{{ $customer->email }}" placeholder="Enter email address">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-star"></i>
                                Membership Type
                            </label>
                            <select class="form-select" name="member_type" required>
                                <option value="regular" {{ $customer->member_type == 'regular' ? 'selected' : '' }}>Regular Member</option>
                                <option value="vip" {{ $customer->member_type == 'vip' ? 'selected' : '' }}>VIP Member</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Address
                            </label>
                            <textarea class="form-control" name="address" rows="3" placeholder="Enter customer's address">{{ $customer->address }}</textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Customer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
