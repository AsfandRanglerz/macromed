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

    protected function updateDiscountStatus(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);
            $discount->status = $discount->status == '0' ? '1' : '0';
            $message = $discount->status == '1' ? 'Discounts Activated Successfully' : 'Discounts Deactivated Successfully';
            $discount->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating discount status.']);
        }
    }
}
