<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    protected $fillable = [
         'customer_id',
        'message'

    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
