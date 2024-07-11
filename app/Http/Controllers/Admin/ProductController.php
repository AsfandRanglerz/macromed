<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use App\Models\Models;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Models\ProductBrands;
use App\Models\ProductVaraint;
use App\Models\ProductCertifcation;
use App\Http\Controllers\Controller;
use App\Models\ProductCategorySubCategory;
use App\Models\ProductCatgeory;
use App\Models\ProductSubCatgeory;
use App\Models\Unit;
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
        $products = Product::with('productBrands.brands', 'productCertifications.certification', 'productCategory.categories', 'productSubCategory.subCategories')->where('status', '1')->latest()->get();
        // return $products;
        return view('admin.product.index', compact('products'));
    }
    public function productVariantIndex($id)
    {
        $units = Unit::all();
        $data = Product::where('status', '1')->with('productVaraint')->find($id);
        return view('admin.product.product_variant', compact('data', 'units'));
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
        return view('admin.product.create', compact('countries', 'categories', 'brands', 'models', 'certifications', 'companies'));
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
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            // 'country' => 'required|string|max:255',
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
                'short_description', 'long_description', 'status'
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

        $products = Product::with('productBrands.brands', 'productCertifications.certification', 'productCategorySubCategory.categories.subcategories')->where('status', '1')->find($id);
        //    return $products;
        return view('admin.product.edit', compact('products', 'countries'));
    }
    // Store and updtae Product Varaints
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
        $subadmin = Product::findOrFail($id);
        $subadmin->delete();
        return response()->json(['alert' => 'success', 'message' => 'Product Deleted SuccessFully!']);
    }
}
