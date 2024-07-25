<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Brands;
use App\Models\Models;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\NumberOfUse;
use App\Models\SubCategory;
use App\Models\MainMaterial;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Models\ProductBrands;
use App\Models\ProductImages;
use App\Models\Sterilization;
use App\Models\ProductVaraint;
use App\Models\ProductCatgeory;
use App\Models\ProductSubCatgeory;
use App\Models\ProductCertifcation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{

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
        $products = Product::with('productBrands.brands', 'productCertifications.certification', 'productCategory.categories', 'productSubCategory.subCategories')->where('status', '1')->latest()->get();
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
        $validatedData = $request->validate([
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
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
        ], [
            'category_id' => 'Category is required.',
            'category_id.*' => 'Category is required.',
            'brand_id' => 'Brand is rquried.',
            'brand_id.*' => 'Brand is rquried.',
            'certification_id' => 'Certification is required',
            'certification_id.*' => 'Certification is required'
        ]);
        try {
            $product = new Product($request->only([
                'product_name', 'short_name', 'slug', 'company', 'country',
                'models', 'product_commission', 'video_link',
                'short_description', 'long_description', 'status', 'sterilizations', 'product_use_status'
            ]));
            if ($request->hasFile('thumbnail_image')) {
                $thumbnail_image = $request->file('thumbnail_image');
                $thumbnail_name = time() . '.' . $thumbnail_image->getClientOriginalExtension();
                $thumbnail_path = 'admin/assets/images/products/' . $thumbnail_name;
                $thumbnail_image->move(public_path('admin/assets/images/products'), $thumbnail_name);
                $product->thumbnail_image = $thumbnail_path;
            }
            $product->save();
            // Save product variants
            $category_ids = $request->input('category_id');
            $sub_category_ids = $request->input('sub_category_id');
            $brand_ids = $request->input('brand_id');
            $certification_ids = $request->input('certification_id');
            foreach ($category_ids as $categoryId) {
                $productVariant = new ProductCatgeory();
                $productVariant->product_id = $product->id;
                $productVariant->category_id = $categoryId;
                $productVariant->save();
            }
            if ($sub_category_ids) {
                foreach ($sub_category_ids as $key => $subCategoryId) {
                    $productVariant = new ProductSubCatgeory();
                    $productVariant->product_id = $product->id;
                    $productVariant->sub_category_id = $subCategoryId;
                    $productVariant->save();
                }
            }
            foreach ($brand_ids as $brandId) {
                $productVariant = new ProductBrands();
                $productVariant->product_id = $product->id;
                $productVariant->brand_id = $brandId;
                $productVariant->save();
            }
            foreach ($certification_ids as $certificationIds) {
                $productVariant = new ProductCertifcation();
                $productVariant->product_id = $product->id;
                $productVariant->certification_id = $certificationIds;
                $productVariant->save();
            }

            return redirect()->route('product.index')->with('message', 'Product Created Successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save Product. Please try again later.');
        }
    }
    public function productEdit($id)
    {
        $products = Product::with('productBrands.brands', 'productCertifications.certification', 'productCategory.categories', 'productSubCategory.subCategories')->where('status', '1')->find($id);
        if (!$products) {
            return response()->json(['alert' => 'error', 'message' => 'Models Not Found'], 500);
        }
        return response()->json($products);
    }

    public function productUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'short_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'company' => 'required',
            'models' => 'required',
            'country' => 'required|string|max:255',
            'sterilizations' => 'required|string|max:255',
            'product_use_status' => 'required|string|max:255',
            'product_commission' => 'required|string|max:255',
            'video_link' => 'nullable|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
        ]);

        try {
            $product = Product::findOrFail($id);

            $product->fill($request->only([
                'product_name', 'short_name', 'slug', 'company', 'country',
                'models', 'product_commission', 'video_link',
                'short_description', 'long_description', 'status', 'sterilizations', 'product_use_status'
            ]));

            if ($request->hasFile('thumbnail_image')) {
                $thumbnail_image = $request->file('thumbnail_image');
                $thumbnail_name = time() . '.' . $thumbnail_image->getClientOriginalExtension();
                $thumbnail_path = 'admin/assets/images/products/' . $thumbnail_name;
                $thumbnail_image->move(public_path('admin/assets/images/products'), $thumbnail_name);
                $product->thumbnail_image = $thumbnail_path;
            }

            if ($request->hasFile('banner_image')) {
                $banner_image = $request->file('banner_image');
                $banner_name = time() . '.' . $banner_image->getClientOriginalExtension();
                $banner_path = 'admin/assets/images/products/' . $banner_name;
                $banner_image->move(public_path('admin/assets/images/products'), $banner_name);
                $product->banner_image = $banner_path;
            }

            $product->save();

            // Update product variants
            $category_ids = json_decode($request->input('category_id'), true);
            $sub_category_ids = json_decode($request->input('sub_category_id'), true);
            $brand_ids = json_decode($request->input('brand_id'), true);
            $certification_ids = json_decode($request->input('certification_id'), true);

            // Delete old variants
            ProductCatgeory::where('product_id', $product->id)->delete();
            ProductSubCatgeory::where('product_id', $product->id)->delete();
            ProductBrands::where('product_id', $product->id)->delete();
            ProductCertifcation::where('product_id', $product->id)->delete();

            foreach ($category_ids as $categoryId) {
                $productCategory = new ProductCatgeory();
                $productCategory->product_id = $product->id;
                $productCategory->category_id = $categoryId;
                $productCategory->save();
            }

            if ($sub_category_ids) {
                foreach ($sub_category_ids as $subCategoryId) {
                    $productSubCategory = new ProductSubCatgeory();
                    $productSubCategory->product_id = $product->id;
                    $productSubCategory->sub_category_id = $subCategoryId;
                    $productSubCategory->save();
                }
            }

            foreach ($brand_ids as $brandId) {
                $productBrand = new ProductBrands();
                $productBrand->product_id = $product->id;
                $productBrand->brand_id = $brandId;
                $productBrand->save();
            }

            foreach ($certification_ids as $certificationId) {
                $productCertification = new ProductCertifcation();
                $productCertification->product_id = $product->id;
                $productCertification->certification_id = $certificationId;
                $productCertification->save();
            }

            return response()->json(['message' => 'Product Updated Successfully!']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
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

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
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
            $imagePath = public_path('admin/assets/images/products' . $image->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $image->delete();
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Image Delete Successfully!']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete image.');
        }
    }
    // ################## Store and updtae Product Varaints ########################
    public function getProductVariants($id)
    {
        $variants = ProductVaraint::where('product_id', $id)->get();
        $json_data["data"] = $variants;
        return json_encode($json_data);
    }
    public function productVariantViewIndex($id)
    {
        $units = Unit::all();
        $productVariant = ProductVaraint::where('product_id', $id)->get();
        return view('admin.product.product_variants_index', compact('productVariant', 'id', 'units'));
    }
    public function productVariantIndex($id)
    {
        $units = Unit::all();
        $data = Product::where('status', '1')->with('productVaraint')->find($id);
        return view('admin.product.product_variant_create', compact('data', 'units'));
    }
    public function productVariantStore(Request $request, $productId)
    {
        try {
            $request->validate([
                'variants.*.s_k_u' => 'required|string',
                'variants.*.packing' => 'required|string',
                'variants.*.unit' => 'required|string',
                'variants.*.quantity' => 'required|integer',
                'variants.*.price_per_unit' => 'required|numeric',
                'variants.*.selling_price_per_unit' => 'required|numeric',
                'variants.*.actual_weight' => 'required|numeric',
                'variants.*.shipping_weight' => 'required|numeric',
                'variants.*.shipping_chargeable_weight' => 'required|numeric',
                'variants.*.description' => 'required|string',
            ], [
                'variants.*.s_k_u.required' => 'SKU is required.',
                'variants.*.s_k_u.string' => 'SKU must be a string.',

                'variants.*.packing.required' => 'Packing is required.',
                'variants.*.packing.string' => 'Packing must be a string.',

                'variants.*.unit.required' => 'Unit is required.',
                'variants.*.unit.string' => 'Unit must be a string.',

                'variants.*.quantity.required' => 'Quantity is required.',
                'variants.*.quantity.integer' => 'Quantity must be an integer.',

                'variants.*.price_per_unit.required' => 'Actual Price/Unit is required.',
                'variants.*.price_per_unit.numeric' => 'Actual Price/Unit must be a number.',

                'variants.*.selling_price_per_unit.required' => 'Selling Price/Unit is required.',
                'variants.*.selling_price_per_unit.numeric' => 'Selling Price/Unit must be a number.',

                'variants.*.actual_weight.required' => 'Actual Weight is required.',
                'variants.*.actual_weight.numeric' => 'Actual Weight must be a number.',

                'variants.*.shipping_weight.required' => 'Shipping Weight is required.',
                'variants.*.shipping_weight.numeric' => 'Shipping Weight must be a number.',

                'variants.*.shipping_chargeable_weight.required' => 'Shipping Chargeable Weight is required.',
                'variants.*.shipping_chargeable_weight.numeric' => 'Shipping Chargeable Weight must be a number.',

                'variants.*.description.required' => 'Description is required.',
                'variants.*.description.string' => 'Description must be a string.',
            ]);


            foreach ($request->variants as $variant) {
                ProductVaraint::updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    [
                        'product_id' => $productId,
                        's_k_u' => $variant['s_k_u'],
                        'packing' => $variant['packing'],
                        'unit' => $variant['unit'],
                        'quantity' => $variant['quantity'],
                        'price_per_unit' => $variant['price_per_unit'],
                        'selling_price_per_unit' => $variant['selling_price_per_unit'],
                        'actual_weight' => $variant['actual_weight'],
                        'shipping_weight' => $variant['shipping_weight'],
                        'shipping_chargeable_weight' => $variant['shipping_chargeable_weight'],
                        'status' => $variant['status'],
                        'description' => $variant['description'],
                    ]
                );
            }
            return  redirect()->route('product.index')->with('message', 'Product variants saved successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return redirect()->back()->with('error', 'Failed to save product variants. Please try again later.');
        }
    }
    public function showVariants($id)
    {
        $productVariant  = ProductVaraint::find($id);
        if (!$productVariant) {
            return response()->json(['alert' => 'error', 'message' => 'Models Not Found'], 500);
        }
        return response()->json($productVariant);
    }

    public function updateVariant(Request $request, $id)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     'category_id' => 'exists:categories,id',
            //     'subcategory_id' => 'exists:sub_categories,id',
            //     'name' => 'required',
            //     'commision' => 'required|numeric',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json(['errors' => $validator->errors()], 422);
            // }

            $product = ProductVaraint::findOrFail($id);
            $product->fill($request->only(['s_k_u', 'packing', 'unit', 'quantity', 'price_per_unit', 'selling_price_per_unit', 'actual_weight', 'shipping_weight', 'shipping_chargeable_weight', 'status', 'description']));
            $product->save();
            return response()->json(['alert' => 'success', 'message' => 'Product Variant Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Updating Product Variant!' . $e->getMessage()], 500);
        }
    }
    public function updateVariantsStatus($id)
    {
        try {
            $productVariant = ProductVaraint::findOrFail($id);
            if ($productVariant->status == '0') {
                $productVariant->status = '1';
                $message = 'Product Variant Active Successfully';
            } else if ($productVariant->status == '1') {
                $productVariant->status = '0';
                $message = 'Product Variant In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $productVariant->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
    public function deleteProductVariant($id)
    {
        $productVariant = ProductVaraint::findOrFail($id);
        $productVariant->delete();
        return response()->json(['alert' => 'success', 'message' => 'Product Variant Deleted SuccessFully!']);
    }
}
