<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'account_name',
        'account_holder_name',
        'account_number',
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
