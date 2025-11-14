<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Kept for demonstration of products_count
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * Corresponds to the index.blade.php view.
     */
    public function index(): View
    {
        // Fetch categories using Eloquent with product count eagerly loaded/appended.
        // The withCount('products') adds a 'products_count' attribute to each category model.
        $categories = Category::withCount('products')->paginate(10);

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:tbl_categories,category_name',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
            // Assuming you want to set created_at and updated_at manually since timestamps=false
            'created_at' => now(),
            'updated_at' => now(),
            // 'created_by' and 'updated_by' are placeholders, assume current user ID if applicable
            'created_by' => Auth::id() ?? 'system',
            'updated_by' => Auth::id() ?? 'system',
        ]);

        return redirect()->route('admin.getAllCategories')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            // Ignore the current category's name when checking for uniqueness
            'category_name' => 'required|string|max:255|unique:tbl_categories,category_name,' . $category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'updated_at' => now(),
            'updated_by' => Auth::id() ?? 'system',
        ]);

        return redirect()->route('admin.getAllCategories')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): \Illuminate\Http\JsonResponse
    {
        try {
            // Check if there are related products before deleting
            if ($category->products()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category: it still has ' . $category->products()->count() . ' associated products.'
                ], 409); // 409 Conflict
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Category deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during deletion.'
            ], 500);
        }
    }
}
