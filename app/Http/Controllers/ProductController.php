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
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::orderBy('category_name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'category_id'    => 'nullable|exists:tbl_categories,id',
            'isHot'          => 'boolean',
            'isActive'       => 'boolean',
            'attachments.*'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $attachmentsData = [];

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('uploads/products', 'public');
                    $attachmentsData[] = $path;
                }
            }

            $productData = $request->except(['attachments']);
            $productData['attachments'] = json_encode($attachmentsData);
            $productData['created_by'] = Auth::id();

            $product = Product::create($productData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Product creation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('category');

        return response()->json([
            'success' => true,
            'product' => [
                'id'            => $product->id,
                'product_name'  => $product->product_name,
                'description'   => $product->description,
                'price'         => $product->price,
                'isHot'         => $product->isHot,
                'isActive'      => $product->isActive,
                'attachments'   => $product->attachments,
                'category_id'   => $product->category_id,
                'category_name' => $product->category->category_name ?? null,
            ],
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'category_id'    => 'nullable|exists:tbl_categories,id',
            'isHot'          => 'boolean',
            'isActive'       => 'boolean',
            'attachments.*'  => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $productData = $request->except(['attachments']);
            $productData['updated_by'] = Auth::id();

            $existingAttachments = json_decode($product->attachments ?? '[]', true);
            $newAttachments = [];

            // ✅ If user uploads new images
            if ($request->hasFile('attachments')) {
                // Delete old attachments first
                foreach ($existingAttachments as $filePath) {
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }

                // Upload and save new attachments
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('uploads/products', 'public');
                    $newAttachments[] = $path;
                }

                $productData['attachments'] = json_encode($newAttachments);
            }

            // ✅ If no new images uploaded, keep old ones
            else {
                $productData['attachments'] = json_encode($existingAttachments);
            }

            $product->update($productData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Product update failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            $attachments = json_decode($product->attachments ?? '[]', true);
            foreach ($attachments as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

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
                'message' => 'Product deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
