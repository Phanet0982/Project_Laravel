<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('total_amount') ?? 0;
        $totalProducts = Product::count();
        $lowStock = Product::where('qty', '<=', 10)->count();
        $totalCustomers = Customer::count();
        
        $recentSales = Sale::with(['customer'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'todaySales', 
            'totalProducts', 
            'lowStock', 
            'totalCustomers', 
            'recentSales'
        ));
    }
}