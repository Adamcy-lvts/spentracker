<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'color' => 'required|string|max:7', // hex color code
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $category = Category::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified category.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // Check if category is being used by any expenses
        if ($category->expenses()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that has associated expenses',
            ], Response::HTTP_CONFLICT);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}