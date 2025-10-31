<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all products, ordered by the latest first,
        // and eager load the category relationship to avoid N+1 queries.
        $products = Product::with('category')->latest()->paginate(10);
        // Fetch all categories for dropdown in create/edit modals
        $categories = Category::orderBy('category_name')->get();
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get categories to populate the dropdown
        $categories = Category::pluck('category_name', 'id');
        return view('products.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'category_id'  => 'nullable|exists:tbl_categories,id',
            'isHot'        => 'boolean',
            'isActive'     => 'boolean',
            // Allow multiple files for the attachments field
            'attachments.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 2. Prepare Data
            $attachmentsData = [];

            // Handle file uploads (if any)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    // Store files in the 'public/uploads/products' directory
                    $path = $file->store('uploads/products', 'public');
                    $attachmentsData[] = $path;
                }
            }
            // Prepare the product data array
            $productData = $request->except(['attachments']);
            $productData['attachments'] = json_encode($attachmentsData); // Store array as JSON string
            $productData['created_by'] = Auth::id(); // Assuming user authentication is in place

            // 3. Create Product
            $product = Product::create($productData);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error and redirect back
            // \Log::error("Product creation failed: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Product creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

        $product->load('category');
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'description' => $product->description,
                'price' => $product->price,
                'isHot' => $product->isHot,
                'isActive' => $product->isActive,
                'image_path' => $product->image_path,
                'category_name' => $product->category->category_name ?? null,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::pluck('category_name', 'id');
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validation (Similar to store, but `sometimes` is used for files)
        $request->validate([
            'product_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'category_id'  => 'nullable|exists:tbl_categories,id',
            'isHot'        => 'boolean',
            'isActive'     => 'boolean',
            'attachments.*' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 2. Prepare Data and Handle Attachments
            $productData = $request->except(['attachments']);
            $currentAttachments = json_decode($product->attachments ?? '[]', true);
            $newAttachments = $currentAttachments;

            if ($request->hasFile('attachments')) {
                // Handle new file uploads
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('uploads/products', 'public');
                    $newAttachments[] = $path;
                }
            }
            // Note: Deleting old files or maintaining the list requires more complex form logic (e.g., hidden fields for existing files).
            // This basic example only appends new files.

            $productData['attachments'] = json_encode($newAttachments);
            $productData['updated_by'] = Auth::id(); // Assuming user authentication

            // 3. Update Product
            $product->update($productData);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Product update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Optional: Delete physical files from storage
            $attachments = json_decode($product->attachments ?? '[]', true);
            foreach ($attachments as $filePath) {
                // Ensure you delete the file from the correct disk (e.g., 'public')
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // Delete the product record
            $product->update(['isActive' => false]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Product deletion failed.'
            ], 500);
        }
    }
}
