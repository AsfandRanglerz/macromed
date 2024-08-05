<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesAgentNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_agent_id',
        'message'
    ];
    public function salesAgents()
    {
        return $this->belongsTo(SalesAgent::class);
    }
}
