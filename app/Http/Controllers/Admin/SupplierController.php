<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Exception;
use App\Models\Supplier;
use Illuminate\Http\Request;

use App\Traits\CountryApiRequestTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SupplierUpdateRequest;

class SupplierController extends BaseController
{
    use CountryApiRequestTrait;

    protected $model = Supplier::class;
    protected $keys = ['name', 'email', 'website', 'phone_number', 'poc', 'whats_app', 'address', 'alternate_phone_number', 'alternate_email', 'country', 'state', 'city', 'status', 'is_draft'];
    protected $formRequestClass = SupplierUpdateRequest::class;

    public function supplierData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $suppliers = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $suppliers], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function supplierIndex()
    {
        $countries = $this->fetchApiData('https://api.countrystatecity.in/v1/countries');
        if (isset($countries['error'])) {
            $countries = [];
        }
        $suppliers = Supplier::all();
        return view('admin.supplier.index', compact('suppliers', 'countries'));
    }

    public function showSupplier($id)
    {
        $supplier = $this->model::findOrFail($id);
        if (!$supplier) {
            return response()->json(['alert' => 'error', 'message' => 'Suppliers Not Found'], 500);
        }
        return response()->json($supplier);
    }
}
