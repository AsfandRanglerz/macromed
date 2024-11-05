<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\DiscountTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryDiscount;
use App\Http\Requests\UpdateCategoryDiscount;

class ProductDiscountController extends Controller
{
    use DiscountTrait;

    public function discountsIndex($id)
    {
        $discounts = $this->getDiscounts($id);
        $products = Product::findOrFail($id);
        return view('admin.product.discounts.index', compact('discounts', 'id', 'products'));
    }

    public function discountsProductCreate(CreateCategoryDiscount $request, $id)
    {
        return $this->createDiscount(Product::class, $request, $id);
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
