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
    public function brands()
    {
        return $this->hasMany(ProductBrands::class);
    }

    public function certifications()
    {
        return $this->hasMany(ProductCertifcation::class);
    }
    public function categories()
    {
        return $this->hasMany(ProductCategorySubCategory::class);
    }

}
