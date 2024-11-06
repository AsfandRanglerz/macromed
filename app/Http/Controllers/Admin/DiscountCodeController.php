<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCodeCreate;

class DiscountCodeController extends Controller
{
    private function generateUniqueDiscountCode()
    {
        do {
            // Generate a random 8-character alphanumeric code
            $code = strtoupper(Str::random(8));
        } while (DiscountCode::where('discount_code', $code)->exists());

        return $code;
    }
    public function discountsCodeData()
    {
        $currencies = DiscountCode::latest()->get();
        $json_data["data"] = $currencies;
        return json_encode($json_data);
    }

    public function discountsCodeIndex()
    {
        // $currencies = DiscountCode::all();
        return view('admin.discountcode.index');
    }

    public function discountsCodeCreate(DiscountCodeCreate $request)
    {
        try {
            DB::beginTransaction();
            if ($request->status == 1) {
                DiscountCode::where('status', '1')->update([
                    'status' => '0',
                    'expiration_status' => 'inactive'
                ]);
            }
            $discountCode = $this->generateUniqueDiscountCode();
            DiscountCode::create([
                'discount_code' => $discountCode,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'min_quantity' => $request->min_quantity,
                'max_quantity' => $request->max_quantity,
                'usage_limit' => $request->usage_limit,
                'remaining_usage_limit' => $request->usage_limit,
                'status' => $request->status,
            ]);
            DB::commit();
            return response()->json(['alert' => 'success', 'message' => 'Discount Code created successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while Creating Customers: ' . $e->getMessage()], 500);
        }
    }

    public function showDiscountsCode($id)
    {
        $discountCode = DiscountCode::find($id);
        if (!$discountCode) {
            return response()->json(['alert' => 'error', 'message' => 'Customer Not Found'], 500);
        }
        return response()->json($discountCode);
    }

    public function updateDiscountsCode(DiscountCodeCreate $request, $id)
    {
        try {
            DB::beginTransaction();
            $discountCode = DiscountCode::findOrFail($id);
            if ($request->status == 1) {
                DiscountCode::where('status', '1')->where('id', '!=', $id)->update([
                    'status' => '0',
                    'expiration_status' => 'inactive'
                ]);
            }
            $discountCode->update([
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'min_quantity' => $request->min_quantity,
                'max_quantity' => $request->max_quantity,
                'usage_limit' => $request->usage_limit,
                'remaining_usage_limit' => $request->usage_limit,
            ]);

            DB::commit();
            return response()->json(['alert' => 'success', 'message' => 'Discount Code updated successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating Discount Code: ' . $e->getMessage()], 500);
        }
    }

    public function deleteDiscountsCode($id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->delete();
        return response()->json(['alert' => 'success', 'message' => 'Customers Deleted SuccessFully!']);
    }

    public function updateDiscountCodeStatus($id)
    {
        try {
            $discount = DiscountCode::findOrFail($id);
            if ($discount->status == '0') {
                DiscountCode::where('id', '!=', $id)
                    ->where('status', '1')
                    ->update([
                        'status' => '0',
                        'expiration_status' => 'inactive'
                    ]);
                $discount->status = '1';
                $discount->expiration_status = 'active';
                $message = 'Discount Code Activated Successfully';
            } else if ($discount->status == '1') {
                $discount->status = '0';
                $discount->expiration_status = 'inactive';
                $message = 'Discount Code Deactivated Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'Discount status is already updated or cannot be updated.'],200);
            }

            $discount->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating discount status: ' . $e->getMessage()],500);
        }
    }
}
