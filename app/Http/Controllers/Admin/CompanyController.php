<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Traits\CountryApiRequestTrait;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends BaseController
{
    use CountryApiRequestTrait;

    protected $model = Company::class;
    protected $keys = ['name', 'status', 'contact_detail', 'country', 'state', 'city', 'zip', 'website'];
    protected $formRequestClass = CompanyCreateRequest::class;


    public function companyData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $companies = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $companies], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function companyIndex()
    {
        $countries = $this->fetchApiData('https://api.countrystatecity.in/v1/countries');
        if (isset($countries['error'])) {
            $countries = [];
        }
        $companies = Company::all();
        return view('admin.company.index', compact('companies', 'countries'));
    }

    public function showCompany($id)
    {
        $compnay = $this->model::findOrFail($id);
        if (!$compnay) {
            return response()->json(['alert' => 'error', 'message' => 'Company Not Found'], 500);
        }
        return response()->json($compnay);
    }


}
