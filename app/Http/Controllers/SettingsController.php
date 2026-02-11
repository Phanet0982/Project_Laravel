<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\BusinessSetting;
use App\Models\SystemPreference;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        
        // Get business settings from database (fallback to config)
        $businessSetting = BusinessSetting::first();
        if ($businessSetting) {
            $businessSettings = [
                'business_name' => $businessSetting->business_name,
                'business_phone' => $businessSetting->business_phone,
                'business_email' => $businessSetting->business_email,
                'tax_rate' => $businessSetting->tax_rate,
                'business_address' => $businessSetting->business_address,
                'currency' => $businessSetting->currency,
                'timezone' => $businessSetting->timezone,
            ];
        } else {
            $businessSettings = [
                'business_name' => config('app.name', 'Sale Management System'),
                'business_phone' => config('app.phone', ''),
                'business_email' => config('app.email', ''),
                'tax_rate' => config('app.tax_rate', 10),
                'business_address' => config('app.address', ''),
                'currency' => config('app.currency', 'USD'),
                'timezone' => config('app.timezone', 'UTC'),
            ];
        }
        
        // Get system preferences from database (fallback to config)
        $systemPreference = SystemPreference::first();
        if ($systemPreference) {
            $systemPreferences = [
                'low_stock_alerts' => $systemPreference->low_stock_alerts,
                'email_notifications' => $systemPreference->email_notifications,
                'auto_backup' => $systemPreference->auto_backup,
                'receipt_printing' => $systemPreference->receipt_printing,
                'low_stock_threshold' => $systemPreference->low_stock_threshold,
                'receipt_footer_text' => $systemPreference->receipt_footer_text,
            ];
        } else {
            $systemPreferences = [
                'low_stock_alerts' => config('app.low_stock_alerts', true),
                'email_notifications' => config('app.email_notifications', true),
                'auto_backup' => config('app.auto_backup', false),
                'receipt_printing' => config('app.receipt_printing', true),
                'low_stock_threshold' => config('app.low_stock_threshold', 10),
                'receipt_footer_text' => config('app.receipt_footer_text', 'Thank you for your business!'),
            ];
        }
        
        return view('settings.index', compact('categories', 'suppliers', 'businessSettings', 'systemPreferences'));
    }
    
    public function updateBusinessSettings(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_email' => 'nullable|email|max:255',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'business_address' => 'nullable|string|max:500',
            'currency' => 'required|string|max:3',
            'timezone' => 'required|string|max:50',
        ]);
        
        // Get existing record or create new one
        $businessSetting = BusinessSetting::first();
        
        if ($businessSetting) {
            $businessSetting->update($request->all());
        } else {
            $businessSetting = BusinessSetting::create($request->all());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Business settings updated successfully!'
        ]);
    }
    
    public function updateSystemPreferences(Request $request)
    {
        $request->validate([
            'low_stock_alerts' => 'boolean',
            'email_notifications' => 'boolean',
            'auto_backup' => 'boolean',
            'receipt_printing' => 'boolean',
            'low_stock_threshold' => 'required|integer|min:1',
            'receipt_footer_text' => 'nullable|string|max:500',
        ]);
        
        // Get existing record or create new one
        $systemPreference = SystemPreference::first();
        
        if ($systemPreference) {
            $systemPreference->update($request->all());
        } else {
            $systemPreference = SystemPreference::create($request->all());
        }
        
        return response()->json([
            'success' => true,
            'message' => 'System preferences updated successfully!'
        ]);
    }
    
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
        ]);
        
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?: 'primary',
            'icon' => $request->icon ?: 'folder',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!',
            'category' => $category
        ]);
    }
    
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
        ]);
        
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?: 'primary',
            'icon' => $request->icon ?: 'folder',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category
        ]);
    }
    
    public function destroyCategory(Category $category)
    {
        // Check if category has associated products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category. It has associated products.'
            ], 422);
        }
        
        $category->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
    }
    
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
        ]);
        
        $supplier = Supplier::create([
            'name' => $request->company_name,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'address' => $request->address,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Supplier added successfully!',
            'supplier' => $supplier
        ]);
    }
    
    public function updateSupplier(Request $request, Supplier $supplier)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
        ]);
        
        $supplier->update([
            'name' => $request->company_name,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'address' => $request->address,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully!',
            'supplier' => $supplier
        ]);
    }
    
    public function destroySupplier(Supplier $supplier)
    {
        // Check if supplier has associated products
        if ($supplier->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete supplier. It has associated products.'
            ], 422);
        }
        
        $supplier->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully!'
        ]);
    }
    
    public function showCategory(Category $category)
    {
        try {
            return response()->json([
                'success' => true,
                'category' => $category->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving category data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function showSupplier(Supplier $supplier)
    {
        try {
            return response()->json([
                'success' => true,
                'supplier' => $supplier->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving supplier data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}