<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesAgentNotes extends Model
{
    use HasFactory;
    protected $fillable = [
        'agent_id',
        'title',
        'description'
    ];
}
