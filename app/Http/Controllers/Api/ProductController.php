<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        try {
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
                return response()->json([
                    'status' => 'success',
                    'products' => $products
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
