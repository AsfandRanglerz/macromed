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
        'status'
    ];

    public function productVaraint()
    {
        return $this->hasMany(ProductVaraint::class, 'brand_id');
    }
}
