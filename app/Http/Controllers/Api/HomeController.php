<?php

namespace App\Http\Controllers\Api;

use App\Models\Brands;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    use ProductHelperTrait;
    public function getDropDownData()
    {
        try {
            $countryOfManufacture = Product::where('status', '1')->select('country')->distinct()->get();
            $categories = Category::where('status', '1')->select('id', 'name')->get();
            $brands = Brands::where('status', '1')->select('id', 'name')->get();
            $certifications = Certification::where('status', '1')->select('id', 'name')->get();
            $company = Company::where('status', '1')->select('id', 'name')->get();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'countries_of_manufacture' => $countryOfManufacture,
                    'categories' => $categories,
                    'brands' => $brands,
                    'certifications' => $certifications,
                    'companies' => $company
                ]
            ], 200);
        } catch (\Exception $e) {
            // Handling errors
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredProducts(Request $request)
    {
        try {
            // Retrieve query parameters
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $company = $request->input('company');
            $brandId = $request->input('brand_id');
            $categoryId = $request->input('category_id');
            $certificationId = $request->input('certification_id');
            $country = $request->input('country');
            // Get currency and handle errors
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;
            // Build the query
            $query = Product::with(
                'productBrands.brands:id,name',
                'productCertifications.certification:id,name',
            )->select(
                'id',
                'product_code',
                'thumbnail_image',
                'short_name',
                'country',
                'company',
                'models',
                'product_use_status',
                'short_description',
                'sterilizations',
                'min_price_range',
                'max_price_range'
            )->where('status', '1');

            // Apply filters only if provided
            if ($minPrice && $maxPrice) {
                $query->where(function ($query) use ($minPrice, $maxPrice) {
                    $query->whereBetween('min_price_range', [$minPrice, $maxPrice])
                        ->orWhereBetween('max_price_range', [$minPrice, $maxPrice]);
                });
            }

            if ($company) {
                $query->where('company', $company);
            }

            if ($brandId) {
                $query->whereHas('productBrands', function ($q) use ($brandId) {
                    $q->where('brand_id', $brandId);
                });
            }

            if ($categoryId) {
                $query->whereHas('productCategory', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }

            if ($certificationId) {
                $query->whereHas('productCertifications', function ($q) use ($certificationId) {
                    $q->where('certification_id', $certificationId);
                });
            }

            if ($country) {
                $query->where('country', $country);
            }

            // Execute query and get results
            $products = $query->latest()->get();

            // Handle no products found
            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No products found for the given filters'
                ]);
            }
            // Convert prices
            $products = $this->convertPrices($products, $pkrAmount);

            // Return the response
            return response()->json([
                'status' => 'success',
                'products' => $products,
                'pkrAmount' => $pkrAmount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFeaturedProduct()
    {
        try {
            $featureProducts = Product::select('id', 'thumbnail_image', 'short_name', 'short_description')->where('product_status', 'Featured Product')
                ->where('status', '1')->latest()->get();
            if ($featureProducts->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'success' => 'Feature Product Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    ' featureProducts' => $featureProducts
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching feature products: ' . $e->getMessage()
            ], 500);
        }
    }
}