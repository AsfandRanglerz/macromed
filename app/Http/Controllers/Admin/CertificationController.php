<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Certification;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CertificationController extends Controller
{
    public function certificationData()
    {
        $certifications = Certification::latest()->get();
        $json_data["data"] = $certifications;
        return json_encode($json_data);
    }

    public function certificationIndex()
    {
        $certifications = Certification::all();
        return view('admin.certification.index', compact('certifications'));
    }
    public function certificationCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('certifications')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $certification = new Certification($request->only(['name', 'status']));
            $certification->save();
            return response()->json(['alert' => 'success', 'message' => 'Certification Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Certification!' . $e->getMessage()], 500);
        }
    }

    public function showCertification($id)
    {
        $certification = Certification::find($id);
        if (!$certification) {
            return response()->json(['alert' => 'error', 'message' => 'Certification Not Found'], 500);
        }
        return response()->json($certification);
    }
    public function updateCertification(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('certifications')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $certification = Certification::findOrFail($id);
            $certification->fill($request->only(['name', 'status']));
            $certification->save();
            return response()->json(['alert' => 'success', 'message' => 'Certification Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();
        return response()->json(['alert' => 'success', 'message' => 'Certification Deleted SuccessFully!']);
    }
    public function updateCertificationStatus($id)
    {
        try {
            $certification = Certification::findOrFail($id);
            if ($certification->status == '0') {
                $certification->status = '1';
                $message = 'Certification Active Successfully';
            } else if ($certification->status == '1') {
                $certification->status = '0';
                $message = 'Certification In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $certification->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
