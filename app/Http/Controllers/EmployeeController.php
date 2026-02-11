<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // Apply role filtering if specified
        $query = Employee::query();
        
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        $employees = $query->paginate(15)->appends($request->except('page'));
        
        // Calculate statistics
        $totalEmployees = Employee::count();
        $activeToday = Attendance::whereDate('date', today())
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->count();
        
        // Get average hours for completed shifts today
        $completedShifts = Attendance::whereDate('date', today())
            ->whereNotNull('check_out')
            ->get();
            
        $avgHours = 0;
        if ($completedShifts->count() > 0) {
            $totalMinutes = $completedShifts->sum(function($attendance) {
                return $attendance->check_out->diffInMinutes($attendance->check_in);
            });
            $avgHours = $totalMinutes / $completedShifts->count() / 60;
        }
            
        $attendanceRate = $totalEmployees > 0 
            ? (Attendance::whereDate('date', today())->count() / $totalEmployees) * 100 
            : 0;

        return view('employees.index', compact(
            'employees', 
            'totalEmployees', 
            'activeToday', 
            'avgHours', 
            'attendanceRate'
        ));
    }

    public function show(Employee $employee)
    {
        $employee->load('attendances');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:employees,username',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,cashier',
            'password' => 'required|string|min:6'
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee created successfully');
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:employees,username,' . $employee->id,
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,cashier',
            'password' => 'nullable|string|min:6'
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }

    public function checkIn(Request $request)
    {
        $employee = Employee::find($request->employee_id);
        
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $today = today();
        $attendance = Attendance::firstOrCreate([
            'user_id' => $employee->id,
            'date' => $today
        ]);

        if ($attendance->check_in) {
            return response()->json(['error' => 'Already checked in today'], 400);
        }

        $attendance->update(['check_in' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Checked in successfully',
            'check_in' => $attendance->check_in->format('h:i A')
        ]);
    }

    public function checkOut(Request $request)
    {
        $employee = Employee::find($request->employee_id);
        
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $attendance = Attendance::where('user_id', $employee->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json(['error' => 'Must check in first'], 400);
        }

        if ($attendance->check_out) {
            return response()->json(['error' => 'Already checked out today'], 400);
        }

        $attendance->update(['check_out' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Checked out successfully',
            'check_out' => $attendance->check_out->format('h:i A')
        ]);
    }
}
