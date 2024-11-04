<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryDiscount;

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
            ]);
            DB::commit();
            return response()->json(['alert' => 'success', 'message' => 'Discount Created Successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['alert' => 'error', 'error' => 'An error occurred .', $e->getMessage()]);
        }
    }
}
