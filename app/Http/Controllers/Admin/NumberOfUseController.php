<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\NumberOfUse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\NumberOfUseRequest;
use Illuminate\Support\Facades\Validator;

class NumberOfUseController extends BaseController
{
    protected $model = NumberOfUse::class;
    protected $keys = ['name', 'status'];
    protected $formRequestClass = NumberOfUseRequest::class;


    public function numberOfUseData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $numberOfUses = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $numberOfUses], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function numberOfUseIndex()
    {
        $numberOfUses = NumberOfUse::all();
        return view('admin.numberofuse.index', compact('numberOfUses'));
    }

    public function showNumberOfUse($id)
    {
        $numberOfUse = $this->model::findOrFail($id);
        if (!$numberOfUse) {
            return response()->json(['alert' => 'error', 'message' => 'NumberOfUses Not Found'], 500);
        }
        return response()->json($numberOfUse);
    }
}
