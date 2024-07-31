<?php

namespace App\Http\Controllers\Api;

use App\Models\Brands;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\ProductBrands;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function getBrand()
    {
        try {
            $brands = Brands::where('status', '1')->latest()->get();
            if ($brands->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Brand Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'brands' => $brands
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching brands: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBrandFilter($brandId)
    {
        try {
            $currency = Currency::first();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }

            $pkrAmount = $currency->pkr_amount;
            $products = Product::with([
                'productBrands' => function ($query) use ($brandId) {
                    $query->where('brand_id', $brandId);
                },
                'productBrands.brands',
                'productCertifications.certification'
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
                'sterilizations',
                'min_price_range',
                'max_price_range'
            )->where('status', '1')
                ->whereHas('productBrands', function ($query) use ($brandId) {
                    $query->where('brand_id', $brandId);
                })
                ->latest()
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No Product Found For This Brand'
                ], 404);
            } else {
                // Add converted prices to the products
                $products->transform(function ($product) use ($pkrAmount) {
                    $product->min_price_range_pkr = $product->min_price_range * $pkrAmount;
                    $product->max_price_range_pkr = $product->max_price_range * $pkrAmount;
                    return $product;
                });
                return response()->json([
                    'status' => 'success',
                    'products' => $products,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products: ' . $e->getMessage()
            ], 500);
        }
    }
}
