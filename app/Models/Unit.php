<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'is_draft'
    ];

    public function scopeActiveAndNonDraft($query)
    {
        return $query->where('status', '1')->where('is_draft', 1);
    }
}
