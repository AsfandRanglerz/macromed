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

    // public function getFilteredProducts(Request $request)
    // {
    //     try {
    //         // Retrieve query parameters
    //         $minPrice = $request->input('min_price');
    //         $maxPrice = $request->input('max_price');
    //         $company = $request->input('company');
    //         $brandId = $request->input('brand_id');
    //         $categoryId = $request->input('category_id');
    //         $certificationId = $request->input('certification_id');
    //         $country = $request->input('country');
    //         $userId = $request->input('user_id');
    //         $productId = $request->input('product_id');
    //         $searchByWords = $request->input('key_words');
    //         $availability = $request->input('available_product');
    //         // Get currency and handle errors
    //         $currency = $this->getCurrency();
    //         if (!$currency) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'No Currency found.',
    //             ]);
    //         }
    //         $pkrAmount = $currency->pkr_amount;

    //         // Build the query
    //         $query = Product::with(
    //             'productBrands.brands:id,name',
    //             'productCertifications.certification:id,name'
    //         )->select(
    //             'id',
    //             'product_code',
    //             'thumbnail_image',
    //             'short_name',
    //             'country',
    //             'company',
    //             'models',
    //             'product_use_status',
    //             'short_description',
    //             'sterilizations',
    //             'min_price_range',
    //             'max_price_range'
    //         )->where('status', '1');

    //         // Apply filters only if provided
    //         if ($minPrice && $maxPrice) {
    //             $query->where(function ($query) use ($minPrice, $maxPrice) {
    //                 $query->where('min_price_range', '<=', $minPrice)
    //                     ->where('max_price_range', '>=', $maxPrice);
    //             })
    //                 ->orWhere(function ($query) use ($minPrice, $maxPrice) {
    //                     $query->where('min_price_range', '=', $minPrice)
    //                         ->where('max_price_range', '=', $maxPrice);
    //                 });
    //         }

    //         if ($company) {
    //             $query->where('company', $company);
    //         }

    //         if ($brandId) {
    //             $query->whereHas('productBrands', function ($q) use ($brandId) {
    //                 $q->where('brand_id', $brandId);
    //             });
    //         }

    //         if ($categoryId) {
    //             $query->whereHas('productCategory', function ($q) use ($categoryId) {
    //                 $q->where('category_id', $categoryId);
    //             });
    //         }

    //         if ($certificationId) {
    //             $query->whereHas('productCertifications', function ($q) use ($certificationId) {
    //                 $q->where('certification_id', $certificationId);
    //             });
    //         }

    //         if ($country) {
    //             $query->where('country', $country);
    //         }

    //         if ($searchByWords) {
    //             $query->where('short_name', 'like', '%' . $searchByWords . '%');
    //         }
    //         if ($availability) {
    //             // Ensure the product has at least one variant
    //             $query->whereHas('productVaraint'); // No need to check a specific column, just ensure existence
    //         }
    //         // Execute query and get results
    //         $products = $query->latest()->get();

    //         // Handle no products found
    //         if ($products->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'failed',
    //                 'data' => []
    //             ], 404);
    //         }

    //         // Convert prices
    //         $products = $this->convertPrices($products, $pkrAmount);

    //         // Fetch the favorite products based on user_id and product_id
    //         $favoriteProducts = WhishList::whereIn('product_id', $products->pluck('id'))->get();

    //         $likes = [];
    //         foreach ($products as $product) {
    //             // Populate likes array
    //             $productLikes = $favoriteProducts->where('product_id', $product->id)->pluck('user_id');
    //             $product->likes = $productLikes->isEmpty() ? [] : $productLikes->toArray();

    //             // Check if user_id and product_id are present in the request, save to WishList
    //             if ($userId && $productId && $productId == $product->id) {
    //                 $wishlist = WhishList::firstOrNew([
    //                     'user_id' => $userId,
    //                     'product_id' => $productId,
    //                 ]);
    //             }
    //         }
    //         return response()->json([
    //             'status' => 'success',
    //             'products' => $products,
    //             'pkrAmount' => $pkrAmount,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred while fetching products: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
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
            $query = Product::with(
                'productBrands.brands:id,name',
                'productCertifications.certification:id,name'
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

            // Apply the price filters

            if ($minPrice  && $maxPrice) {
                $query->whereHas('max_price_range', [$minPrice, $maxPrice]);
                if ($query->count() === 0) {
                    return response()->json([
                        'products' => [],
                    ]);
                }
            }

            // Apply other filters one by one, returning an empty array if any filter does not match
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

            // Execute query and get results
            $products = $query->latest()->get();

            // Handle no products found
            if ($products->isEmpty()) {
                return response()->json([
                    'products' => [],
                ], 404);
            }

            // Convert prices
            $products = $this->convertPrices($products, $pkrAmount);

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
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products: ' . $e->getMessage()
            ], 500);
        }
    }
}