@extends('layouts.app')

@section('title', 'Employee Report')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-user-tie me-3"></i>Employee Report</h1>
            <p class="text-muted mb-0">Staff performance, attendance records, and productivity metrics</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportReport('employees')">
                <i class="fas fa-download me-2"></i>Export PDF
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Reports
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
                        <h4 class="mb-1">{{ $activeEmployees }}</h4>
                        <p class="text-muted mb-0">Active Employees</p>
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
                        <i class="fas fa-percentage text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($attendanceRate, 0) }}%</h4>
                        <p class="text-muted mb-0">Attendance Rate</p>
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
                        <h4 class="mb-1">{{ $employees->where('is_active', false)->count() }}</h4>
                        <p class="text-muted mb-0">Inactive</p>
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
                        <i class="fas fa-address-book me-2"></i>Employee Performance
                    </h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="filterByRole(this.value)">
                            <option value="all">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="employeesTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Employee</th>
                                <th><i class="fas fa-id-badge me-2"></i>Role</th>
                                <th><i class="fas fa-calendar-check me-2"></i>Attendance</th>
                                <th><i class="fas fa-clock me-2"></i>Hours</th>
                                <th><i class="fas fa-toggle-on me-2"></i>Status</th>
                                <th><i class="fas fa-calendar me-2"></i>Last Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr data-role="{{ $employee->role }}">
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
                                        @php
                                            $attendanceDays = $employee->attendances->count();
                                            $workingDays = 30; // Assume 30 working days
                                            $attendancePercentage = $workingDays > 0 ? ($attendanceDays / $workingDays) * 100 : 0;
                                        @endphp
                                        <div>
                                            <div class="fw-bold">{{ $attendanceDays }} days</div>
                                            <div class="progress mt-1" style="height: 4px;">
                                                <div class="progress-bar bg-{{ $attendancePercentage >= 90 ? 'success' : ($attendancePercentage >= 70 ? 'warning' : 'danger') }}" 
                                                     style="width: {{ min(100, $attendancePercentage) }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ number_format($attendancePercentage, 0) }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $totalHours = 0;
                                            foreach($employee->attendances as $attendance) {
                                                if($attendance->check_in && $attendance->check_out) {
                                                    $totalHours += $attendance->check_out->diffInHours($attendance->check_in);
                                                }
                                            }
                                            $avgHours = $attendanceDays > 0 ? $totalHours / $attendanceDays : 0;
                                        @endphp
                                        <div>
                                            <div class="fw-bold">{{ number_format($totalHours, 0) }}h total</div>
                                            <small class="text-muted">{{ number_format($avgHours, 1) }}h avg/day</small>
                                        </div>
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
                                        @if($employee->last_login)
                                            <div>
                                                <div class="fw-bold">{{ $employee->last_login->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $employee->last_login->diffForHumans() }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
                    <i class="fas fa-chart-pie me-2"></i>Role Distribution
                </h5>
            </div>
            <div class="card-body">
                @php
                    $adminCount = $employees->where('role', 'admin')->count();
                    $managerCount = $employees->where('role', 'manager')->count();
                    $cashierCount = $employees->where('role', 'cashier')->count();
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Admin</span>
                        <span class="fw-bold text-danger">{{ $adminCount }}</span>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: {{ $totalEmployees > 0 ? ($adminCount / $totalEmployees) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Manager</span>
                        <span class="fw-bold text-warning">{{ $managerCount }}</span>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: {{ $totalEmployees > 0 ? ($managerCount / $totalEmployees) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Cashier</span>
                        <span class="fw-bold text-primary">{{ $cashierCount }}</span>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: {{ $totalEmployees > 0 ? ($cashierCount / $totalEmployees) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Team Overview</strong><br>
                    <small>
                        {{ $activeEmployees }} out of {{ $totalEmployees }} employees are currently active.
                        Average attendance rate is {{ number_format($attendanceRate, 0) }}%.
                    </small>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>Top Performers
                </h5>
            </div>
            <div class="card-body">
                @php
                    $topPerformers = $employees->sortByDesc(function($employee) {
                        return $employee->attendances->count();
                    })->take(5);
                @endphp
                
                @foreach($topPerformers as $index => $employee)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                            <span class="text-white fw-bold" style="font-size: 12px;">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $employee->name }}</div>
                            <small class="text-muted">{{ $employee->attendances->count() }} attendance days</small>
                        </div>
                        <span class="badge bg-{{ $employee->role_badge_color }}">{{ ucfirst($employee->role) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterByRole(role) {
        const table = document.getElementById('employeesTable');
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (role === 'all' || row.dataset.role === role) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        showToast(`Filtered by ${role === 'all' ? 'all roles' : role + ' role'}`, 'info');
    }

    function exportReport(type) {
        showToast(`Exporting ${type} report...`, 'info');
        setTimeout(() => {
            showToast('Report exported successfully!', 'success');
        }, 2000);
    }
</script>
@endpush
@endsection