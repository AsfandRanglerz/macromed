<?php

namespace App\Http\Controllers\Admin;

use App\Models\MainMaterial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MainMaterialController extends Controller
{
    public function mainMaterialData()
    {
        $mainMaterial = MainMaterial::latest()->get();
        $json_data["data"] = $mainMaterial;
        return json_encode($json_data);
    }

    public function MainMaterialIndex()
    {
        $mainMaterial = MainMaterial::all();
        return view('admin.mianmaterial.index', compact('mainMaterial'));
    }
    public function mainMaterialCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('main_materials')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $mainMaterial = new MainMaterial($request->only(['name', 'status']));
            $mainMaterial->save();
            return response()->json(['alert' => 'success', 'message' => 'MainMaterial Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating MainMaterial!' . $e->getMessage()], 500);
        }
    }

    public function showMainMaterial($id)
    {
        $mainMaterial = MainMaterial::find($id);
        if (!$mainMaterial) {
            return response()->json(['alert' => 'error', 'message' => 'MainMaterial Not Found'], 500);
        }
        return response()->json($mainMaterial);
    }
    public function updateMainMaterial(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('main_materials')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $mainMaterial = MainMaterial::findOrFail($id);
            $mainMaterial->fill($request->only(['name', 'status']));
            $mainMaterial->save();
            return response()->json(['alert' => 'success', 'message' => 'Main Material Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteMainMaterial($id)
    {
        $mainMaterial = MainMaterial::findOrFail($id);
        $mainMaterial->delete();
        return response()->json(['alert' => 'success', 'message' => 'Main Material Deleted SuccessFully!']);
    }
    public function updateMainMaterialStatus($id)
    {
        try {
            $mainMaterial = MainMaterial::findOrFail($id);
            if ($mainMaterial->status == '0') {
                $mainMaterial->status = '1';
                $message = 'Main Material Active Successfully';
            } else if ($mainMaterial->status == '1') {
                $mainMaterial->status = '0';
                $message = 'Main Material In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $mainMaterial->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
