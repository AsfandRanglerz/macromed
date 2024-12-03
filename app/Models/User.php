<?php

namespace App\Models;


use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasRoles;
    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */

    // Define hidden attributes
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Define fillable attributes
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'phone',
        'country',
        'city',
        'state',
        'profession',
        'location',
        'status',
        'user_type',
        'is_active',
        'work_space_name',
        'work_space_email',
        'work_space_address',
        'work_space_number',
        'is_draft'
    ];
    public function bankAccounts()
    {
        return $this->hasOne(UserAccount::class);
    }
    public function subAdminPrivateNote()
    {
        return $this->hasMany(AdminNotes::class, 'sub_admin_id');
    }

    public function adminNotification()
    {
        return $this->hasMany(UserNotification::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function whishList()
    {
        return $this->hasMany(WhishList::class, 'user_id');
    }


}
