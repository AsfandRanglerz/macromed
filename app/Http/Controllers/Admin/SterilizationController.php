<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Sterilization;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SterilizationController extends Controller
{
    public function sterilizationData()
    {
        $sterilizations = Sterilization::latest()->get();
        $json_data["data"] = $sterilizations;
        return json_encode($json_data);
    }

    public function sterilizationIndex()
    {
        $sterilizations = Sterilization::all();
        return view('admin.sterilization.index', compact('sterilizations'));
    }
    public function sterilizationCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('sterilizations')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $sterilization = new Sterilization($request->only(['name', 'status']));
            $sterilization->save();
            return response()->json(['alert' => 'success', 'message' => 'Sterilization Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Sterilization!' . $e->getMessage()], 500);
        }
    }

    public function showSterilization($id)
    {
        $sterilization = Sterilization::find($id);
        if (!$sterilization) {
            return response()->json(['alert' => 'error', 'message' => 'Sterilization Not Found'], 500);
        }
        return response()->json($sterilization);
    }
    public function updateSterilization(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sterilizations')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $sterilization = Sterilization::findOrFail($id);
            $sterilization->fill($request->only(['name', 'status']));
            $sterilization->save();
            return response()->json(['alert' => 'success', 'message' => 'Sterilization Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteSterilization($id)
    {
        $sterilization = Sterilization::findOrFail($id);
        $sterilization->delete();
        return response()->json(['alert' => 'success', 'message' => 'Sterilization Deleted SuccessFully!']);
    }
    public function updateSterilizationStatus($id)
    {
        try {
            $sterilization = Sterilization::findOrFail($id);
            if ($sterilization->status == '0') {
                $sterilization->status = '1';
                $message = 'Sterilization Active Successfully';
            } else if ($sterilization->status == '1') {
                $sterilization->status = '0';
                $message = 'Sterilization In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $sterilization->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
