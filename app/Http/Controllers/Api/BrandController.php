<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;

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
}
