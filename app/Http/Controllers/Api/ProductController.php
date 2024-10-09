<?php

namespace App\Http\Controllers\Api;




use App\Models\Product;
use App\Models\ProductVaraint;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompareRequest;
use GuzzleHttp\Psr7\Request;

class ProductController extends Controller
{
    use ProductHelperTrait;

    public function getProductDetail($productId)
    {
        try {
            $currency = $this->getCurrency();
            $pkrAmount = $currency->pkr_amount;

            // Fetch product details with related data in a single query
            $productDetails = Product::with([
                'productImages' => function ($query) {
                    $query->where('status', '0')->select('id', 'image', 'product_id');
                },
                'productBrands.brands' => function ($query) {
                    $query->where('status', '1')->select('id', 'name');
                },
                'productCertifications.certification' => function ($query) {
                    $query->where('status', '1')->select('id', 'name');
                },
            ])
                ->where('status', '1')
                ->where('id', $productId)
                ->select(
                    'id',
                    'product_hts',
                    'product_name',
                    'product_code',
                    'thumbnail_image',
                    'short_name',
                    'country',
                    'company',
                    'models',
                    'product_use_status',
                    'sterilizations',
                    'tab_1_heading',
                    'tab_2_heading',
                    'tab_3_heading',
                    'tab_4_heading',
                    'tab_1_text',
                    'tab_2_text',
                    'tab_3_text',
                    'tab_4_text'
                )
                ->first();

            if (!$productDetails) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Product Detail Not Found!'
                ], 404);
            }

            // Fetch product variants and calculate the selling price in PKR
            $productVariants = ProductVaraint::where('product_id', $productId)
                ->where('status', '1')
                ->select('id','s_k_u', 'description', 'packing', 'unit', 'remaining_quantity', 'selling_price_per_unit', 'tooltip_information')
                ->get()
                ->map(function ($variant) use ($pkrAmount) {
                    $variant->selling_price_per_unit_pkr = $variant->selling_price_per_unit * $pkrAmount;
                    return $variant;
                });


            // Extract category IDs for the product
            $categoryIds = $productDetails->productCategory->pluck('category_id')->toArray();
            if (empty($categoryIds)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No categories found for this product!'
                ], 404);
            }

            // Find related products based on category IDs
            $relatedProducts = Product::select('id', 'thumbnail_image', 'short_name', 'short_description')
                ->where('status', '1')
                ->where('id', '!=', $productId)
                ->whereHas('productCategory', function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds);
                })
                ->latest()
                ->get();

            // It's not necessary to return an error if no related products are found
            // as it's optional data. Instead, return an empty array.
            return response()->json([
                'status' => 'success',
                'productDetails' => $productDetails,
                'productVariants' => $productVariants,
                'relatedProducts' => $relatedProducts,
                'pkrAmount' => $pkrAmount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching product details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function productComparison(CompareRequest $request)
    {
        try {
            // Retrieve product_ids from query parameters
            $productIds = $request->query('product_ids');
            if (!$productIds) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Product IDs are required.',
                ], 400);
            }

            $productIdsArray = explode(',', $productIds); // Convert string to array
            $productCount = count($productIdsArray);



            if ($productCount > 3) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'You can only compare up to 3 products!',
                ], 400);
            }


            // Fetch products and their related data
            $currency = $this->getCurrency();
            $pkrAmount = $currency->pkr_amount;

            $comparisons = Product::with([
                'productBrands.brands' => function ($query) {
                    $query->where('status', '1')->select('id', 'name');
                },
                'productCertifications.certification' => function ($query) {
                    $query->where('status', '1')->select('id', 'name');
                },
                'productVaraint' => function ($query) {
                    $query->where('status', '1')->select('product_id', 'selling_price_per_unit', 'remaining_quantity');
                }
            ])
                ->where('status', '1')
                ->whereIn('id', $productIdsArray)
                ->select(
                    'id',
                    'thumbnail_image',
                    'short_name',
                    'country',
                    'company',
                    'product_use_status',
                    'sterilizations'
                )
                ->get();

            if ($comparisons->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No products found for the provided IDs!',
                ], 404);
            }

            // Prepare the comparison data
            $comparisonData = $comparisons->map(function ($product) use ($pkrAmount) {
                $variantPrices = $product->productVaraint->pluck('selling_price_per_unit')->map(function ($price) use ($pkrAmount) {
                    return $price * $pkrAmount;
                });

                $pricePkr = ($variantPrices->count() == 1) ? $variantPrices->first() : null;
                $minPricePkr = ($variantPrices->count() > 1) ? $variantPrices->min() : null;
                $maxPricePkr = ($variantPrices->count() > 1) ? $variantPrices->max() : null;

                // Get the remaining quantity for each variant
                $remainingQuantities = $product->productVaraint->pluck('remaining_quantity');

                return [
                    'id' => $product->id,
                    'thumbnail_image' => $product->thumbnail_image,
                    'short_name' => $product->short_name,
                    'country' => $product->country,
                    'company' => $product->company,
                    'product_use_status' => $product->product_use_status,
                    'sterilizations' => $product->sterilizations,
                    'brands' => $product->productBrands->pluck('brands.name'),
                    'certifications' => $product->productCertifications->pluck('certification.name'),
                    'price' => $pricePkr,
                    'min_price_range_pkr' => $minPricePkr,
                    'max_price_range_pkr' => $maxPricePkr,
                    'variant_count' => $variantPrices->count(),
                    'remaining_quantities' => $remainingQuantities // Include remaining quantities
                ];
            });

            return response()->json([
                'status' => 'success',
                'comparison' => $comparisonData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching product details: ' . $e->getMessage(),
            ], 500);
        }
    }
}
