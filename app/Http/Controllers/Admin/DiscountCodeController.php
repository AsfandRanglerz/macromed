<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
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
}
