<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubCatgeory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function subCategories()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
