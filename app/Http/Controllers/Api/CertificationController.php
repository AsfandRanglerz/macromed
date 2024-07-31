<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Http\Controllers\Controller;

class CertificationController extends Controller
{
    public function getCertification()
    {
        try {
            $certifications = Certification::where('status', '1')->latest()->get();
            if ($certifications->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Certifcation Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'certifications' => $certifications
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Certifcation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCertificationFilter($certificationId)
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
                'productCertifications' => function ($query) use ($certificationId) {
                    $query->where('certification_id', $certificationId);
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
                ->whereHas('productCertifications', function ($query) use ($certificationId) {
                    $query->where('certification_id', $certificationId);
                })
                ->latest()
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No Product Found For This Certification'
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
