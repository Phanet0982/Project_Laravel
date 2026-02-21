<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('created_at', 'desc')->paginate(15);
        $totalCategories = Category::count();
        $categoriesWithProducts = Category::has('products')->count();
        $emptyCategoriesCount = Category::doesntHave('products')->count();

        return view('categories.index', compact('categories', 'totalCategories', 'categoriesWithProducts', 'emptyCategoriesCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
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

        return redirect()->route('categories.index')
                        ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with(['products' => function ($query) {
            $query->orderBy('name');
        }])->findOrFail($id);

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
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
        $category = Category::findOrFail($id);

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

        return redirect()->route('categories.index')
                        ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if category has associated products
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                            ->with('error', 'Cannot delete category. It has ' . $category->products()->count() . ' associated products.');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Category deleted successfully.');
    }
}
