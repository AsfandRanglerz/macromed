<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    use ProductHelperTrait;

    public function getCategory()
    {
        try {

            $categories = Category::where('status', '1')->latest()->get();
            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Category Not Found!'
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'categories' => $categories
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCategoryFilter($categoryId)
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
            )->where('status', '1')
                ->whereHas('productCategory', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                })
                ->latest()
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No Product Found For This Category'
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
                'message' => 'An error occurred while fetching category: ' . $e->getMessage()
            ], 500);
        }
    }
}
