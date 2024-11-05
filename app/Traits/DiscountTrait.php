<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait DiscountTrait
{
    protected function getDiscounts($id)
    {
        $variants = Discount::where('discountable_id', $id)->latest()->get();
        return response()->json(['data' => $variants]);
    }

    protected function createDiscount($discountableModel, Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $discountable = $discountableModel::findOrFail($id);
            $discountable->discounts()
                ->where('status', '1')
                ->update([
                    'status' => '0',
                    'discount_expiration_status' => 'inactive',
                ]);
            $discountable->discounts()->create([
                'name' => $request->name,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'discount_expiration_status' => 'active',
            ]);
            DB::commit();
            return response()->json(['alert' => 'success', 'message' => 'Discount Created Successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['alert' => 'error', 'error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    protected function showDiscount($id)
    {
        $discount = Discount::find($id);
        if ($discount) {
            $discount->start_date = Carbon::parse($discount->start_date)->format('Y-m-d\TH:i');
            $discount->end_date = Carbon::parse($discount->end_date)->format('Y-m-d\TH:i');
            return response()->json($discount);
        }
        return response()->json(['error' => 'Discount not found'], 404);
    }

    protected function updateDiscount(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);
            $discount->update([
                'name' => $request->name,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            return response()->json(['alert' => 'success', 'message' => 'Discount updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    protected function deleteDiscount($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return response()->json(['alert' => 'success', 'message' => 'Discount Deleted Successfully!']);
    }

 
    protected function updateStatus(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);

            if ($discount->status == '0') {
                Discount::where('discountable_id', $discount->discountable_id)
                    ->where('discountable_type', $discount->discountable_type)
                    ->where('id', '!=', $id)
                    ->where('status', '1')
                    ->update([
                        'status' => '0',
                        'discount_expiration_status' => 'inactive'
                    ]);

                $discount->status = '1';
                $discount->discount_expiration_status = 'active';
                $message = 'Discount Activated Successfully';
            } else if ($discount->status == '1') {
                $discount->status = '0';
                $discount->discount_expiration_status = 'inactive';
                $message = 'Discount Deactivated Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'Discount status is already updated or cannot be updated.']);
            }

            $discount->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating discount status: ' . $e->getMessage()]);
        }
    }
}
