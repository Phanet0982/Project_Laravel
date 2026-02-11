@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-user-tie me-3"></i>Employee Details</h1>
            <p class="text-muted mb-0">View employee information and attendance history</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Employee
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Employees
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>Employee Profile
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="bg-{{ $employee->role_badge_color }} rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                    <i class="fas {{ $employee->role_icon }} fa-3x text-white"></i>
                </div>
                <h4 class="mb-2">{{ $employee->name }}</h4>
                <p class="text-muted mb-3">@{{ $employee->username }}</p>
                
                <span class="badge bg-{{ $employee->role_badge_color }} fs-6 px-4 py-2">
                    <i class="fas {{ $employee->role_icon }} me-2"></i>{{ ucfirst($employee->role) }}
                </span>
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
                    <label class="form-label text-muted">Total Attendance Days</label>
                    <h4 class="mb-0">{{ $employee->attendances->count() }}</h4>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Status</label>
                    <p class="mb-0">
                        @if($employee->is_active)
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-circle me-2"></i>Active
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-2">
                                <i class="fas fa-circle me-2"></i>Inactive
                            </span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="form-label text-muted">Joined On</label>
                    <p class="fw-bold mb-0">{{ $employee->created_at->format('M d, Y') }}</p>
                    <small class="text-muted">{{ $employee->created_at->diffForHumans() }}</small>
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
                        <p class="fw-bold fs-5">{{ $employee->name }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Username</label>
                        <p class="fw-bold">@{{ $employee->username }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email Address</label>
                        <p class="fw-bold">
                            @if($employee->email)
                                <a href="mailto:{{ $employee->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-2"></i>{{ $employee->email }}
                                </a>
                            @else
                                <span class="text-muted">Not Provided</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Phone Number</label>
                        <p class="fw-bold">
                            @if($employee->phone)
                                <a href="tel:{{ $employee->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-2"></i>{{ $employee->phone }}
                                </a>
                            @else
                                <span class="text-muted">Not Provided</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-12"><hr></div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Last Login</label>
                        <p class="fw-bold">
                            @if($employee->last_login)
                                <i class="fas fa-clock me-2"></i>{{ $employee->last_login->format('M d, Y h:i A') }}
                            @else
                                <span class="text-muted">Never logged in</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Account Created</label>
                        <p class="fw-bold">
                            <i class="fas fa-calendar me-2"></i>{{ $employee->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Attendance
                </h5>
            </div>
            <div class="card-body">
                @if($employee->attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Hours Worked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->attendances->take(10) as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                        <td>
                                            @if($attendance->check_in)
                                                <span class="text-success">{{ $attendance->check_in->format('h:i A') }}</span>
                                            @else
                                                <span class="text-muted">--:--</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->check_out)
                                                <span class="text-danger">{{ $attendance->check_out->format('h:i A') }}</span>
                                            @else
                                                <span class="text-muted">--:--</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->check_in && $attendance->check_out)
                                                @php
                                                    $hours = $attendance->check_out->diffInHours($attendance->check_in);
                                                    $minutes = $attendance->check_out->diffInMinutes($attendance->check_in) % 60;
                                                @endphp
                                                <span class="fw-bold">{{ $hours }}h {{ $minutes }}m</span>
                                            @elseif($attendance->check_in)
                                                <span class="text-info">Active</span>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Attendance Records</h5>
                        <p class="text-muted">This employee hasn't recorded any attendance yet</p>
                    </div>
                @endif
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
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Employee
                    </a>
                    @if($employee->email)
                    <a href="mailto:{{ $employee->email }}" class="btn btn-success">
                        <i class="fas fa-envelope me-2"></i>Send Email
                    </a>
                    @endif
                    <button type="button" class="btn btn-danger" onclick="deleteEmployee({{ $employee->id }})">
                        <i class="fas fa-trash me-2"></i>Delete Employee
                    </button>
                    <form id="delete-form-{{ $employee->id }}" action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: none;">
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
    function deleteEmployee(employeeId) {
        showConfirm(
            'Are you sure you want to delete this employee? This action cannot be undone.',
            function() {
                document.getElementById('delete-form-' + employeeId).submit();
            },
            'Delete Employee'
        );
    }
</script>
@endpush
@endsection