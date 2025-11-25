<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        // dd($request->all());
        // die();

        $request->merge([
            'isHot' => $request->has('isHot') ? 1 : 0,
            'isActive' => $request->has('isActive') ? 1 : 0,
        ]);

        $rules = [
            'product_name' => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:1|max:100000',
            'category_id'  => 'required|exists:tbl_categories,id',
            'isHot'        => 'boolean',
            'isActive'     => 'boolean',
            'attachments'  => 'required|array|min:1',
            'attachments.*' => 'required|file|mimes:jpeg,jpg,png,gif|max:2048',
        ];

        $messages = [
            'product_name.required' => 'Please enter a product name.',
            'price.required'        => 'Please provide a price.',
            'price.numeric'         => 'Price must be a valid number.',
            'price.min'             => 'Price must be at least 1.',
            'price.max'             => 'Price must not exceed 100,000.',
            'category_id.required'  => 'Please select a category.',
            'category_id.exists'    => 'The selected category is invalid.',
            'attachments.required' => 'Please upload at least one image.',
            'attachments.*.mimes'   => 'Only JPG, JPEG, PNG, and GIF images are allowed.',
            'attachments.*.max'     => 'Each image must be smaller than 2 MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => '❌ Failed to add product.',
                'errors' => $validator->errors(),
            ], 422);
        }


        try {
            DB::beginTransaction();

            $attachments = $this->handleFileUploads($request->file('attachments'));
            $product = Product::create([
                'product_name' => $request->product_name,
                'description'  => $request->description,
                'price'        => $request->price,
                'category_id'  => $request->category_id,
                'isHot'        => $request->boolean('isHot'),
                'isActive'     => $request->boolean('isActive', true),
                'attachments'  => json_encode($attachments),
                'created_by'   => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product added successfully!',
                'product' => $product
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => '❌ Failed to add product. Please try again. ' . $e->getMessage()
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
        // Convert checkbox values before validation
        $request->merge([
            'isHot' => $request->has('isHot') ? 1 : 0,
            'isActive' => $request->has('isActive') ? 1 : 0,
        ]);

        $rules = [
            'product_name'  => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:1',
            'category_id'   => 'required|exists:tbl_categories,id',
            'isHot'         => 'boolean',
            'isActive'      => 'boolean',
            'attachments.*' => 'sometimes|file|mimes:jpeg,jpg,png,gif|max:2048',
        ];

        $messages = [
            'product_name.required' => 'Please enter a product name.',
            'price.required'        => 'Please provide a price.',
            'price.numeric'         => 'Price must be a valid number.',
            'price.min'             => 'Price must be at least 1.',
            'category_id.required'  => 'Please select a category.',
            'category_id.exists'    => 'The selected category is invalid.',
            'attachments.*.mimes'   => 'Only JPG, JPEG, PNG, and GIF images are allowed.',
            'attachments.*.max'     => 'Each image must be smaller than 2 MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['attachments']);
            $data['updated_by'] = Auth::id();

            // Preserve old attachments if no new ones uploaded
            $attachments = json_decode($product->attachments ?? '[]', true);

            if ($request->hasFile('attachments')) {
                // Delete old files
                foreach ($attachments as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $attachments = $this->handleFileUploads($request->file('attachments'));
            }

            $data['attachments'] = json_encode($attachments);
            $product->update($data);

            DB::commit();

            return redirect()->route('products.index')->with('success', '✅ Product “' . e($product->product_name) . '” updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Failed to update product.', $e);
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



    private function handleFileUploads($files)
    {
        $paths = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                $original = $file->getClientOriginalName();
                $name = time() . '_' . preg_replace('/\s+/', '_', $original);
                $paths[] = $file->storeAs('uploads/products', $name, 'public');
            }
        }
        return $paths;
    }

    private function validationErrorResponse($validator)
    {
        $errors = [];
        foreach ($validator->errors()->messages() as $field => $messages) {
            foreach ($messages as $msg) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ': ' . $msg;
            }
        }

        return response()->json([
            'success' => false,
            'title'   => 'Please fix the following:',
            'errors'  => $errors,
        ], 422);
    }

    private function serverErrorResponse($msg, $exception)
    {
        return response()->json([
            'success' => false,
            'title'   => 'Server Error',
            'message' => $msg,
            'debug'   => config('app.debug') ? $exception->getMessage() : null,
        ], 500);
    }
}
