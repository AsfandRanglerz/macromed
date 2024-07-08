<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Certification;
use App\Models\Company;
use App\Models\Models;
use App\Models\Product;
use App\Models\ProductVaraint;

class ProductController extends Controller
{
    public function productIndex()
    {
        $categories = Category::all()->where('status', '1');
        return view('admin.product.index', compact('categories'));
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
            'country' => 'required|string|max:255',
            'product_commission' => 'required|string|max:255',
            'video_link' => 'nullable|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
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

            foreach ($product->id as $productId) {
                $productVariant = new ProductVaraint();
                $productVariant->product_id = $productId;
                $productVariant->category_id = $request->input('category_id');
                $productVariant->sub_category_id = $request->input('sub_category_id');
                $productVariant->brand_id = $request->input('brand_id');
                $productVariant->certification_id = $request->input('certification_id');
                $productVariant->save();
            }

            return redirect()->route('product.index')->with('message', 'Product created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
