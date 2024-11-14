<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

class admin extends Authenticatable
{
    use HasFactory;
    protected $guarded = [];

    public function adminPrivateNote()
    {
        return $this->hasMany(AdminNotes::class, 'admin_id');
    }

    public function admin_user()
    {
        return $this->morphMany(Category::class, 'admin_user');
    }
}
