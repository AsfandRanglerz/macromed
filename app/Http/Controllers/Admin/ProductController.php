<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Certification;
use App\Models\Company;
use App\Models\Models;

class ProductController extends Controller
{
    public function productIndex()
    {
        $categories = Category::all()->where('status', '1');
        return view('admin.product.index', compact('categories'));
    }
    public function productCreateIndex()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $countries = json_decode($response);

        // Decode the JSON response
        if ($countries == NULL) {
            $countries = [];
        }
        $categories = Category::where('status', '1')->get();
        $brands = Brands::where('status', '1')->get();
        $models = Models::where('status', '1')->get();
        $certifications = Certification::where('status', '1')->get();
        $companies = Company::where('status', '1')->get();
        return view('admin.product.create', compact('countries', 'categories', 'brands', 'models', 'certifications', 'companies'));
    }

    public function getSubCategories(Request $request)
    {
        $categoryIds = $request->category_ids;
        $subCategories = SubCategory::whereIn('category_id', $categoryIds)->get();
        return response()->json($subCategories);
    }
}
