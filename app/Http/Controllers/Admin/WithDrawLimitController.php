<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\WithDrawLimit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WithDrawLimitController extends Controller
{
    public function withdrawLimitData()
    {
        $walletLimits = WithDrawLimit::latest()->get();
        $json_data["data"] =  $walletLimits;
        return json_encode($json_data);
    }

    public function withdrawLimitIndex()
    {
        $walletLimits = WithDrawLimit::latest()->get();
        return view('admin.withdrawlimit.index', compact('walletLimits'));
    }
    public function withDrawLimitCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'min_limits' => 'required|numeric',
                'max_limits' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $waleetLimit = new WithDrawLimit($request->only(['min_limits','max_limits']));
            $waleetLimit->save();
            return response()->json(['alert' => 'success', 'message' => 'Wallet WithDraw Limit Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating WalletLimit!' . $e->getMessage()], 500);
        }
    }

    public function showwithDrawLimit($id)
    {
        $walletLimits = WithDrawLimit::find($id);

        if (!$walletLimits) {
            return response()->json(['alert' => 'error', 'message' => 'Wallet WithDraw Limit Not Found'], 404);
        }
        return response()->json($walletLimits);
    }

    public function updatewithDrawLimit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'min_limits' => 'required|numeric',
            'max_limits' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $waleetLimit = WithDrawLimit::findOrFail($id);
            $waleetLimit->fill($request->only(['min_limits','max_limits']));
            $waleetLimit->save();
            return response()->json(['alert' => 'success', 'message' => 'Wallet WithDraw Limit Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating WalletLimit' . $e->getMessage()], 500);
        }
    }

    public function deletewithDrawLimit($id)
    {
        $waleetLimit = WithDrawLimit::findOrFail($id);
        $waleetLimit->delete();
        return response()->json(['alert' => 'success', 'message' => 'Wallet WithDraw Limit Deleted SuccessFully!']);
    }
}
