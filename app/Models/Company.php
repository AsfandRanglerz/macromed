<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'contact_detail',
        'country',
        'state',
        'city',
        'zip',
        'website'
    ];
}
