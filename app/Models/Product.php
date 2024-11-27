<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ProductImage;

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
    public function productCategory()
    {
        return $this->hasMany(ProductCatgeory::class, 'product_id');
    }
    public function productSubCategory()
    {
        return $this->hasMany(ProductSubCatgeory::class, 'product_id');
    }

    public function productMaterial()
    {
        return $this->hasMany(ProductMaterial::class, 'product_id');
    }

    public function productTax()
    {
        return $this->hasMany(ProductTax::class, 'product_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImages::class, 'product_id');
    }
    public function whishList()
    {
        return $this->hasMany(WhishList::class, 'product_id');
    }
    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
 
}
