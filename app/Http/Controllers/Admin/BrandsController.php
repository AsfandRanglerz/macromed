<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBrandRequest;
use App\Traits\CountryApiRequestTrait;


class BrandsController extends Controller
{
    use CountryApiRequestTrait;

    protected $model = Brands::class;
    protected $keys = ['name', 'slug', 'status', 'owner', 'company', 'company_country', 'contact_detail'];
    protected $formRequestClass = CreateBrandRequest::class;


    public function brandsData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $brands = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $brands], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function brandsIndex()
    {
        $countries = $this->fetchApiData('https://api.countrystatecity.in/v1/countries');
        if (isset($countries['error'])) {
            $countries = [];
        }
        $brands = Brands::all();
        return view('admin.brands.index', compact('brands', 'countries'));
    }


    public function showBrands($id)
    {
        $brand = $this->model::findOrFail($id);
        if (!$brand) {
            return response()->json(['alert' => 'error', 'message' => 'Brands Not Found'], 500);
        }
        return response()->json($brand);
    }
}
