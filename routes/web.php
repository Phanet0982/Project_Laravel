<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/amazon', function () {
        $products = \App\Models\Product::with(['category', 'supplier'])->get();
        $customers = \App\Models\Customer::all();
        $categories = \App\Models\Category::all();
        
        return view('pos.amazon', compact('products', 'customers', 'categories'));
    })->name('pos.amazon');
    Route::post('/pos/process-sale', [POSController::class, 'processSale'])->name('pos.process-sale');
    
    // Receipt Route
    Route::get('/pos/receipt/{saleId}', function ($saleId) {
        $sale = \App\Models\Sale::with(['saleItems.product', 'customer', 'user'])->find($saleId);
        if (!$sale) {
            abort(404, 'Sale not found');
        }
        return view('pos.receipt', compact('sale'));
    })->name('pos.receipt');
    
    // QR Payment Routes
    Route::post('/pos/generate-qr', [POSController::class, 'generateQrCode'])->name('pos.generate-qr');
    Route::post('/pos/check-payment', [POSController::class, 'checkPaymentStatus'])->name('pos.check-payment');
    Route::post('/pos/process-qr-payment', [POSController::class, 'processQrPayment'])->name('pos.process-qr-payment');
    
    // Test route without auth
    Route::post('/test/generate-qr', [POSController::class, 'generateQrCode']);
    
    // Stock Backup Routes
    Route::prefix('stock-backup')->group(function () {
        Route::post('/create', [ReportsController::class, 'createStockBackup'])->name('stock-backup.create');
        Route::get('/list', [ReportsController::class, 'listStockBackups'])->name('stock-backup.list');
        Route::post('/restore/{id}', [ReportsController::class, 'restoreStockBackup'])->name('stock-backup.restore');
        Route::delete('/delete/{id}', [ReportsController::class, 'deleteStockBackup'])->name('stock-backup.delete');
    });
    
    // Customer QR Payment Page
    Route::get('/pay/{payment_ref?}', function ($payment_ref = null) {
        return view('pos.qr-payment', compact('payment_ref'));
    })->name('pos.qr-payment');
    
    // Products
    Route::resource('products', ProductController::class);
    
    // Customers
    Route::resource('customers', CustomerController::class);
    
    // Suppliers
    Route::resource('suppliers', SupplierController::class);
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/employees', [ReportsController::class, 'employees'])->name('reports.employees');
    Route::get('/reports/transactions', [ReportsController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/financial', [ReportsController::class, 'financial'])->name('reports.financial');
    
    // Report API endpoints
    Route::get('/reports/chart-data', [ReportsController::class, 'getChartData'])->name('reports.chart-data');
    Route::post('/reports/{type}/export', [ReportsController::class, 'exportReport'])->name('reports.export');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/business', [SettingsController::class, 'updateBusinessSettings'])->name('settings.business.update');
    Route::post('/settings/preferences', [SettingsController::class, 'updateSystemPreferences'])->name('settings.preferences.update');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Suppliers - handled by resource route above
    
    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/check-in', [EmployeeController::class, 'checkIn'])->name('employees.check-in');
    Route::post('/employees/check-out', [EmployeeController::class, 'checkOut'])->name('employees.check-out');
    
    // Promotions
    Route::resource('promotions', PromotionsController::class);
    Route::post('/promotions/{promotion}/toggle-status', [PromotionsController::class, 'toggleStatus'])->name('promotions.toggleStatus');
    Route::put('/promotions/{promotion}/update-products', [PromotionsController::class, 'updateProducts'])->name('promotions.updateProducts');
});