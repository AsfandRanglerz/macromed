<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SupplierCreateRequest;
use App\Http\Requests\SupplierUpdateRequest;

class SupplierController extends Controller
{
    // Genarte Supplier Id
    private function generateUniqueSupplierId()
    {
        do {
            $supplier_id = $this->generateRandomSupplierId();
        } while (Supplier::where('supplier_id', $supplier_id)->exists());

        return $supplier_id;
    }

    private function generateRandomSupplierId($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $supplier_id = '';

        for ($i = 0; $i < $length; $i++) {
            $supplier_id .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $supplier_id;
    }
    // Code end
    public function supplierData()
    {
        $suppliers = Supplier::latest()->get();
        $json_data["data"] = $suppliers;
        return json_encode($json_data);
    }

    public function supplierIndex()
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
        $suppliers = Supplier::all();
        return view('admin.supplier.index', compact('suppliers', 'countries'));
    }
    public function fetchSupplierStates(Request $request)
    {
        $countryCode = $request->input('country_code');

        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
                ),
            ));
            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('Error occurred while fetching states: ' . curl_error($curl));
            }

            curl_close($curl);
            $states = json_decode($response);

            return response()->json($states);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchSupplierCities(Request $request)
    {
        $stateCode = $request->input('state_code');
        $countryCode = $request->input('country_code');
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.countrystatecity.in/v1/countries/' . $countryCode . '/states/' . $stateCode . '/cities',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'X-CSCAPI-KEY: TExJVmdYa1pFcWFsRWViS0c3dDRRdTdFV3hnWXJveFhQaHoyWVo3Mw=='
                ),
            ));
            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('Error occurred while fetching cities: ' . curl_error($curl));
            }

            curl_close($curl);
            $cities = json_decode($response);

            return response()->json($cities);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function supplierCreate(SupplierCreateRequest $request)
    {
        try {
            $supplier = new Supplier($request->only(['name', 'email', 'website', 'phone_number', 'poc', 'whats_app', 'address', 'alternate_phone_number', 'alternate_email', 'country', 'state', 'city', 'status']));
            $supplier->supplier_id = $this->generateUniqueSupplierId();
            $supplier->save();
            return response()->json(['alert' => 'success', 'message' => 'Number Of Uses Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Suppliers!' . $e->getMessage()], 500);
        }
    }
    public function showSupplier($id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response()->json(['alert' => 'error', 'message' => 'Suppliers Not Found'], 500);
        }
        return response()->json($supplier);
    }
    public function updateSupplier(SupplierUpdateRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->fill($request->only(['name', 'email', 'website', 'phone_number', 'poc', 'whats_app', 'address', 'alternate_phone_number', 'alternate_email', 'country', 'state', 'city', 'status']));
            $supplier->save();
            return response()->json(['alert' => 'success', 'message' => 'Suppliers Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['alert' => 'success', 'message' => 'Suppliers Deleted SuccessFully!']);
    }
    public function updateSupplierStatus($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            if ($supplier->status == '0') {
                $supplier->status = '1';
                $message = 'Supplier Active Successfully';
            } else if ($supplier->status == '1') {
                $supplier->status = '0';
                $message = 'Supplier In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $supplier->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
