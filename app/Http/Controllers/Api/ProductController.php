<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function getProducts()
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
            $products = Product::with(
                'productBrands.brands',
                'productCertifications.certification'
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
            )->where('status', '1')->latest()->get();
            // Add converted prices to the products
            $products->transform(function ($product) use ($pkrAmount) {
                $product->min_price_range_pkr = $product->min_price_range * $pkrAmount;
                $product->max_price_range_pkr = $product->max_price_range * $pkrAmount;
                return $product;
            });
            return response()->json([
                'status' => 'success',
                'products' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductByRange(Request $request)
    {
        try {
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');

            if (!$minPrice || !$maxPrice) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Both min price and max price are required'
                ], 400);
            }
            $currency = Currency::first();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;
            $products = Product::with(
                'productBrands.brands',
                'productCertifications.certification'
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
            )->where('status', '1')
                ->where(function ($query) use ($minPrice, $maxPrice) {
                    $query->whereBetween('min_price_range', [$minPrice, $maxPrice])
                        ->orWhereBetween('max_price_range', [$minPrice, $maxPrice]);
                })
                ->latest()
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No Product Found In This Range'
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
                    'pkrAmount' => $pkrAmount,
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
