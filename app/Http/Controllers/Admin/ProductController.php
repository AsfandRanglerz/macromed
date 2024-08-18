<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\Unit;
use App\Models\Brands;
use App\Models\Models;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductTax;
use App\Models\NumberOfUse;
use App\Models\SubCategory;
use App\Models\MainMaterial;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Models\ProductBrands;
use App\Models\ProductImages;
use App\Models\Sterilization;
use App\Models\ProductCatgeory;
use App\Models\ProductMaterial;
use Illuminate\Validation\Rule;
use App\Models\ProductSubCatgeory;
use App\Models\ProductCertifcation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mockery\Undefined;

class ProductController extends Controller
{
    // ########## Generate Product Code #############
    private function generateUniqueProductId()
    {
        do {
            $productCode = $this->generateRandomProductId();
        } while (Product::where('product_code', $productCode)->exists());

        return $productCode;
    }

    private function generateRandomProductId($length = 10)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $productCode = '';

        for ($i = 0; $i < $length; $i++) {
            $productCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $productCode;
    }
    public function productData()
    {
        $products = Product::with('productBrands.brands', 'productCertifications.certification', 'productCategory.categories', 'productSubCategory.subCategories')->latest()->get();
        $json_data["data"] = $products;
        return json_encode($json_data);
    }

    public function productIndex()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $countries = json_decode($response);

