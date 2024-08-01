<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductVaraint;
use ProductImage;

class ProductController extends Controller
{
    use ProductHelperTrait;
    public function getProducts()
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

            // Convert prices
            $products = $this->convertPrices($products, $pkrAmount);

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

            $currency = $this->getCurrency();
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
            }

            // Convert prices
            $products = $this->convertPrices($products, $pkrAmount);

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

    public function getFeaturedProduct()
    {
        try {
            $featureProducts = Product::select('id', 'thumbnail_image', 'short_name', 'short_description')->where('product_status', 'Featured Product')
                ->where('status', '1')->latest()->get();
            if ($featureProducts->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'success' => 'Feature Product Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    ' featureProducts' => $featureProducts
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching feature products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductdetail($productId)
    {
        try {
            $productDetails = Product::with([
                'productImages' => function ($query) {
                    $query->where('status', '0');
                },
                'productBrands.brands' => function ($query) {
                    $query->where('status', '1');
                },
                'productCertifications.certification' => function ($query) {
                    $query->where('status', '1');
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
                'sterilizations',
                'min_price_range',
                'max_price_range'
            )->where('status', '1')->where('id', $productId)->get();

            if ($productDetails->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Product Detail Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'productDetails' => $productDetails
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching product details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductVaraint($productId)
    {
        try {
            $productVaraints = ProductVaraint::where('product_id', $productId)->where('status', '1')->get();
            if ($productVaraints->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Product Detail Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'productVaraints' =>  $productVaraints
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching product varaints: ' . $e->getMessage()
            ], 500);
        }
    }
}
