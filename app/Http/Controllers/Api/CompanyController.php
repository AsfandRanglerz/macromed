<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    use ProductHelperTrait;

    public function getCategory()
    {
        try {

            $companies = Company::where('status', '1')->latest()->get();
            if ($companies->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Company Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'companies' => $companies
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching Company: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCompanyFilter(Request $request)
    {
        try {
            $company = $request->company;
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
                ->where('company', $company)
                ->latest()
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No Product Found For This Company'
                ], 404);
            } else {
                // Add converted prices to the products
                $products = $this->convertPrices($products, $pkrAmount);
                return response()->json([
                    'status' => 'success',
                    'products' => $products,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching company: ' . $e->getMessage()
            ], 500);
        }
    }
}
