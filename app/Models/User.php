<?php

namespace App\Models;

use Faker\Provider\ar_EG\Company;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = ['name', 'email', 'image', 'password','phone', 'status','user_type'];
}
