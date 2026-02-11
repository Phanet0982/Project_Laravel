<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(15);
        
        // Calculate customer metrics
        $totalCustomers = Customer::count();
        $vipMembers = Customer::where('member_type', 'vip')->count();
        $regularMembers = Customer::where('member_type', 'regular')->count();
        $newThisMonth = Customer::whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->count();
        
        return view('customers.index', compact('customers', 'totalCustomers', 'vipMembers', 'regularMembers', 'newThisMonth'));
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'member_type' => 'required|in:regular,vip'
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'member_type' => 'required|in:regular,vip'
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }
}