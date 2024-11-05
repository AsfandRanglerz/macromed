<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brands;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryDiscount;
use App\Http\Requests\UpdateCategoryDiscount;
use App\Traits\DiscountTrait;

class BrandDiscountController extends Controller
{
    use DiscountTrait;

    public function discountsIndex($id)
    {
        $discounts = $this->getDiscounts($id);
        $brands = Brands::findOrFail($id);
        return view('admin.brands.discounts.index', compact('discounts', 'id', 'brands'));
    }

    public function discountsCreate(CreateCategoryDiscount $request, $id)
    {
        return $this->createDiscount(Brands::class, $request, $id);
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
        return $this->updateDiscountStatus($request, $id);
    }
}
