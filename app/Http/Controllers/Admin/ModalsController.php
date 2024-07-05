<?php

namespace App\Http\Controllers\Admin;

use App\Models\Models;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ModalsController extends Controller
{
    public function modelsData()
    {
        $models = Models::latest()->get();
        $json_data["data"] = $models;
        return json_encode($json_data);
    }

    public function modelsIndex()
    {
        $models = Models::all();
        return view('admin.models.index', compact('models'));
    }
    public function modelsCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'numeric',
                    'min:1',
                    Rule::unique('models')
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $model = new Models($request->only(['name', 'status']));
            $model->save();
            return response()->json(['alert' => 'success', 'message' => 'Models Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Models!' . $e->getMessage()], 500);
        }
    }

    public function showModels($id)
    {
        $model = Models::find($id);
        if (!$model) {
            return response()->json(['alert' => 'error', 'message' => 'Models Not Found'], 500);
        }
        return response()->json($model);
    }
    public function updateModels(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'numeric',
                'min:1',
                Rule::unique('models')->ignore($id),

            ],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $model = Models::findOrFail($id);
            $model->fill($request->only(['name', 'status']));
            $model->save();
            return response()->json(['alert' => 'success', 'message' => 'Models Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteModels($id)
    {
        $model = Models::findOrFail($id);
        $model->delete();
        return response()->json(['alert' => 'success', 'message' => 'Models Deleted SuccessFully!']);
    }
    public function updateModelsStatus($id)
    {
        try {
            $model = Models::findOrFail($id);
            if ($model->status == '0') {
                $model->status = '1';
                $message = 'Models Active Successfully';
            } else if ($model->status == '1') {
                $model->status = '0';
                $message = 'Models In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $model->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
