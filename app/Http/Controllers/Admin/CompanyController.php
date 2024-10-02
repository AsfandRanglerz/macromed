<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{
    public function companyData()
    {
        $companies = Company::latest()->get();
        $json_data["data"] = $companies;
        return json_encode($json_data);
    }

    public function companyIndex()
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
        $companies = Company::all();
        return view('admin.company.index', compact('companies', 'countries'));
    }
    public function fetchCompanyStates(Request $request)
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

    public function fetchCompanyCities(Request $request)
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

    public function companyCreate(CompanyCreateRequest $request)
    {
        try {
            $compnay = new Company($request->only(['name', 'status', 'contact_detail', 'country', 'state', 'city', 'zip', 'website']));
            $compnay->save();
            return response()->json(['alert' => 'success', 'message' => 'Company Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Company!' . $e->getMessage()], 500);
        }
    }

    public function showCompany($id)
    {
        $compnay = Company::find($id);
        if (!$compnay) {
            return response()->json(['alert' => 'error', 'message' => 'Company Not Found'], 500);
        }
        return response()->json($compnay);
    }
    public function updateCompany(CompanyUpdateRequest $request, $id)
    {
        try {
            $compnay = Company::findOrFail($id);
            $compnay->fill($request->only(['name', 'status', 'contact_detail', 'country', 'state', 'city', 'zip', 'website']));
            $compnay->save();
            return response()->json(['alert' => 'success', 'message' => 'Company Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteCompany($id)
    {
        $compnay = Company::findOrFail($id);
        $compnay->delete();
        return response()->json(['alert' => 'success', 'message' => 'Company Deleted SuccessFully!']);
    }
    public function updateCompanyStatus($id)
    {
        try {
            $compnay = Company::findOrFail($id);
            if ($compnay->status == '0') {
                $compnay->status = '1';
                $message = 'Company Active Successfully';
            } else if ($compnay->status == '1') {
                $compnay->status = '0';
                $message = 'Company In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $compnay->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
