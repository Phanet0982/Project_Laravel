@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-user-tie me-3"></i>Employee Management</h1>
            <p class="text-muted mb-0">Manage staff, track attendance, and monitor performance metrics</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#employeeModal">
                <i class="fas fa-user-plus me-2"></i>Add Employee
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
                        <h4 class="mb-1">{{ $totalEmployees }}</h4>
                        <p class="text-muted mb-0">Total Employees</p>
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
                        <h4 class="mb-1">{{ $activeToday }}</h4>
                        <p class="text-muted mb-0">Active Today</p>
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
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($avgHours, 1) }}h</h4>
                        <p class="text-muted mb-0">Avg. Hours</p>
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
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($attendanceRate, 0) }}%</h4>
                        <p class="text-muted mb-0">Attendance Rate</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-address-book me-2"></i>Employee Directory
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Search employees..." id="employeeSearch">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <select class="form-select form-select-sm" id="roleFilter" style="width: auto;">
                            <option value="all" {{ !request()->has('role') || request()->role === 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="admin" {{ request()->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ request()->role === 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="cashier" {{ request()->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Employee</th>
                                <th><i class="fas fa-id-badge me-2"></i>Role</th>
                                <th><i class="fas fa-toggle-on me-2"></i>Status</th>
                                <th><i class="fas fa-calendar me-2"></i>Last Active</th>
                                <th><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($employees) && $employees->count() > 0)
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-{{ $employee->role_badge_color }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas {{ $employee->role_icon }} text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $employee->name }}</div>
                                                    <small class="text-muted">{{ $employee->username }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $employee->role_badge_color }}">
                                                <i class="fas {{ $employee->role_icon }} me-1"></i>{{ ucfirst($employee->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($employee->is_active)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">
                                                    @if($employee->last_login)
                                                        {{ $employee->last_login->format('M d, Y') }}
                                                    @else
                                                        Never
                                                    @endif
                                                </div>
                                                <small class="text-muted">
                                                    @if($employee->last_login)
                                                        {{ $employee->last_login->diffForHumans() }}
                                                    @else
                                                        No login yet
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="editEmployee({{ $employee->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" title="View Details" onclick="viewEmployee({{ $employee->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteEmployee({{ $employee->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $employee->id }}" action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-user-friends fa-3x mb-3"></i>
                                            <h5>No Employees Found</h5>
                                            <p>Start by adding your first employee to the system</p>
                                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#employeeModal">
                                                <i class="fas fa-user-plus me-2"></i>Add Employee
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Attendance Tracker
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-clock fa-2x text-white"></i>
                    </div>
                    <h4 id="currentTime">--:--:--</h4>
                    <p class="text-muted">Current Time</p>
                </div>
                
                <div class="d-grid gap-3">
                    <button class="btn btn-success btn-lg" onclick="checkIn()">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-in-alt fa-lg me-3"></i>
                            <div class="text-start">
                                <div class="fw-bold">Check In</div>
                                <small class="opacity-75">Start your shift</small>
                            </div>
                        </div>
                    </button>
                    
                    <button class="btn btn-warning btn-lg" onclick="checkOut()">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-out-alt fa-lg me-3"></i>
                            <div class="text-start">
                                <div class="fw-bold">Check Out</div>
                                <small class="opacity-75">End your shift</small>
                            </div>
                        </div>
                    </button>
                </div>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-success mb-1" id="todayCheckIn">--:--</h5>
                            <small class="text-muted">Check In</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-muted mb-1" id="todayCheckOut">--:--</h5>
                        <small class="text-muted">Check Out</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Recent Attendance
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Today</td>
                                <td class="text-success" id="recentTodayIn">09:00 AM</td>
                                <td class="text-muted" id="recentTodayOut">--:--</td>
                                <td class="text-info">Active</td>
                            </tr>
                            <tr>
                                <td>Yesterday</td>
                                <td>09:15 AM</td>
                                <td>06:00 PM</td>
                                <td>8h 45m</td>
                            </tr>
                            <tr>
                                <td>Jan 16</td>
                                <td>09:00 AM</td>
                                <td>05:30 PM</td>
                                <td>8h 30m</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">
                    <i class="fas fa-user-plus"></i>
                    Add New Employee
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm" action="{{ route('employees.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="text-center mb-4">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-user-tie fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name
                            </label>
                            <input type="text" class="form-control" name="name" placeholder="Enter employee's full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-at"></i>
                                Username
                            </label>
                            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
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
                                <i class="fas fa-phone"></i>
                                Phone Number
                            </label>
                            <input type="tel" class="form-control" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-id-badge"></i>
                                Role
                            </label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="cashier">Cashier</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Role filter functionality
    document.getElementById('roleFilter').addEventListener('change', function() {
        const selectedRole = this.value;
        const url = new URL(window.location);
        
        if (selectedRole === 'all') {
            url.searchParams.delete('role');
        } else {
            url.searchParams.set('role', selectedRole);
        }
        
        window.location.href = url.toString();
    });
    
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        document.getElementById('currentTime').textContent = timeString;
    }
    
    // Update time every second
    setInterval(updateTime, 1000);
    updateTime(); // Initial call
    
    // Update attendance tracker
    function updateAttendanceTracker() {
        // This would typically make an AJAX call to get today's attendance data
        // For now, we'll simulate with placeholder data
        document.getElementById('todayCheckIn').textContent = '09:00 AM';
        document.getElementById('todayCheckOut').textContent = '--:--';
        
        // Update recent attendance table
        document.getElementById('recentTodayIn').textContent = '09:00 AM';
        document.getElementById('recentTodayOut').textContent = '--:--';
    }
    
    // Update attendance tracker on page load
    updateAttendanceTracker();
    
    // Employee search functionality
    document.getElementById('employeeSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const employees = document.querySelectorAll('tbody tr:not(.no-results)');
        
        let visibleCount = 0;
        
        employees.forEach(row => {
            const employeeName = row.querySelector('td:first-child .fw-bold')?.textContent.toLowerCase() || '';
            const employeeUsername = row.querySelector('td:first-child small')?.textContent.toLowerCase() || '';
            const employeeRole = row.querySelector('td:nth-child(2) .badge')?.textContent.toLowerCase() || '';
            
            if (searchTerm === '' || 
                employeeName.includes(searchTerm) || 
                employeeUsername.includes(searchTerm) || 
                employeeRole.includes(searchTerm)) {
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
                <td colspan="5" class="text-center py-4">
                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No employees found matching "${searchTerm}"</p>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    });

    // Username generation from name
    document.querySelector('input[name="name"]').addEventListener('input', function(e) {
        const name = e.target.value.toLowerCase().replace(/\s+/g, '');
        const usernameField = document.querySelector('input[name="username"]');
        if (name && !usernameField.value) {
            usernameField.value = name;
        }
    });

    // Form validation
    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        const name = document.querySelector('input[name="name"]').value.trim();
        const username = document.querySelector('input[name="username"]').value.trim();
        const password = document.querySelector('input[name="password"]').value;
        const role = document.querySelector('select[name="role"]').value;
        
        if (!name || !username || !password || !role) {
            e.preventDefault();
            showToast('Please fill in all required fields.', 'warning');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            showToast('Password must be at least 6 characters long.', 'warning');
            return false;
        }
    });

    // Edit employee function
    function editEmployee(employeeId) {
        window.location.href = `/employees/${employeeId}/edit`;
    }

    // View employee function
    function viewEmployee(employeeId) {
        window.location.href = `/employees/${employeeId}`;
    }

    // Delete employee function
    function deleteEmployee(employeeId) {
        showConfirm(
            'Are you sure you want to delete this employee? This action cannot be undone.',
            function() {
                document.getElementById('delete-form-' + employeeId).submit();
            },
            'Delete Employee'
        );
    }
    
    // Check In function
    function checkIn() {
        showConfirm(
            'Are you sure you want to check in?',
            function() {
                // Get current logged in user ID (you might need to adjust this based on your auth system)
                const userId = {{ auth()->user()->id ?? 1 }};
                
                fetch('{{ route('employees.check-in') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        employee_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        document.getElementById('todayCheckIn').textContent = data.check_in;
                        document.getElementById('todayCheckIn').classList.add('text-success');
                        // Update recent attendance
                        document.getElementById('recentTodayIn').textContent = data.check_in;
                        document.getElementById('recentTodayIn').classList.remove('text-muted');
                        document.getElementById('recentTodayIn').classList.add('text-success');
                        document.getElementById('recentTodayOut').textContent = '--:--';
                        document.getElementById('recentTodayOut').classList.add('text-muted');
                    } else {
                        showToast(data.error || 'Check in failed', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Network error occurred', 'error');
                });
            },
            'Check In'
        );
    }
    
    // Check Out function
    function checkOut() {
        showConfirm(
            'Are you sure you want to check out?',
            function() {
                // Get current logged in user ID (you might need to adjust this based on your auth system)
                const userId = {{ auth()->user()->id ?? 1 }};
                
                fetch('{{ route('employees.check-out') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        employee_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        document.getElementById('todayCheckOut').textContent = data.check_out;
                        document.getElementById('todayCheckOut').classList.remove('text-muted');
                        document.getElementById('todayCheckOut').classList.add('text-warning');
                        // Update recent attendance
                        document.getElementById('recentTodayOut').textContent = data.check_out;
                        document.getElementById('recentTodayOut').classList.remove('text-muted');
                        document.getElementById('recentTodayOut').classList.add('text-warning');
                        // Calculate hours worked
                        const checkInTime = document.getElementById('recentTodayIn').textContent;
                        if (checkInTime !== '--:--') {
                            // Simple hour calculation (you might want to improve this)
                            document.querySelector('tbody tr:first-child td:last-child').textContent = 'Completed';
                            document.querySelector('tbody tr:first-child td:last-child').classList.add('text-success');
                        }
                    } else {
                        showToast(data.error || 'Check out failed', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Network error occurred', 'error');
                });
            },
            'Check Out'
        );
    }
    
    // Update clock every second
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        document.getElementById('currentTime').textContent = timeString;
    }
    
    // Initialize clock
    setInterval(updateTime, 1000);
    updateTime(); // Initial call
</script>
@endpush
@endsection