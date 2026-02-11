@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-user-edit me-3"></i>Edit Employee</h1>
            <p class="text-muted mb-0">Update employee information and role permissions</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Employees
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>Employee Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-12 text-center">
                            <div class="bg-{{ $employee->role_badge_color }} rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas {{ $employee->role_icon }} fa-2x text-white"></i>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name
                            </label>
                            <input type="text" class="form-control" name="name" value="{{ $employee->name }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-at"></i>
                                Username
                            </label>
                            <input type="text" class="form-control" name="username" value="{{ $employee->username }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                            </label>
                            <input type="email" class="form-control" name="email" value="{{ $employee->email }}" placeholder="Enter email address">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="form-control" name="phone" value="{{ $employee->phone }}" placeholder="Enter phone number">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-id-badge"></i>
                                Role
                            </label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="cashier" {{ $employee->role == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                <option value="manager" {{ $employee->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                New Password (Optional)
                            </label>
                            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                            <div class="form-text">Only fill this if you want to change the password</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $employee->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-toggle-on me-2"></i>Active Employee
                                </label>
                                <div class="form-text">Inactive employees cannot login to the system</div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Role Permissions:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><strong>Cashier:</strong> POS access, basic sales operations</li>
                                    <li><strong>Manager:</strong> Full access except system settings</li>
                                    <li><strong>Admin:</strong> Complete system access and management</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Update Employee
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-text {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
</style>
@endpush
@endsection