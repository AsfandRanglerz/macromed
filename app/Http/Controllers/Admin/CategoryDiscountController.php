<?php

namespace App\Http\Controllers\Admin;

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
        // return  $discounts;
        $categories = Category::where('id', $id)->first();
        return view('admin.category.discounts.index', compact('discounts', 'id', 'categories'));
    }

    public function discountsCreate(CreateCategoryDiscount $request,   $id)
    {
        try {
            DB::beginTransaction();
            $category = Category::findOrFail($id);
            $category->discounts()->create([
                'name' => $request->name,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status
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
        if (!$discount) {
            return response()->json(['alert' => 'error', 'message' => 'Discounts Not Found'], 500);
        }
        return response()->json($discount);
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
}
