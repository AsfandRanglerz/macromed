<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\DiscountTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryDiscount;
use App\Http\Requests\UpdateCategoryDiscount;
use App\Models\Category;

class CategoryDiscountController extends Controller
{
    use DiscountTrait;

    public function discountsIndex($id)
    {
        $discounts = $this->getDiscounts($id);
        $categories = Category::findOrFail($id);
        return view('admin.category.discounts.index', compact('discounts', 'id', 'categories'));
    }

    public function discountsCreate(CreateCategoryDiscount $request, $id)
    {
        return $this->createDiscount(Category::class, $request, $id);
    }

    public function discountsShow($id)
    {
        return $this->showDiscount($id);
    }

    public function discountsUpdate(UpdateCategoryDiscount $request, $id)
    {
        return $this->updateDiscount($request, $id);
    }

    public function discountsDelete($id)
    {
        return $this->deleteDiscount($id);
    }

    public function updateDiscountStatus(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }
}
