<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Brands;
use App\Models\Models;
use Mockery\Undefined;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Condation;
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
use Illuminate\Support\Facades\DB;
use App\Models\ProductCertifcation;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Traits\CountryApiRequestTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use CountryApiRequestTrait;
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
        $countries = $this->fetchApiData('https://api.countrystatecity.in/v1/countries');
        if (isset($countries['error'])) {
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
        $conditions = Condation::all();
        $products = Product::with(
            'productBrands.brands',
            'productCertifications.certification',
            'productCategory.categories',
            'productSubCategory.subCategories',
            'productTax'
        )->where('status', '1')->latest()->get();
        return view('admin.product.index', compact('conditions', 'mianMaterials', 'suppliers', 'numberOfUses', 'subCategories', 'countries', 'categories', 'brands', 'models', 'certifications', 'companies', 'sterilizations', 'products'));
    }

    public function productCreateIndex()
    {
        $countries = $this->fetchApiData('https://api.countrystatecity.in/v1/countries');
        if (isset($countries['error'])) {
            $countries = [];
        }
        // return $countries;
        $categories = Category::where('status', '1')->get();
        $subCategories = SubCategory::where('status', '1')->get();
        $brands = Brands::where('status', '1')->get();
        $models = Models::where('status', '1')->get();
        $certifications = Certification::where('status', '1')->get();
        $companies = Company::where('status', '1')->get();
        $sterilizations = Sterilization::where('status', '1')->get();
        $numberOfUses = NumberOfUse::where('status', '1')->get();
        $suppliers = Supplier::where('status', '1')->get();
        $mianMaterials = MainMaterial::where('status', '1')->get();
        $conditions = Condation::all();
        return view('admin.product.create', compact('conditions', 'subCategories', 'mianMaterials', 'suppliers', 'numberOfUses', 'countries', 'categories', 'brands', 'models', 'certifications', 'companies', 'sterilizations'));
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

    public function productStore(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = $request->draft_id
                ? Product::findOrFail($request->draft_id)
                : new Product();
            $product->fill($request->only([
                'product_hts',
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
                'product_condition',
            ]));

            // Handle thumbnail image upload
            if ($request->hasFile('thumbnail_image')) {
                $oldImagePath = $product->thumbnail_image;
                if ($oldImagePath && File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $thumbnail_image = $request->file('thumbnail_image');
                $thumbnail_name = time() . '.' . $thumbnail_image->getClientOriginalExtension();
                $thumbnail_path = 'public/admin/assets/images/products/' . $thumbnail_name;
                $thumbnail_image->move(public_path('admin/assets/images/products'), $thumbnail_name);
                $product->thumbnail_image = $thumbnail_path;
            }
            $product->is_darft = 1;
            $product->save();
            $this->updateProductRelationships($product, $request);
            DB::commit();
            return redirect()->route('product.index')->with('message', 'Product Created Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save Product. Please try again later.' . $e->getMessage());
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
    public function productUpdate(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->fill($request->only([
                'product_hts',
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
                'product_condition'
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
            return response()->json(['error' => 'Failed to update Product. Please try again later.' . $e->getMessage()]);
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
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.' . $e->getMessage()]);
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

    public function productAutosave(Request $request)
    {
        try {
            $product = $request->draft_id
                ? Product::find($request->draft_id)
                : new Product();
            $product->fill($request->only([
                'product_hts',
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
                'product_condition',
            ]));

            // Handle thumbnail image upload
            if ($request->hasFile('thumbnail_image')) {
                $oldImagePath = $product->thumbnail_image;
                if ($oldImagePath && File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $thumbnail_image = $request->file('thumbnail_image');
                $thumbnail_name = time() . '.' . $thumbnail_image->getClientOriginalExtension();
                $thumbnail_path = 'public/admin/assets/images/products/' . $thumbnail_name;
                $thumbnail_image->move(public_path('admin/assets/images/products'), $thumbnail_name);
                $product->thumbnail_image = $thumbnail_path;
            }

            $product->save();

            $this->updateProductRelationships($product, $request);

            return response()->json(['draft_id' => $product->id, 'message' => 'Product Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update Product. Please try again later.' . $e->getMessage()]);
        }
    }

    private function updateProductRelationships($product, $request)
    {
        $relations = [
            'category_id' => ProductCatgeory::class,
            'sub_category_id' => ProductSubCatgeory::class,
            'brand_id' => ProductBrands::class,
            'certification_id' => ProductCertifcation::class,
            'material_id' => ProductMaterial::class,
        ];

        foreach ($relations as $key => $model) {
            if ($request->filled($key)) {
                foreach ($request->input($key) as $relationId) {
                    $model::updateOrCreate(
                        ['product_id' => $product->id, "{$key}" => $relationId]
                    );
                }
            }
        }
        if ($request->filled('taxes')) {
            foreach ($request->input('taxes') as $tax) {
                // Check if 'tax_per_city' and 'local_tax' are set before accessing them
                if (isset($tax['tax_per_city']) && isset($tax['local_tax'])) {
                    ProductTax::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'tax_per_city' => $tax['tax_per_city']
                        ],
                        [
                            'local_tax' => $tax['local_tax']
                        ]
                    );
                } else {
                    // Handle missing tax values gracefully
                    Log::warning('Missing tax_per_city or local_tax for product: ' . $product->id);
                }
            }
        }
    }
}
