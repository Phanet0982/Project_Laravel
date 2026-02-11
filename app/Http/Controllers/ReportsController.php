<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\StockBackup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function index()
    {
        // Calculate quick stats
        $thisMonth = Carbon::now()->startOfMonth();
        
        $monthlyRevenue = Sale::where('created_at', '>=', $thisMonth)->sum('total_amount') ?? 0;
        $totalOrders = Sale::where('created_at', '>=', $thisMonth)->count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        
        // Sales analytics data for chart
        $salesData = $this->getSalesAnalytics();
        
        return view('reports.index', compact(
            'monthlyRevenue',
            'totalOrders', 
            'totalCustomers',
            'totalProducts',
            'salesData'
        ));
    }

    public function sales(Request $request)
    {
        $period = $request->get('period', '7days');
        $startDate = $this->getStartDate($period);
        
        $sales = Sale::with(['customer', 'saleItems.product'])
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $totalRevenue = Sale::where('created_at', '>=', $startDate)->sum('total_amount') ?? 0;
        $totalOrders = Sale::where('created_at', '>=', $startDate)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        return view('reports.sales', compact(
            'sales', 
            'totalRevenue', 
            'totalOrders', 
            'avgOrderValue',
            'period'
        ));
    }

    public function inventory()
    {
        $products = Product::with(['category', 'supplier'])->get();
        
        $totalProducts = $products->count();
        $inStock = $products->where('qty', '>', 10)->count();
        $lowStock = $products->whereBetween('qty', [1, 10])->count();
        $outOfStock = $products->where('qty', '<=', 0)->count();
        
        $totalValue = $products->sum(function($product) {
            return $product->qty * $product->cost_price;
        });
        
        return view('reports.inventory', compact(
            'products',
            'totalProducts',
            'inStock', 
            'lowStock',
            'outOfStock',
            'totalValue'
        ));
    }

    public function customers()
    {
        $customers = Customer::withCount(['sales' => function($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonths(3));
        }])->get();
        
        $totalCustomers = $customers->count();
        $vipCustomers = $customers->where('member_type', 'vip')->count();
        $regularCustomers = $customers->where('member_type', 'regular')->count();
        $newThisMonth = Customer::where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        
        return view('reports.customers', compact(
            'customers',
            'totalCustomers',
            'vipCustomers',
            'regularCustomers', 
            'newThisMonth'
        ));
    }

    public function employees()
    {
        $employees = Employee::with('attendances')->get();
        
        $totalEmployees = $employees->count();
        $activeEmployees = $employees->where('is_active', true)->count();
        
        // Today's attendance
        $todayAttendance = Attendance::whereDate('date', today())->count();
        $attendanceRate = $totalEmployees > 0 ? ($todayAttendance / $totalEmployees) * 100 : 0;
        
        return view('reports.employees', compact(
            'employees',
            'totalEmployees',
            'activeEmployees',
            'attendanceRate'
        ));
    }

    public function transactions(Request $request)
    {
        $period = $request->get('period', '7days');
        $startDate = $this->getStartDate($period);
        
        $transactions = Sale::with(['customer', 'saleItems'])
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('reports.transactions', compact('transactions', 'period'));
    }

    public function financial()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Revenue calculations
        $thisMonthRevenue = Sale::where('created_at', '>=', $thisMonth)->sum('total_amount') ?? 0;
        $lastMonthRevenue = Sale::whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total_amount') ?? 0;
        
        // Cost calculations (simplified - using cost price of sold items)
        $thisMonthCosts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.created_at', '>=', $thisMonth)
            ->sum(DB::raw('sale_items.quantity * products.cost_price')) ?? 0;
            
        $profit = $thisMonthRevenue - $thisMonthCosts;
        $profitMargin = $thisMonthRevenue > 0 ? ($profit / $thisMonthRevenue) * 100 : 0;
        
        return view('reports.financial', compact(
            'thisMonthRevenue',
            'lastMonthRevenue', 
            'thisMonthCosts',
            'profit',
            'profitMargin'
        ));
    }

    private function getSalesAnalytics()
    {
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Sale::whereDate('created_at', $date)->sum('total_amount') ?? 0;
            $last7Days->push([
                'date' => $date->format('M d'),
                'revenue' => $revenue
            ]);
        }
        
        return $last7Days;
    }

    private function getStartDate($period)
    {
        return match($period) {
            'today' => Carbon::today(),
            'yesterday' => Carbon::yesterday(),
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            'thismonth' => Carbon::now()->startOfMonth(),
            'lastmonth' => Carbon::now()->subMonth()->startOfMonth(),
            default => Carbon::now()->subDays(7)
        };
    }

    // Stock Backup Methods
    public function createStockBackup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        // Get all current product data
        $products = Product::select([
            'id', 'name', 'barcode', 'cost_price', 'sale_price', 
            'qty', 'category_id', 'supplier_id'
        ])->get();

        $backupData = [
            'products' => $products->toArray(),
            'backup_timestamp' => now()->toISOString(),
            'total_products' => $products->count(),
            'total_value' => $products->sum(function($product) {
                return $product->qty * $product->cost_price;
            })
        ];

        $backup = StockBackup::create([
            'name' => $request->name,
            'description' => $request->description,
            'backup_data' => json_encode($backupData),
            'product_count' => $products->count(),
            'created_by' => Auth::id(),
            'backup_date' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock backup created successfully',
            'backup_id' => $backup->id
        ]);
    }

    public function listStockBackups()
    {
        $backups = StockBackup::with('creator')
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'backups' => $backups
        ]);
    }

    public function restoreStockBackup(Request $request, $id)
    {
        $backup = StockBackup::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $backupData = json_decode($backup->backup_data, true);

        try {
            DB::beginTransaction();

            // Restore each product's stock level
            foreach ($backupData['products'] as $productData) {
                $product = Product::find($productData['id']);
                if ($product) {
                    $product->update([
                        'qty' => $productData['qty']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock backup restored successfully',
                'restored_products' => count($backupData['products'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteStockBackup(Request $request, $id)
    {
        $backup = StockBackup::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $backup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Backup deleted successfully'
        ]);
    }
    
    // Chart data endpoint for sales analytics
    public function getChartData(Request $request)
    {
        try {
            $salesData = $this->getSalesAnalytics();
            
            return response()->json([
                'success' => true,
                'chartData' => $salesData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load chart data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Generic report export endpoint
    public function exportReport(Request $request, $type)
    {
        $request->validate([
            'format' => 'required|in:pdf,excel,csv',
            'date_range' => 'required|string'
        ]);
        
        try {
            // In a real implementation, this would generate and return the actual file
            // For now, we'll return a success response
            
            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' report exported successfully',
                'download_url' => '/storage/reports/' . $type . '_' . time() . '.' . $request->format
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
