<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryDiscount;
use App\Http\Requests\UpdateCategoryDiscount;

class CategoryDiscountController extends Controller
{
    public function getDiscounts($id)
    {
        $variants = Discount::where('discountable_id', $id)->latest()->get();
        $json_data["data"] = $variants;
        return json_encode($json_data);
    }
    public function discountsIndex($id)
    {
        $discounts = Discount::where('discountable_id', $id)->get();
        $categories = Category::where('id', $id)->first();
        return view('admin.category.discounts.index', compact('discounts', 'id', 'categories'));
    }

    public function discountsCreate(CreateCategoryDiscount $request,   $id)
    {
        try {
            DB::beginTransaction();
            $discount = Category::findOrFail($id);
            $discount->discounts()->create([
                'name' => $request->name,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'discount_expiration_status' => 'active'
            ]);
            DB::commit();
            return response()->json(['alert' => 'success', 'message' => 'Discount Created Successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['alert' => 'error', 'error' => 'An error occurred .', $e->getMessage()]);
        }
    }

    public function discountsShow($id)
    {
        $discount = Discount::find($id);
        if ($discount) {
            $discount->start_date = Carbon::parse($discount->start_date)->format('Y-m-d\TH:i');
            $discount->end_date = Carbon::parse($discount->end_date)->format('Y-m-d\TH:i');
            return response()->json($discount);
        } else {
            return response()->json(['error' => 'Discount not found'], 404);
        }
    }

    public function discountsUpdate(UpdateCategoryDiscount $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);
            $discount->update([
                'name' => $request->name,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            return redirect()->back()->with('success', 'Discount updated successfully!');
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred .', $e->getMessage()]);
        }
    }
    public function discountsDelete($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return response()->json(['alert' => 'success', 'message' => 'Category Deleted SuccessFully!']);
    }
    public function updateDiscountStatus(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);
            if ($discount->status == '0') {
                $discount->status = '1';
                $message = 'Discouts Active Successfully';
            } else if ($discount->status == '1') {
                $discount->status = '0';
                $message = 'Discouts In Active Successfully';
            } else {
                return response()->json(['alert' => 'info', 'error' => 'User status is already updated or cannot be updated.']);
            }
            $discount->save();
            return response()->json(['alert' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'error' => 'An error occurred while updating user status.']);
        }
    }
}
