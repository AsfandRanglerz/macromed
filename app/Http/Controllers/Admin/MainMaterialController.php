<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\MainMaterial;
use Illuminate\Http\Request;
use App\Http\Requests\MainMaterialRequest;


class MainMaterialController extends BaseController
{
    protected $model = MainMaterial::class;
    protected $keys = ['name', 'status'];
    protected $formRequestClass = MainMaterialRequest::class;

    public function mainMaterialData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $mainMaterial = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $mainMaterial], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function MainMaterialIndex()
    {
        $mainMaterial = MainMaterial::all();
        return view('admin.mianmaterial.index', compact('mainMaterial'));
    }


    public function showMainMaterial($id)
    {
        $mainMaterial =  $this->model::findOrFail($id);
        if (!$mainMaterial) {
            return response()->json(['alert' => 'error', 'message' => 'MainMaterial Not Found'], 500);
        }
        return response()->json($mainMaterial);
    }
}
