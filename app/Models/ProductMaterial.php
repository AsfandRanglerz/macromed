<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function mainMaterial()
    {
        return $this->belongsTo(MainMaterial::class, 'material_id', 'id');
    }
}
