<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
    public function product()
    {
        $this->hasMany(ProductCertifcation::class, 'certification_id');
    }
}
