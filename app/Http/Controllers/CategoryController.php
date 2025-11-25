<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Kept for demonstration of products_count
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $rules = [
            'category_name' => 'required|string|max:255|unique:tbl_categories,category_name',
            'description' => 'nullable|string|max:500', // NEW: Validation for description
            'category_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // NEW: Image validation
        ];

        $messages = [
            'category_name.required' => 'The category name is required.',
            'category_name.unique' => 'The category name must be unique.',
            'category_image.required' => 'The category image is required.',
            'category_image.image' => 'The category image must be an image file.',
            'category_image.mimes' => 'The category image must be a file of type: jpeg, png, jpg, gif, svg.',
            'category_image.max' => 'The category image may not be greater than 2MB.',
        ];


        $imagePath = null;
        if ($request->hasFile('category_image')) {
            // Store the image in the 'public/categories' directory
            $imagePath = $request->file('category_image')->store('categories', 'public');
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withInput()
                ->with('error', $validator->errors()->first());
        }

        Category::create([
            'category_name' => $request->input('category_name'),
            'description' => $request->input('description') ?? null,
            'category_image' => $imagePath, // Save the path
            'created_at' => now(),
            'updated_at' => now(),
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
        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:tbl_categories,category_name,' . $category->id,
            'description' => 'nullable|string|max:500', // NEW
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // NEW
            'remove_image' => 'nullable|boolean', // NEW: To handle image deletion
        ]);

        $data = [
            'category_name' => $validated['category_name'],
            'description' => $validated['description'] ?? null,
            'updated_at' => now(),
            'updated_by' => Auth::id() ?? 'system',
        ];

        // 1. Handle image removal
        if ($request->boolean('remove_image') && $category->category_image) {
            Storage::disk('public')->delete($category->category_image);
            $data['category_image'] = null;
        }

        // 2. Handle new image upload
        if ($request->hasFile('category_image')) {
            // Delete old image if it exists
            if ($category->category_image) {
                Storage::disk('public')->delete($category->category_image);
            }
            // Store new image
            $data['category_image'] = $request->file('category_image')->store('categories', 'public');
        }

        $category->update($data);

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
