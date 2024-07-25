<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainMaterial extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status'
    ];

    public function materials()
    {
        $this->hasMany(ProductMaterial::class, 'material_id');
    }
}
