<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\PackingValue;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Http\Requests\PackingValueRequest;

class PackingValueController extends BaseController
{

        protected $model = PackingValue::class;
        protected $keys = ['name', 'status'];
        protected $formRequestClass = PackingValueRequest::class;

        public function PackingValueData(Request $request)
        {
            try {
                $is_draft = $request->query('is_draft', '1');
                $packingvalues = $this->model::where('is_draft', $is_draft)->latest()->get();
                return response()->json(['data' => $packingvalues], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to fetch category data',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        public function PackingValuesIndex()
        {
            $packingvalues = PackingValue::all();
            return view('admin.PackingValue.index', compact('packingvalues'));
        }

        public function showPackingValue($id)
        {
            $packingvalue = $this->model::findOrFail($id);
            if (!$packingvalue) {
                return response()->json(['alert' => 'error', 'message' => 'PackingValues Not Found'], 500);
            }
            return response()->json($packingvalue);
        }

}
