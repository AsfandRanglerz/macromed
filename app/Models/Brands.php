<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'slug',
        'status',
        'owner',
        'company',
        'company_country',
        'contact_detail'
    ];

    public function product()
    {
        $this->hasMany(ProductBrands::class, 'brand_id');
    }
    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
}
