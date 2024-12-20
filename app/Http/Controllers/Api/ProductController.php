<?php

namespace App\Http\Controllers\Api;




use App\Models\Brands;
use App\Models\Product;
use App\Models\Category;
use GuzzleHttp\Psr7\Request;
use App\Models\ProductVaraint;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompareRequest;

class ProductController extends Controller
{
    use ProductHelperTrait;
    protected function getDiscountMessage($discounts)
    {
        foreach ($discounts as $discount) {
            if ($discount->discount_expiration_status === 'active') {
                $now = now();
                $remainingTime = $discount->end_date->diff($now);
                return [
                    'percentage' => $discount->discount_percentage,
                    'message' => "{$discount->discount_percentage}% discount for {$remainingTime->d} days {$remainingTime->h} hours {$remainingTime->i} minutes remaining!"
                ];
            }
        }
        return null;
    }
    public function getProductDetail($productId)
    {
        try {
            $currency = $this->getCurrency();
            $pkrAmount = $currency->pkr_amount;
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
                    'tab_4_text',
                    'pdf'
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
                ->where('is_draft', '1')
                ->select('id', 's_k_u', 'description', 'packing', 'unit', 'remaining_quantity', 'selling_price_per_unit', 'tooltip_information')
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

            $relatedProducts = Product::with([
                'productBrands.brands:id,name',
                'productCategory.categories:id,name',
                'productCertifications.certification:id,name'
            ])
                ->select('id', 'thumbnail_image', 'short_name', 'short_description')
                ->where('status', '1')
                ->where('id', '!=', $productId)
                ->whereHas('productCategory', fn($query) => $query->whereIn('category_id', $categoryIds))
                ->latest()
                ->get()
                ->map(function ($relatedProduct) {
                    $discountMessage = $this->getDiscountMessage($relatedProduct->discounts);
                    $relatedProduct->discount_percentage = $discountMessage['percentage'] ?? null;
                    $relatedProduct->discount_message = $discountMessage['message'] ?? null;
                    return $relatedProduct;
                });

            $discountMessage = $this->getDiscountMessage($productDetails->discounts);
            $productDetails->discount_percentage = $discountMessage ? $discountMessage['percentage'] : null;
            $productDetails->discount_message = $discountMessage ? $discountMessage['message'] : null;
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

    public function getCategoryBrand()
    {
        try {
            $categories = Category::with('discounts')->where('status', '1')->select('id', 'name')->get();
            $brands = Brands::with('discounts')->where('status', '1')->select('id', 'name')->get();
            $categories = $categories->map(function ($category) {
                $discountMessage = $this->getDiscountMessage($category->discounts);
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'discount_percentage' => $discountMessage ? $discountMessage['percentage'] : null,
                    'discount_message' => $discountMessage ? $discountMessage['message'] : null,
                ];
            });
            $brands = $brands->map(function ($brand) {
                $discountMessage = $this->getDiscountMessage($brand->discounts);
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'discount_percentage' => $discountMessage ? $discountMessage['percentage'] : null,
                    'discount_message' => $discountMessage ? $discountMessage['message'] : null,
                ];
            });
            return response()->json([
                'status' => 'success',
                'categories' => $categories,
                'brands' => $brands,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching product details: ' . $e->getMessage(),
            ], 500);
        }
    }
}
