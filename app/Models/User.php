<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements AuthenticatableContract
{
    use HasFactory, HasRoles;

    // Define hidden attributes
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define fillable attributes
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'phone',
        'status',
        'user_type'
    ];
    public function bankAccounts()
    {
        return $this->hasOne(UserAccount::class);
    }
}
