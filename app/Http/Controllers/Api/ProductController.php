<?php

namespace App\Http\Controllers\Api;




use App\Models\Product;
use App\Models\ProductVaraint;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;

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
                    'max_price_range',
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
                ->select('s_k_u', 'description', 'packing', 'unit', 'selling_price_per_unit')
                ->get()
                ->map(function ($variant) use ($pkrAmount) {
                    $variant->selling_price_per_unit_pkr = $variant->selling_price_per_unit * $pkrAmount;
                    return $variant;
                });

            if ($productVariants->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Product Variants Not Found!'
                ], 404);
            }

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


}