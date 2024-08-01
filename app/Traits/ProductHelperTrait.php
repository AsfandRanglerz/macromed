<?php

namespace App\Traits;

use App\Models\Currency;

trait ProductHelperTrait
{
    // Helper method to get the currency
    private function getCurrency()
    {
        return Currency::first();
    }

    // Helper method to convert prices
    private function convertPrices($products, $pkrAmount)
    {
        return $products->transform(function ($product) use ($pkrAmount) {
            $product->min_price_range_pkr = $product->min_price_range * $pkrAmount;
            $product->max_price_range_pkr = $product->max_price_range * $pkrAmount;
            return $product;
        });
    }
}