        // Decode the JSON response
        if ($countries == NULL) {
            $countries = [];
        }
        // return $countries;
        $categories = Category::where('status', '1')->get();
        $numberOfUses = NumberOfUse::where('status', '1')->get();
        $subCategories = SubCategory::where('status', '1')->get();
        $brands = Brands::where('status', '1')->get();
        $models = Models::where('status', '1')->get();
        $certifications = Certification::where('status', '1')->get();
        $companies = Company::where('status', '1')->get();
        $sterilizations = Sterilization::where('status', '1')->get();
        $suppliers = Supplier::where('status', '1')->get();
        $mianMaterials = MainMaterial::where('status', '1')->get();
        $products = Product::with(
            'productBrands.brands',
            'productCertifications.certification',
            'productCategory.categories',
            'productSubCategory.subCategories',
            'productTax'
        )->where('status', '1')->latest()->get();
        return view('admin.product.index', compact('mianMaterials', 'suppliers', 'numberOfUses', 'subCategories', 'countries', 'categories', 'brands', 'models', 'certifications', 'companies', 'sterilizations', 'products'));
    }

    public function productCreateIndex()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $countries = json_decode($response);

        // Decode the JSON response
        if ($countries == NULL) {
            $countries = [];
        }
        // return $countries;
        $categories = Category::where('status', '1')->get();
        $brands = Brands::where('status', '1')->get();
        $models = Models::where('status', '1')->get();
        $certifications = Certification::where('status', '1')->get();
        $companies = Company::where('status', '1')->get();
        $sterilizations = Sterilization::where('status', '1')->get();
        $numberOfUses = NumberOfUse::where('status', '1')->get();
        $suppliers = Supplier::where('status', '1')->get();
        $mianMaterials = MainMaterial::where('status', '1')->get();
        return view('admin.product.create', compact('mianMaterials', 'suppliers', 'numberOfUses', 'countries', 'categories', 'brands', 'models', 'certifications', 'companies', 'sterilizations'));
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::where('status', '1')->get(['id', 'name', 'supplier_id']);
        return response()->json($suppliers);
    }
    public function getSubCategories(Request $request)
    {
        $categoryIds = $request->category_ids;
        $subCategories = SubCategory::whereIn('category_id', $categoryIds)->get();
        return response()->json($subCategories);
    }
    public function productStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_name' => 'required|string|max:255',
            'product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')
            ],
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'brand_id' => 'required|array',
            'brand_id.*' => 'exists:brands,id',
            'certification_id' => 'required|array',
            'certification_id.*' => 'exists:certifications,id',
            'company' => 'required',
            'models' => 'required',
            'country' => 'required|string|max:255',
            'sterilizations' => 'required|string|max:255',
            'product_use_status' => 'required|string|max:255',
            'product_commission' => 'required|string|max:255',
            'video_link' => 'nullable|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'buyer_type' => 'required',
            'product_class' => 'required',
            'supplier_delivery_time' => 'required',
            'supplier_name' => 'required',
            'delivery_period' => 'required',
            'self_life' => 'required',
            'federal_tax' => 'required',
            'provincial_tax' => 'required',
            'material_id' => 'required|array',
            'material_id.*' => 'exists:main_materials,id',
        ], [
            'category_id' => 'Category is required.',
            'category_id.*' => 'Category is required.',
            'brand_id' => 'Brand is required.',
            'brand_id.*' => 'Brand is required.',
            'certification_id' => 'Certification is required',
            'certification_id.*' => 'Certification is required',
            'material_id' => 'Main Material is required',
            'material_id.*' => 'Main Material is required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $product = new Product($request->only([
                'product_name',
                'short_name',
                'slug',
                'company',
                'country',
                'models',
                'product_commission',
                'video_link',
                'short_description',
                'long_description',
                'status',
                'sterilizations',
                'product_use_status',
                'buyer_type',
                'product_class',
                'supplier_id',
                'supplier_delivery_time',
                'supplier_name',
                'delivery_period',
                'self_life',
                'federal_tax',
                'provincial_tax',
                'tab_1_heading',
                'tab_1_text',
                'tab_2_heading',
                'tab_2_text',
                'tab_3_heading',
                'tab_3_text',
                'tab_4_heading',
                'tab_4_text',
            ]));
            $product->product_code = $this->generateUniqueProductId();
            if ($request->hasFile('thumbnail_image')) {
                $thumbnail_image = $request->file('thumbnail_image');
                $thumbnail_name = time() . '.' . $thumbnail_image->getClientOriginalExtension();
                $thumbnail_path = 'public/admin/assets/images/products/' . $thumbnail_name;
                $thumbnail_image->move(public_path('admin/assets/images/products'), $thumbnail_name);
                $product->thumbnail_image = $thumbnail_path;
            }
            $product->save();
            // Save product variants
            $category_ids = $request->input('category_id');
            $sub_category_ids = $request->input('sub_category_id');
            $brand_ids = $request->input('brand_id');
            $certification_ids = $request->input('certification_id');
            $material_ids = $request->input('material_id');

            foreach ($category_ids as $categoryId) {
                ProductCatgeory::create([
                    'product_id' => $product->id,
                    'category_id' => $categoryId
                ]);
            }
            if ($sub_category_ids) {
                foreach ($sub_category_ids as $subCategoryId) {
                    ProductSubCatgeory::create([
                        'product_id' => $product->id,
                        'sub_category_id' => $subCategoryId
                    ]);
                }
            }
            foreach ($brand_ids as $brandId) {
                ProductBrands::create([
                    'product_id' => $product->id,
                    'brand_id' => $brandId
                ]);
            }

            foreach ($certification_ids as $certificationId) {
                ProductCertifcation::create([
                    'product_id' => $product->id,
                    'certification_id' => $certificationId
                ]);
            }

            foreach ($material_ids as $materialId) {
                ProductMaterial::create([
                    'product_id' => $product->id,
                    'material_id' => $materialId
                ]);
            }

            if ($request->has('taxes')) {
                foreach ($request->taxes as $tax) {
                    ProductTax::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'tax_per_city' => $tax['tax_per_city'],
                        ],
                        [
                            'local_tax' => $tax['local_tax'],
                        ]
                    );
                }
            }
            return redirect()->route('product.index')->with('message', 'Product Created Successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save Product. Please try again later.');
        }
    }

    public function productEdit($id)
    {
        $products = Product::with(
            'productBrands.brands',
            'productCertifications.certification',
            'productCategory.categories',
            'productSubCategory.subCategories',
            'productTax',
            'productMaterial.mainMaterial'
        )->find($id);
        if ($products) {
            return response()->json($products);
        } else {

            return response()->json(['alert' => 'error', 'warning' => 'Models Not Found']);
        }
    }
    public function productUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_name' => 'required|string|max:255',
            'product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($id)
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($id)
            ],
            'company' => 'required|string|max:255',
            'models' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'sterilizations' => 'required|string|max:255',
            'product_use_status' => 'required|string|max:255',
            'product_commission' => 'required|string|max:255',
            'video_link' => 'nullable|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'buyer_type' => 'required|string|max:255',
            'product_class' => 'required|string|max:255',
            'supplier_delivery_time' => 'required|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'delivery_period' => 'required|string|max:255',
            'self_life' => 'required|date',
            'federal_tax' => 'required|numeric',
            'provincial_tax' => 'required|numeric',

        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $product = Product::findOrFail($id);
            $product->fill($request->only([
                'product_name',
                'short_name',
                'slug',
                'company',
                'country',
                'models',
                'product_commission',
                'video_link',
                'short_description',
                'long_description',
                'status',
                'sterilizations',
                'product_use_status',
                'buyer_type',
                'product_class',
                'supplier_id',
                'supplier_delivery_time',
                'supplier_name',
                'delivery_period',
                'self_life',
                'federal_tax',
                'provincial_tax',
                'tab_1_heading',
                'tab_1_text',
                'tab_2_heading',
                'tab_2_text',
                'tab_3_heading',
                'tab_3_text',
                'tab_4_heading',
                'tab_4_text',
            ]));
            if ($request->hasFile('thumbnail_image')) {
                // Delete old image if exists
                $oldImagePath =  $product->thumbnail_image;
                // Delete old image if it exists
                if ($product->thumbnail_image &&  File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $thumbnail_image = $request->file('thumbnail_image');
                $thumbnail_name = time() . '.' . $thumbnail_image->getClientOriginalExtension();
                $thumbnail_path = 'public/admin/assets/images/products/' . $thumbnail_name;
                $thumbnail_image->move(public_path('admin/assets/images/products'), $thumbnail_name);
                $product->thumbnail_image = $thumbnail_path;
            }
            $product->save();

            // Update product variants
            $category_ids = json_decode($request->input('category_id'), true);
            $sub_category_ids = json_decode($request->input('sub_category_id'), true) ?? [];
            $brand_ids = json_decode($request->input('brand_id'), true);
            $certification_ids = json_decode($request->input('certification_id'), true);
            $material_ids = json_decode($request->input('material_id'), true);
            $taxes = $request->input('taxes');

            // Delete old variants
            ProductCatgeory::where('product_id', $product->id)->delete();
            ProductSubCatgeory::where('product_id', $product->id)->delete();
            ProductBrands::where('product_id', $product->id)->delete();
            ProductCertifcation::where('product_id', $product->id)->delete();
            ProductMaterial::where('product_id', $product->id)->delete();
            ProductTax::where('product_id', $product->id)->delete();
            // Insert new variants
            foreach ($category_ids as $categoryId) {
                ProductCatgeory::create(['product_id' => $product->id, 'category_id' => $categoryId]);
            }

            foreach ($sub_category_ids as $subCategoryId) {
                ProductSubCatgeory::create(['product_id' => $product->id, 'sub_category_id' => $subCategoryId]);
            }

            foreach ($brand_ids as $brandId) {
                ProductBrands::create(['product_id' => $product->id, 'brand_id' => $brandId]);
            }

            foreach ($certification_ids as $certificationId) {
                ProductCertifcation::create(['product_id' => $product->id, 'certification_id' => $certificationId]);
            }

            foreach ($material_ids as $materialId) {
                ProductMaterial::create(['product_id' => $product->id, 'material_id' => $materialId]);
            }
            // Handle taxes
            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                    ProductTax::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'tax_per_city' => $tax['tax_per_city']
                        ],
                        [
                            'local_tax' => $tax['local_tax']
                        ]
                    );
                }
            }
            return response()->json(['message' => 'Product Updated Successfully!']);
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to update Product. Please try again later.']);
        }
    }

    public function updateProductStatus($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->status == '0') {
                $product->status = '1';
                $message = 'Product Active Successfully';
            } else if ($product->status == '1') {
                $product->status = '0';
                $message = 'Product In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $product->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
    public function updateProductFeatureStatus($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->product_status == 'New Product') {
                $product->product_status = 'Featured Product';
                $message = 'Product has been successfully marked as Featured.';
            } else if ($product->product_status == 'Featured Product') {
                $product->product_status = 'New Product';
                $message = 'Product is no longer featured successfully.';
            } else {
                return response()->json(['alert' => 'info', 'message' => 'Product status is already updated or cannot be updated.']);
            }
            $product->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating the product status.']);
        }
    }


    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $imagePath = $product->thumbnail_image;
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $product->delete();
        return response()->json(['alert' => 'success', 'message' => 'Product Deleted SuccessFully!']);
    }
    //  ################# Image Upload Code ####################
    public function show($id)
    {
        $productImages = ProductImages::where('product_id', $id)->latest()->get();
        $product = Product::findOrFail($id);
        return view('admin.product.product_images_index', compact('product', 'productImages'));
    }

    public function uploadImages(Request $request, $id)
    {
        try {
            $request->validate([
                'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:1024', // Maximum file size in kilobytes
            ], [
                'image.*.image' => 'The file must be an image.',
                'image.*.mimes' => 'Only JPEG, PNG, JPG, and GIF formats are allowed.',
                'image.*.max' => 'Maximum file size allowed is 1MB.',
            ]);
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('admin/assets/images/products'), $filename);
                    ProductImages::create([
                        'product_id' => $id,
                        'image' => 'public/admin/assets/images/products/' . $filename,
                    ]);
                }
            }
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Image Add Successfully!']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while uploading images.');
        }
    }
    public function updateCoverStatus(Request $request, $productId, $imageId)
    {
        try {
            $selectedImage = ProductImages::where('id', $imageId)->firstOrFail();
            if ($selectedImage->status == '0') {
                $selectedImage->status = '1';
                $message = 'Product Image In Active Successfully';
            } else if ($selectedImage->status == '1') {
                $selectedImage->status = '0';
                $message = 'Product Image Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $selectedImage->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update cover image']);
        }
    }

    public function deleteImage($id)
    {
        try {
            $image = ProductImages::findOrFail($id);
            $imagePath = $image->image;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $image->delete();
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Image Delete Successfully!']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete image.');
        }
    }
}