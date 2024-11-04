<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'status'
    ];
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function products()
    {
        return $this->belongsTo(Product::class);
    }
    public function product()
    {
        $this->hasMany(ProductCatgeory::class, 'category_id');
    }
    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
}
