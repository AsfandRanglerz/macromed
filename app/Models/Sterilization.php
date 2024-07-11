<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sterilization extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status'
    ];
}
