<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function unitsData()
    {
        $units = Unit::latest()->get();
        $json_data["data"] = $units;
        return json_encode($json_data);
    }

    public function unitsIndex()
    {
        $units = Unit::all();
        return view('admin.unit.index', compact('units'));
    }
    public function unitsCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('units')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $unit = new Unit($request->only(['name', 'status']));
            $unit->save();
            return response()->json(['alert' => 'success', 'message' => 'Units Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Units!' . $e->getMessage()], 500);
        }
    }

    public function showUnits($id)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json(['alert' => 'error', 'message' => 'Units Not Found'], 500);
        }
        return response()->json($unit);
    }
    public function updateUnits(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $unit = Unit::findOrFail($id);
            $unit->fill($request->only(['name', 'status']));
            $unit->save();
            return response()->json(['alert' => 'success', 'message' => 'Units Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteUnits($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(['alert' => 'success', 'message' => 'Units Deleted SuccessFully!']);
    }
    public function updateUnitsStatus($id)
    {
        try {
            $unit = Unit::findOrFail($id);
            if ($unit->status == '0') {
                $unit->status = '1';
                $message = 'Units Active Successfully';
            } else if ($unit->status == '1') {
                $unit->status = '0';
                $message = 'Units In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $unit->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
