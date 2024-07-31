<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class CurrencyController extends Controller
{
    public function currencyData()
    {
        $currencies = Currency::latest()->get();
        $json_data["data"] = $currencies;
        return json_encode($json_data);
    }

    public function currencyIndex()
    {
        $currencies = Currency::all();
        return view('admin.currency.index', compact('currencies'));
    }
    public function currencyCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doller_amount' => 'required|numeric',
                'pkr_amount' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $currency = new Currency($request->only(['doller_amount', 'pkr_amount']));
            $currency->save();
            return response()->json(['alert' => 'success', 'message' => 'Currency Created Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Currency!' . $e->getMessage()], 500);
        }
    }

    public function showCurrency($id)
    {
        $currency = Currency::find($id);
        if (!$currency) {
            return response()->json(['alert' => 'error', 'message' => 'Currency Not Found'], 500);
        }
        return response()->json($currency);
    }
    public function updateCurrency(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pkr_amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $currency = Currency::findOrFail($id);
            $currency->fill($request->only(['doller_amount', 'pkr_amount']));
            $currency->save();
            return response()->json(['alert' => 'success', 'message' => 'Currency Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Sub Admin' . $e->getMessage()], 500);
        }
    }

    public function deleteCurrency($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return response()->json(['alert' => 'success', 'message' => 'Currency Deleted SuccessFully!']);
    }
}
