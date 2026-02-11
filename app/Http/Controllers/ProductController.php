<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);
        
        // Apply filters
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $products = $query->paginate(15)->appends($request->except('page'));
        $categories = Category::all();
        $suppliers = Supplier::all();
        
        // Calculate stock counts
        $totalProducts = Product::count();
        $inStock = Product::where('qty', '>', 10)->count();
        $lowStock = Product::whereBetween('qty', [1, 10])->count();
        $outOfStock = Product::where('qty', 0)->count();
        
        return view('products.index', compact('products', 'categories', 'suppliers', 'totalProducts', 'inStock', 'lowStock', 'outOfStock'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'barcode' => 'nullable|string|unique:products,barcode',
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
            // Auto-sync storage to public directory
            $this->syncStorage();
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
            // Auto-sync storage to public directory
            $this->syncStorage();
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // Check if product has been sold
        if ($product->saleItems()->exists()) {
            // Soft delete - mark as inactive instead of permanent deletion
            $product->update(['is_active' => false]);
            return redirect()->route('products.index')->with('success', 'Product deactivated successfully. It has existing sales records and cannot be permanently deleted.');
        }
        
        // If no sales records, permanently delete
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
    
    /**
     * Sync storage files to public directory
     */
    private function syncStorage()
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');
        
        // Create destination directory if it doesn't exist
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }
        
        // Copy all files recursively
        File::copyDirectory($source, $destination);
    }
}