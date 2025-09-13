<?php

namespace App\Http\Controllers;

use App\Models\Adhesive;
use App\Models\Category;
use App\Models\Company;
use App\Models\ProductDetail;
use App\Models\Productimg;
use App\Models\ProductSample;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductDetail::with(['company', 'category', 'images', 'sampleImages'])->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $companies = Company::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        $adhesives = Adhesive::select('id', 'name')->get();
        $zones = Zone::select('id', 'name')->get();

        return view('products.create', compact('companies', 'categories', 'adhesives', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'application_area' => 'nullable|string|max:255',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'thickness' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:10',
            'gst_percentage' => 'nullable|numeric|min:0',
            'warranty_period' => 'nullable|integer|min:0',
            'warranty_type' => 'nullable|string|in:months,years',
            'adhesive_id' => 'nullable|exists:adhesives,id',
            'labor_charges' => 'nullable|numeric|min:0',
            'delivery_duration' => 'nullable|integer|min:0',
            'delivery_unit' => 'nullable|string|in:days,months,years',
            'custom_keys' => 'nullable|array',
            'custom_values' => 'nullable|array',
            'other_parameters' => 'nullable',
        ]);

        try {
            // Prepare payload similar to update(), allowing custom params
            $data = $request->except(['custom_keys', 'custom_values']);

            if ($request->has('custom_keys') && $request->has('custom_values')) {
                $customParams = [];
                foreach ($request->custom_keys as $index => $key) {
                    if (!empty($key) && isset($request->custom_values[$index])) {
                        $customParams[$key] = $request->custom_values[$index];
                    }
                }
                if (!empty($customParams)) {
                    $data['other_parameters'] = $customParams;
                }
            } elseif ($request->filled('other_parameters')) {
                // If sent as JSON string, decode to array for JSON column cast
                $decoded = json_decode($request->input('other_parameters'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['other_parameters'] = $decoded;
                } else {
                    // Fallback: wrap raw string in a descriptive key
                    $data['other_parameters'] = ['notes' => $request->input('other_parameters')];
                }
            }

            $productDetail = ProductDetail::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Product detail created successfully',
                'product_id' => $productDetail->id
            ]);
        } catch (\Exception $e) {
            Log::error('Product detail creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating product detail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeProductForm(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required|exists:productdetails,id',
            'product_image' => 'nullable|image|max:10240', // Added validation for the image
            'pdf_name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:50',
            'product_color' => 'nullable|string|max:50',
            'purchase_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_available' => 'nullable|boolean',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('product_image')) {
                $imagePath = $request->file('product_image')->store('products', 'public');
            }

            $productImage = Productimg::create([
                'productdetail_id' => $request->product_detail_id,
                'image_path' => $imagePath, // Save the image path
                'pdf_name' => $request->pdf_name,
                'product_code' => $request->product_code,
                'product_color' => $request->product_color,
                'purchase_cost' => $request->purchase_cost,
                'selling_price' => $request->selling_price,
                'discount_price' => $request->discount_price,
                'stock_available' => $request->boolean('stock_available'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product form details saved successfully',
                'product_image' => $productImage
            ]);
        } catch (\Exception $e) {
            Log::error('Product form details creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving product form details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeProductImage(Request $request)
    {
        $request->validate([
            'product_detail_id' => 'required|exists:productdetails,id',
            'product_image' => 'required|image|max:10240', // 10MB max
        ]);

        try {
            $productImagePath = $request->file('product_image')->store('products', 'public');
            
            $productImage = Productimg::where('productdetail_id', $request->product_detail_id)
                ->update(['image_path' => $productImagePath]);

            return response()->json([
                'success' => true,
                'message' => 'Product image saved successfully',
                'product_image' => $productImage
            ]);
        } catch (\Exception $e) {
            Log::error('Product image upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading product image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeSampleImages(Request $request)
    {
        $request->validate([
            'product_image_id' => 'required|exists:productimgs,id',
            'product_detail_id' => 'required|exists:productdetails,id',
            'sample_images.*' => 'required|image|max:10240', // 10MB max per image
        ]);

        try {
            DB::beginTransaction();

            $uploadedImages = [];
            
            if ($request->hasFile('sample_images')) {
                foreach ($request->file('sample_images') as $image) {
                    $path = $image->store('products/samples', 'public');
                    $sampleImage = ProductSample::create([
                        'product_detail_id' => $request->product_detail_id,
                        'product_img_id' => $request->product_image_id,
                        'image_path' => $path
                    ]);
                    $uploadedImages[] = $sampleImage;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sample images uploaded successfully',
                'sample_images' => $uploadedImages
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sample images upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading sample images: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $product = ProductDetail::with(['company', 'category', 'adhesive', 'images', 'sampleImages'])
            ->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = ProductDetail::with(['images', 'sampleImages'])->findOrFail($id);
        $companies = Company::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        $adhesives = Adhesive::select('id', 'name')->get();
    
        return view('products.edit', compact('product', 'companies', 'categories', 'adhesives'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'application_area' => 'nullable|string|max:255',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'thickness' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:10',
            'gst_percentage' => 'nullable|numeric|min:0',
            'warranty_period' => 'nullable|integer|min:0',
            'warranty_type' => 'nullable|string|in:months,years',
            'adhesive_id' => 'nullable|exists:adhesives,id',
            'labor_charges' => 'nullable|numeric|min:0',
            'delivery_duration' => 'nullable|integer|min:0',
            'delivery_unit' => 'nullable|string|in:days,months,years',
            'custom_keys' => 'nullable|array',
            'custom_values' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();
            
            $product = ProductDetail::findOrFail($id);
            
            // Prepare the data for update
            $data = $request->except(['custom_keys', 'custom_values']);
            
            // Handle custom parameters
            if ($request->has('custom_keys') && $request->has('custom_values')) {
                $customParams = [];
                foreach ($request->custom_keys as $index => $key) {
                    if (!empty($key) && isset($request->custom_values[$index])) {
                        $customParams[$key] = $request->custom_values[$index];
                    }
                }
                $data['other_parameters'] = $customParams;
            }
            
            $product->update($data);
            
            DB::commit();
            return redirect()->back()->with('success', 'Product updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Find the product with all its relationships
            $product = ProductDetail::with(['images', 'sampleImages'])->findOrFail($id);
            
            Log::info('Starting deletion of product ID: ' . $id);
            
            // Delete associated images from storage
            foreach ($product->images as $image) {
                if ($image->image_path) {
                    Log::info('Deleting image from storage: ' . $image->image_path);
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            
            foreach ($product->sampleImages as $sample) {
                if ($sample->image_path) {
                    Log::info('Deleting sample image from storage: ' . $sample->image_path);
                    Storage::disk('public')->delete($sample->image_path);
                }
            }
            
            // Delete related records first
            Log::info('Deleting related records for product ID: ' . $id);
            $product->images()->delete();
            $product->sampleImages()->delete();
            
            // Delete the product
            Log::info('Deleting main product record ID: ' . $id);
            $product->delete();
            
            DB::commit();
            Log::info('Successfully deleted product ID: ' . $id);
            
            return response()->json([
                'success' => true,
                'message' => 'Product and all associated data deleted successfully',
                'redirect' => false
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product deletion error for ID ' . $id . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeProductImage($id)
    {
        try {
            DB::beginTransaction();
            
            $image = Productimg::findOrFail($id);
            
            // Delete the file from storage
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
            
            // Delete the database record
            $image->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Product image deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product image deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeSampleImage($id)
    {
        try {
            DB::beginTransaction();
            
            $sampleImage = ProductSample::findOrFail($id);
            
            // Delete the file from storage
            if ($sampleImage->image_path) {
                Storage::disk('public')->delete($sampleImage->image_path);
            }
            
            // Delete the database record
            $sampleImage->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sample image deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sample image deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting sample image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editProductImage($id, $product_id)
    {
        $productImage = Productimg::with('productDetail')->findOrFail($id);
        $product = ProductDetail::findOrFail($product_id);
        
        return view('products.edit-image', compact('productImage', 'product'));
    }

    public function getProductImage($id)
    {
        try {
            $productImage = Productimg::findOrFail($id);
            
            // Get the full URL for the image
            $imageUrl = $productImage->image_path ? asset('storage/' . $productImage->image_path) : null;
            
            return response()->json([
                'success' => true,
                'product_image' => [
                    'id' => $productImage->id,
                    'pdf_name' => $productImage->pdf_name,
                    'product_code' => $productImage->product_code,
                    'product_color' => $productImage->product_color,
                    'purchase_cost' => $productImage->purchase_cost,
                    'selling_price' => $productImage->selling_price,
                    'discount_price' => $productImage->discount_price,
                    'stock_available' => $productImage->stock_available,
                    'image_path' => $productImage->image_path,
                    'image_url' => $imageUrl
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching product image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProductImage(Request $request, $id)
    {
        $request->validate([
            'pdf_name' => 'required|string|max:255',
            'product_code' => 'nullable|string|max:50',
            'product_color' => 'nullable|string|max:50',
            'purchase_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_available' => 'required|boolean',
            'product_image' => 'nullable|image|max:10240', // 10MB max
        ]);

        try {
            DB::beginTransaction();
            
            $productImage = Productimg::findOrFail($id);
            
            // Update image if new one is uploaded
            if ($request->hasFile('product_image')) {
                // Delete old image
                if ($productImage->image_path) {
                    Storage::disk('public')->delete($productImage->image_path);
                }
                // Store new image
                $imagePath = $request->file('product_image')->store('products', 'public');
                $productImage->image_path = $imagePath;
            }

            // Update other fields
            $productImage->pdf_name = $request->pdf_name;
            $productImage->product_code = $request->product_code;
            $productImage->product_color = $request->product_color;
            $productImage->purchase_cost = $request->purchase_cost;
            $productImage->selling_price = $request->selling_price;
            $productImage->discount_price = $request->discount_price;
            $productImage->stock_available = $request->stock_available;
            $productImage->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product image updated successfully',
                'product_image' => $productImage
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product image update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating product image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSampleImage($id)
    {
        try {
            $sampleImage = ProductSample::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'sample_image' => [
                    'id' => $sampleImage->id,
                    'image_path' => $sampleImage->image_path
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sample image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sample image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSampleImage(Request $request, $id)
    {
        $request->validate([
            'sample_image' => 'required|image|max:10240', // 10MB max
        ]);

        try {
            $sampleImage = ProductSample::findOrFail($id);
            
            // Delete old image if exists
            if ($sampleImage->image_path) {
                Storage::disk('public')->delete($sampleImage->image_path);
            }

            // Store new image
            $path = $request->file('sample_image')->store('products/samples', 'public');
            
            // Update record
            $sampleImage->update([
                'image_path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sample image updated successfully',
                'sample_image' => [
                    'id' => $sampleImage->id,
                    'image_path' => $sampleImage->image_path
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating sample image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating sample image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addSampleImage(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:productdetails,id',
                'sample_image' => 'required|image|max:10240', // 10MB max
            ]);

            DB::beginTransaction();

            if ($request->hasFile('sample_image')) {
                // Get the main product image ID
                $mainProductImage = Productimg::where('productdetail_id', $request->product_id)
                    ->latest()
                    ->first();

                if (!$mainProductImage) {
                    throw new \Exception('Please save the main product image first before adding sample images');
                }

                $imagePath = $request->file('sample_image')->store('products/samples', 'public');
                
                $sampleImage = ProductSample::create([
                    'product_detail_id' => $request->product_id,
                    'product_img_id' => $mainProductImage->id,
                    'image_path' => $imagePath
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Sample image added successfully',
                    'sample_image' => [
                        'id' => $sampleImage->id,
                        'image_path' => $sampleImage->image_path,
                        'image_url' => asset('storage/' . $sampleImage->image_path)
                    ]
                ]);
            }

            throw new \Exception('No image file provided');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding sample image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding sample image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:productdetails,id'
            ]);

            if (empty($request->ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one product to delete'
                ], 400);
            }

            DB::beginTransaction();

            $products = ProductDetail::with(['images', 'sampleImages'])
                ->whereIn('id', $request->ids)
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No products found to delete'
                ], 404);
            }

            foreach ($products as $product) {
                // Delete associated images from storage
                foreach ($product->images as $image) {
                    if ($image->image_path) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                }
                
                foreach ($product->sampleImages as $sample) {
                    if ($sample->image_path) {
                        Storage::disk('public')->delete($sample->image_path);
                    }
                }
                
                // Delete related records first
                $product->images()->delete();
                $product->sampleImages()->delete();
                
                // Delete the product
                $product->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' products deleted successfully',
                'redirect' => false
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk product deletion error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getImages($id)
    {
        try {
            $images = Productimg::where('productdetail_id', $id)->get();
            return response()->json([
                'success' => true,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching product images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product images: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSampleImages($id)
    {
        try {
            $samples = ProductSample::where('product_img_id', $id)->get();
            return response()->json([
                'success' => true,
                'samples' => $samples
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sample images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sample images: ' . $e->getMessage()
            ], 500);
        }
    }
}