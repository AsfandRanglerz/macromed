<?php

namespace App\Http\Controllers\Admin;

use App\Models\NumberOfUse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NumberOfUseController extends Controller
{
    public function numberOfUseData()
    {
        $numberOfUses = NumberOfUse::latest()->get();
        $json_data["data"] = $numberOfUses;
        return json_encode($json_data);
    }

    public function numberOfUseIndex()
    {
        $numberOfUses = NumberOfUse::all();
        return view('admin.numberofuse.index', compact('numberOfUses'));
    }
    public function numberOfUseCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('number_of_uses')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $numberOfUse = new NumberOfUse($request->only(['name', 'status']));
            $numberOfUse->save();
            return response()->json(['alert' => 'success', 'message' => 'Number Of Uses Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating NumberOfUses!' . $e->getMessage()], 500);
        }
    }

    public function showNumberOfUse($id)
    {
        $numberOfUse = NumberOfUse::find($id);
        if (!$numberOfUse) {
            return response()->json(['alert' => 'error', 'message' => 'NumberOfUses Not Found'], 500);
        }
        return response()->json($numberOfUse);
    }
    public function updateNumberOfUse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('number_of_uses')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $numberOfUse = NumberOfUse::findOrFail($id);
            $numberOfUse->fill($request->only(['name', 'status']));
            $numberOfUse->save();
            return response()->json(['alert' => 'success', 'message' => 'NumberOfUses Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteNumberOfUse($id)
    {
        $numberOfUse = NumberOfUse::findOrFail($id);
        $numberOfUse->delete();
        return response()->json(['alert' => 'success', 'message' => 'NumberOfUses Deleted SuccessFully!']);
    }
    public function updateNumberOfUseStatus($id)
    {
        try {
            $numberOfUse = NumberOfUse::findOrFail($id);
            if ($numberOfUse->status == '0') {
                $numberOfUse->status = '1';
                $message = 'Number Of Uses Active Successfully';
            } else if ($numberOfUse->status == '1') {
                $numberOfUse->status = '0';
                $message = 'Number Of Uses In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $numberOfUse->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
