<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
        $companies = Company::all();
        return view('admin.company.index', compact('companies'));
    }
    public function companyCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('companies')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $compnay = new Company($request->only(['name', 'status']));
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
    public function updateCompany(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $compnay = Company::findOrFail($id);
            $compnay->fill($request->only(['name', 'status']));
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
