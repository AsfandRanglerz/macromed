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
use App\Models\SilderImages;
use App\Models\WhishList;

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
            $featureProducts = Product::select('id', 'thumbnail_image', 'short_name', 'short_description')->where('product_status', 'Featured Product')
                ->where('status', '1')->latest()->get();
            $silders = SilderImages::where('status', '0')->select('id', 'images')->latest()->get();
            if ($featureProducts->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'success' => 'Feature Product Not Found!'
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => [
                    'silders' => $silders,
                    'countries_of_manufacture' => $countryOfManufacture,
                    'categories' => $categories,
                    'brands' => $brands,
                    'certifications' => $certifications,
                    'companies' => $company,
                    'featureProducts' => $featureProducts,
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
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $companies = $request->input('company') ? explode(',', $request->input('company')) : [];
            $brandIds = $request->input('brand_id') ? explode(',', $request->input('brand_id')) : [];
            $categoryIds = $request->input('category_id') ? explode(',', $request->input('category_id')) : [];
            $certificationIds = $request->input('certification_id') ? explode(',', $request->input('certification_id')) : [];
            $countries = $request->input('country') ? explode(',', $request->input('country')) : [];
            $userId = $request->input('user_id');
            $productId = $request->input('product_id');
            $searchByWords = $request->input('key_words');
            $availability = $request->input('available_product');
            $suggestedWords = $request->input('suggested_word');
            $priceOrder = $request->input('price_order');
            $page = $request->input('page', 1);
            $perPage = 6;
            $offset = ($page - 1) * $perPage;

            // Get currency and handle errors
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;
            if ($searchByWords) {
                $suggestedWordsList = Product::selectRaw('DISTINCT(short_name)')
                    ->where('short_name', 'like', '%' . $searchByWords . '%')
                    ->pluck('short_name')
                    ->toArray();
                return response()->json([
                    'status' => 'success',
                    'suggested_words' => $suggestedWordsList
                ]);
            }
            // Start the query to get products with relationships
            $query = Product::with([
                'productBrands.brands:id,name',
                'productCertifications.certification:id,name',
                'productVaraint' => function ($query) {
                    $query->select('product_id', 'selling_price_per_unit');
                }
            ])->select(
                'id',
                'product_code',
                'thumbnail_image',
                'short_name',
                'country',
                'company',
                'models',
                'product_use_status',
                'short_description',
                'sterilizations'
            )->where('status', '1');

            // Apply the price filters using variants' prices
            if ($minPrice && $maxPrice) {
                $query->whereHas('productVaraint', function ($variantPrice) use ($minPrice, $maxPrice) {
                    $variantPrice->whereBetween('selling_price_per_unit', [$minPrice, $maxPrice]);
                });
            }
            if (!empty($companies)) {
                $query->whereIn('company', $companies);
            }
            if (!empty($brandIds)) {
                $query->whereHas('productBrands', function ($q) use ($brandIds) {
                    $q->whereIn('brand_id', $brandIds);
                });
            }
            if (!empty($categoryIds)) {
                $query->whereHas('productCategory', function ($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds);
                });
            }
            if (!empty($certificationIds)) {
                $query->whereHas('productCertifications', function ($q) use ($certificationIds) {
                    $q->whereIn('certification_id', $certificationIds);
                });
            }
            if (!empty($countries)) {
                $query->whereIn('country', $countries);
            }
            if ($suggestedWords) {
                $query->where('short_name', 'like', '%' . $suggestedWords . '%');
            }
            if ($availability) {
                $query->whereHas('productVaraint');
            }
            $totalProducts = $query->count();
            $products = $query->latest()
                ->skip($offset)
                ->take($perPage)
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'products' => [],
                    'filter_counts' => [],
                ]);
            }
            $products->each(function ($product) use ($pkrAmount) {
                $variantPrices = $product->productVaraint->pluck('selling_price_per_unit')->map(function ($price) use ($pkrAmount) {
                    return $price * $pkrAmount;
                });
                if ($variantPrices->count() == 1) {
                    $product->min_price_range_pkr = $variantPrices->first();
                } else {
                    // Set min and max prices for the product
                    $product->min_price_range_pkr = $variantPrices->min();
                    $product->max_price_range_pkr = $variantPrices->max();
                }
                $product->variant_count = $product->productVaraint->count();
                // Detach the variants to exclude them from the response
                unset($product->productVaraint);
            });

            // Fetch the favorite products based on user_id and product_id
            $favoriteProducts = WhishList::whereIn('product_id', $products->pluck('id'))->get();

            foreach ($products as $product) {
                // Populate likes array
                $productLikes = $favoriteProducts->where('product_id', $product->id)->pluck('user_id');
                $product->likes = $productLikes->isEmpty() ? [] : $productLikes->toArray();

                // Check if user_id and product_id are present in the request, save to WishList
                if ($userId && $productId && $productId == $product->id) {
                    $wishlist = WhishList::firstOrNew([
                        'user_id' => $userId,
                        'product_id' => $productId,
                    ]);
                    $wishlist->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'products' => $products,
                'pkrAmount' => $pkrAmount,
                'totalProducts' => $totalProducts,
                'filter_counts' => $this->getFilterCounts(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getFilterCounts()
    {
        return [
            'countries' => Product::selectRaw('country, COUNT(*) as count')->groupBy('country')->pluck('count', 'country')->toArray(),
            'brands' => Product::with('productBrands')
                ->selectRaw('product_brands.brand_id, COUNT(*) as count')
                ->join('product_brands', 'products.id', '=', 'product_brands.product_id')
                ->groupBy('product_brands.brand_id')
                ->pluck('count', 'product_brands.brand_id')
                ->toArray(),
            'categories' => Product::with('productCategory')
                ->selectRaw('product_catgeories.category_id, COUNT(*) as count')
                ->join('product_catgeories', 'products.id', '=', 'product_catgeories.product_id')
                ->groupBy('product_catgeories.category_id')
                ->pluck('count', 'product_catgeories.category_id')
                ->toArray(),
            'certifications' => Product::with('productCertifications')
                ->selectRaw('product_certifcations.certification_id, COUNT(*) as count')
                ->join('product_certifcations', 'products.id', '=', 'product_certifcations.product_id')
                ->groupBy('product_certifcations.certification_id')
                ->pluck('count', 'product_certifcations.certification_id')
                ->toArray()
        ];
    }
}
