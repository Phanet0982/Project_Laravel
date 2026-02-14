@extends('layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-users me-3"></i>Customer Management</h1>
            <p class="text-muted mb-0">Manage customer relationships, track purchase history, and loyalty programs</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal">
                <i class="fas fa-user-plus me-2"></i>Add Customer
            </button>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $customers->total() ?? 0 }}</h4>
                        <p class="text-muted mb-0">Total Customers</p>
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
                        <i class="fas fa-crown text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $vipMembers ?? 0 }}</h4>
                        <p class="text-muted mb-0">VIP Members</p>
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
                        <i class="fas fa-user-check text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $regularMembers ?? 0 }}</h4>
                        <p class="text-muted mb-0">Regular Members</p>
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
                        <i class="fas fa-calendar-day text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $newThisMonth ?? 0 }}</h4>
                        <p class="text-muted mb-0">New This Month</p>
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
                <i class="fas fa-address-book me-2"></i>Customer Directory
            </h5>
            <div class="d-flex gap-3">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Search customers..." id="customerSearch">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <select class="form-select" style="width: 200px;">
                    <option>All Members</option>
                    <option value="regular">Regular</option>
                    <option value="vip">VIP</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-user me-2"></i>Customer</th>
                        <th><i class="fas fa-phone me-2"></i>Contact</th>
                        <th><i class="fas fa-envelope me-2"></i>Email</th>
                        <th><i class="fas fa-map-marker-alt me-2"></i>Address</th>
                        <th><i class="fas fa-star me-2"></i>Membership</th>
                        <th><i class="fas fa-calendar me-2"></i>Joined</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($customers) && $customers->count() > 0)
                        @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $customer->name }}</div>
                                            <small class="text-muted">ID: #{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->phone)
                                        <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i>{{ $customer->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">No phone</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->email)
                                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope me-1"></i>{{ $customer->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">No email</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->address)
                                        <span title="{{ $customer->address }}">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($customer->address, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted">No address</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->member_type === 'vip')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-crown me-1"></i>VIP
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-user me-1"></i>Regular
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $customer->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" title="Edit Customer" onclick="editCustomer({{ $customer->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" title="View History" onclick="viewCustomer({{ $customer->id }})">
                                            <i class="fas fa-history"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" title="Send Message" onclick="sendMessage('{{ $customer->email }}')">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteCustomer({{ $customer->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: none;">
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
                                    <i class="fas fa-user-friends fa-3x mb-3"></i>
                                    <h5>No Customers Found</h5>
                                    <p>Start building your customer base by adding your first customer</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal">
                                        <i class="fas fa-user-plus me-2"></i>Add Customer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if(isset($customers) && $customers->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">
                    <i class="fas fa-user-plus"></i>
                    Add New Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="customerForm" action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="text-center mb-4">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name
                            </label>
                            <input type="text" class="form-control" name="name" placeholder="Enter customer's full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="form-control" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email Address
                            </label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email address">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-star"></i>
                                Membership Type
                            </label>
                            <select class="form-select" name="member_type" required>
                                <option value="regular">Regular Member</option>
                                <option value="vip">VIP Member</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Address
                            </label>
                            <textarea class="form-control" name="address" rows="3" placeholder="Enter customer's address"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Membership Benefits:</strong>
                                <ul class="mb-0 mt-2">
                                    <li><strong>Regular:</strong> Standard pricing and basic support</li>
                                    <li><strong>VIP:</strong> Special discounts, priority support, and exclusive offers</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .alert ul {
        padding-left: 1.5rem;
    }
    
    .alert ul li {
        margin-bottom: 0.25rem;
    }
    
    a.text-decoration-none:hover {
        color: var(--primary-color) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Phone number formatting
    document.querySelector('input[name="phone"]').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{3})/, '$1-$2');
        }
        e.target.value = value;
    });

    // Form validation
    document.getElementById('customerForm').addEventListener('submit', function(e) {
        const name = document.querySelector('input[name="name"]').value.trim();
        const phone = document.querySelector('input[name="phone"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        
        if (!name) {
            e.preventDefault();
            showToast('Customer name is required.', 'warning');
            return false;
        }
        
        if (!phone && !email) {
            e.preventDefault();
            showToast('Please provide either a phone number or email address for contact.', 'warning');
            return false;
        }
    });

    // Edit customer function
    function editCustomer(customerId) {
        window.location.href = `/customers/${customerId}/edit`;
    }

    // View customer function
    function viewCustomer(customerId) {
        window.location.href = `/customers/${customerId}`;
    }

    // Send message function
    function sendMessage(email) {
        if (email) {
            window.location.href = `mailto:${email}`;
        } else {
            showToast('No email address available for this customer.', 'warning');
        }
    }

    // Delete customer function
    function deleteCustomer(customerId) {
        showConfirm(
            'Are you sure you want to delete this customer? This action cannot be undone.',
            function() {
                document.getElementById('delete-form-' + customerId).submit();
            },
            'Delete Customer'
        );
    }
    
    // Customer search functionality
    document.getElementById('customerSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const customers = document.querySelectorAll('tbody tr:not(.no-results)');
        
        let visibleCount = 0;
        
        customers.forEach(row => {
            const customerName = row.querySelector('td:first-child .fw-bold')?.textContent.toLowerCase() || '';
            const customerEmail = row.querySelector('td:nth-child(3) a')?.textContent.toLowerCase() || '';
            const customerPhone = row.querySelector('td:nth-child(2) a')?.textContent.toLowerCase() || '';
            
            if (searchTerm === '' || 
                customerName.includes(searchTerm) || 
                customerEmail.includes(searchTerm) || 
                customerPhone.includes(searchTerm)) {
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
                    <p class="text-muted mb-0">No customers found matching "${searchTerm}"</p>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    });
</script>
@endpush
@endsection