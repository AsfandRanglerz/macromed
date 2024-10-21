<?php

namespace App\Http\Controllers\Api;

use App\Models\WhishList;
use Illuminate\Http\Request;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;

class WhishlistController extends Controller
{
    use ProductHelperTrait;
    public function getWhishList($userId)
    {
        try {
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;
            $wishListItems = WhishList::where('user_id', $userId)
                ->with([
                    'products' => function ($query) {
                        $query->select(
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
                        )
                            ->with([
                                'productBrands.brands:id,name',
                                'productCertifications.certification:id,name',
                                'productVaraint' => function ($query) {
                                    $query->select('product_id', 'selling_price_per_unit', 'status');
                                }
                            ]);
                    }
                ])
                ->get();

            // Check if the wishlist is empty
            if ($wishListItems->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No products found in the wishlist.',
                    'data' => []
                ], 200);
            }

            // Process each wishlist item to calculate prices and add details
            $wishListItems->each(function ($wishListItem) {
                if ($wishListItem->products) {
                    $product = $wishListItem->products;
                    $variantPrices = $product->productVaraint->pluck('selling_price_per_unit');

                    if ($variantPrices->isNotEmpty()) {
                        $product->min_price_range_pkr = $variantPrices->min();
                        $product->max_price_range_pkr = $variantPrices->max();
                        $product->variant_count = $product->productVaraint->count();
                    } else {
                        $product->min_price_range_pkr = null;
                        $product->max_price_range_pkr = null;
                        $product->variant_count = 0;
                    }
                    unset($product->productVaraint); // Optionally remove the variant data if not needed
                }
            });

            // Return the wishlist items with product details
            return response()->json([
                'status' => 'success',
                'data' => $wishListItems
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching wishlist data: ' . $e->getMessage()
            ], 500);
        }
    }
}
