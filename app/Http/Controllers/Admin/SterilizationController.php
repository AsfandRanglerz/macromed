<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Sterilization;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\SterilizationsRequest;
use Illuminate\Support\Facades\Validator;

class SterilizationController extends BaseController
{
    protected $model = Sterilization::class;
    protected $keys = ['name', 'status'];
    protected $formRequestClass = SterilizationsRequest::class;

    public function sterilizationData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $certifications = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $certifications], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function sterilizationIndex()
    {
        $sterilizations = Sterilization::all();
        return view('admin.sterilization.index', compact('sterilizations'));
    }

    public function showSterilization($id)
    {
        $sterilization = $this->model::findOrFail($id);
        if (!$sterilization) {
            return response()->json(['alert' => 'error', 'message' => 'Sterilization Not Found'], 500);
        }
        return response()->json($sterilization);
    }
}
