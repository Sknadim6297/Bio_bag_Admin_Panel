<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalCategories = Category::count();
        $categories = Category::paginate(10);
        return view('admin.category.index', compact('categories', 'totalCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_code' => 'nullable|string|max:100',
        ]);

        $category_code = 'CAT' . rand(1000, 9999);

        Category::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'status' => $request->status,
            'category_code' => $category_code,
        ]);

        return response()->json(['success' => true, 'message' => 'Category created successfully.']);
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }

    
    public function search(Request $request)
    {
        $search = $request->search;

        $categories = Category::where('category_name', 'LIKE', "%$search%")
            ->orWhere('category_code', 'LIKE', "%$search%")
            ->get();

        $data = $categories->map(function ($cat) {
            return [
                'id' => $cat->id,
                'category_name' => $cat->category_name,
                'category_code' => $cat->category_code,
                'description' => $cat->description,
                'status' => $cat->status,
                'edit_url' => route('admin.category.edit', $cat->id),
                'delete_url' => route('admin.category.destroy', $cat->id),
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}
