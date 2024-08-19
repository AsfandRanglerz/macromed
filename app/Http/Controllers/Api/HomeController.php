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
            // Retrieve query parameters
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $company = $request->input('company');
            $brandId = $request->input('brand_id');
            $categoryId = $request->input('category_id');
            $certificationId = $request->input('certification_id');
            $country = $request->input('country');
            $userId = $request->input('user_id');
            $productId = $request->input('product_id');
            $searchByWords = $request->input('key_words');
            $availability = $request->input('available_product');
            // $page = $request->input('page', 6);
            // $perPage = 6;
            // $offset = ($page - 1) * $perPage;
            // Get currency and handle errors
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;

            // Build the initial query
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
            if ($minPrice  && $maxPrice) {
                $query->whereHas('productVaraint', function ($variantPrice) use ($minPrice, $maxPrice) {
                    $variantPrice->whereBetween('selling_price_per_unit', [$minPrice, $maxPrice]);
                });
            }
            // if ($minPrice) {
            //     $query->whereHas('productVaraint', function ($variantPrice) use ($minPrice) {
            //         $variantPrice->where('selling_price_per_unit', '>=', $minPrice);
            //     });
            // }
            // if ($maxPrice) {
            //     $query->whereHas('productVaraint', function ($variantPrice) use ($maxPrice) {
            //         $variantPrice->where('selling_price_per_unit', '>=', $maxPrice);
            //     });
            // }

            // Apply other filters as before
            if ($company) {
                $query->where('company', $company);
                if ($query->count() === 0) {
                    return response()->json([
                        'products' => [],
                    ]);
                }
            }
            if ($brandId) {
                $query->whereHas('productBrands', function ($q) use ($brandId) {
                    $q->where('brand_id', $brandId);
                });
                if ($query->count() === 0) {
                    return response()->json([
                        'products' => [],
                    ]);
                }
            }
            if ($categoryId) {
                $query->whereHas('productCategory', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
                if ($query->count() === 0) {
                    return response()->json([
                        'products' => [],
                    ]);
                }
            }
            if ($certificationId) {
                $query->whereHas('productCertifications', function ($q) use ($certificationId) {
                    $q->where('certification_id', $certificationId);
                });
                if ($query->count() === 0) {
                    return response()->json([
                        'products' => [],
                    ]);
                }
            }
            if ($country) {
                $query->where('country', $country);
                if ($query->count() === 0) {
                    return response()->json([
                        'products' => [],
                    ]);
                }
            }
            if ($searchByWords) {
                $query->where('short_name', 'like', '%' . $searchByWords . '%');
                if ($query->count() === 0) {
                    return response()->json([
                        'products' => [],
                    ]);
                }
            }
            if ($availability) {
                $query->whereHas('productVaraint');
            }
            $totalProducts = $query->count();
            // Execute query and get results
            $products = $query->latest()
                // ->skip($offset)
                // ->take($perPage)
                ->get();

            // Handle no products found
            if ($products->isEmpty()) {
                return response()->json([
                    'products' => [],
                ]);
            }
            // Calculate min/max prices for variants in PKR and adjust for single price
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
                'totalProducts'=>$totalProducts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products: ' . $e->getMessage()
            ], 500);
        }
    }
}