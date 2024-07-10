<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productVaraint()
    {
        return $this->hasMany(ProductVaraint::class, 'product_id');
    }
    public function productBrands()
    {
        return $this->hasMany(ProductBrands::class, 'product_id');
    }
    public function productCertifications()
    {
        return $this->hasMany(ProductCertifcation::class, 'product_id');
    }
    public function productCategorySubCategory()
    {
        return $this->hasMany(ProductCategorySubCategory::class, 'product_id');
    }
}
