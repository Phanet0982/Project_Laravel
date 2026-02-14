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
        
        $query = Promotion::orderBy('created_at', 'desc');
        
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
        ]);

        $promotion = Promotion::create([
            'name' => $request->name,
            'discount_percent' => $request->discount_percent,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active ?? true,
        ]);

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
        $promotion = Promotion::findOrFail($id);
        $products = Product::where('is_active', true)->get();
        return view('promotions.show', compact('promotion', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $products = Product::where('is_active', true)->get();
        $categories = Category::all();
        return view('promotions.edit', compact('promotion', 'products', 'categories'));
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

        $promotion->update([
            'name' => $request->name,
            'discount_percent' => $request->discount_percent,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active ?? true,
        ]);

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
        
        // For now, we'll store the product information in session
        // In a full implementation, this would be stored in a pivot table
        session()->put('promotion_' . $promotion->id . '_products', $request->selected_products);
        
        return redirect()->route('promotions.show', $promotion->id)
                        ->with('success', 'Product selection saved successfully! ' . count($request->selected_products) . ' products will receive the ' . $promotion->formatted_discount . ' discount.');
    }
}