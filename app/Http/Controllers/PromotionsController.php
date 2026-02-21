<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;

class PromotionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = Promotion::with('products')->orderBy('created_at', 'desc');
        
        // Apply filter based on request
        switch($filter) {
            case 'active':
                $query = $query->active();
                break;
            case 'expired':
                $query = $query->expired();
                break;
            case 'upcoming':
                $query = $query->upcoming();
                break;
            case 'all':
            default:
                // No additional filter for 'all'
                break;
        }
        
        $promotions = $query->paginate(15);

        // Calculate statistics
        $totalPromotions = Promotion::count();
        $activePromotions = Promotion::active()->count();
        $expiredPromotions = Promotion::expired()->count();
        $upcomingPromotions = Promotion::upcoming()->count();

        return view('promotions.index', compact('promotions', 'totalPromotions', 'activePromotions', 'expiredPromotions', 'upcomingPromotions', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        $categories = Category::all();
        return view('promotions.create', compact('products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean',
            'selected_products' => 'array',
            'selected_products.*' => 'exists:products,id',
        ]);

        $promotion = Promotion::create([
            'name' => $request->name,
            'discount_percent' => $request->discount_percent,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active ?? true,
        ]);

        // Attach selected products with discount amount
        if ($request->has('selected_products') && !empty($request->selected_products)) {
            $productIds = $request->selected_products;
            $syncData = [];
            
            foreach ($productIds as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $discountAmount = $product->sale_price * $request->discount_percent / 100;
                    $syncData[$productId] = ['discount_amount' => $discountAmount];
                }
            }
            
            if (!empty($syncData)) {
                $promotion->products()->sync($syncData);
            }
        }

        return redirect()->route('promotions.index')
                        ->with('success', 'Promotion created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promotion = Promotion::with('products')->findOrFail($id);
        $products = Product::where('is_active', true)->get();
        $selectedProducts = $promotion->products()->pluck('products.id')->toArray();
        return view('promotions.show', compact('promotion', 'products', 'selectedProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotion = Promotion::with('products')->findOrFail($id);
        $products = Product::where('is_active', true)->get();
        $selectedProducts = $promotion->products()->pluck('products.id')->toArray();
        $categories = Category::all();
        return view('promotions.edit', compact('promotion', 'products', 'selectedProducts', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean',
        ]);

        $oldDiscount = $promotion->discount_percent;

        $promotion->update([
            'name' => $request->name,
            'discount_percent' => $request->discount_percent,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active ?? true,
        ]);

        // If discount percent changed, update pivot discount_amount for attached products
        if ($oldDiscount != $promotion->discount_percent && $promotion->products()->count() > 0) {
            $syncData = [];
            foreach ($promotion->products as $product) {
                $discountAmount = $product->sale_price * $promotion->discount_percent / 100;
                $syncData[$product->id] = ['discount_amount' => $discountAmount];
            }
            // Update pivot values without detaching existing relations
            $promotion->products()->syncWithoutDetaching($syncData);
        }

        return redirect()->route('promotions.index')
                        ->with('success', 'Promotion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('promotions.index')
                        ->with('success', 'Promotion deleted successfully.');
    }

    /**
     * Toggle promotion active status
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->active = !$promotion->active;
        $promotion->save();

        $status = $promotion->active ? 'activated' : 'deactivated';
        return redirect()->route('promotions.index')
                        ->with('success', "Promotion {$status} successfully.");
    }

    /**
     * Update products for the specified promotion
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProducts(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);
        
        $request->validate([
            'selected_products' => 'required|array|min:1',
            'selected_products.*' => 'exists:products,id',
        ]);
        
        // Sync products with the promotion via pivot table
        $promotion->products()->sync($request->selected_products);
        
        return redirect()->route('promotions.show', $promotion->id)
                        ->with('success', 'Product selection saved successfully! ' . count($request->selected_products) . ' products will receive the ' . $promotion->formatted_discount . ' discount.');
    }
}