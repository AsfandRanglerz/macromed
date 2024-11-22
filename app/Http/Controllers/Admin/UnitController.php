<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;


class UnitController extends BaseController
{
    protected $model = Unit::class;
    protected $keys = ['name', 'status'];
    protected $formRequestClass = UnitRequest::class;

    public function unitsData(Request $request)
    {
        try {
            $is_draft = $request->query('is_draft', '1');
            $units = $this->model::where('is_draft', $is_draft)->latest()->get();
            return response()->json(['data' => $units], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch category data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function unitsIndex()
    {
        $units = Unit::all();
        return view('admin.unit.index', compact('units'));
    }

    public function showUnits($id)
    {
        $unit = $this->model::findOrFail($id);
        if (!$unit) {
            return response()->json(['alert' => 'error', 'message' => 'Units Not Found'], 500);
        }
        return response()->json($unit);
    }
}
