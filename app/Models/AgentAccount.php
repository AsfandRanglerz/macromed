<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'agent_id',
        'account_name',
        'account_holder_name',
        'account_number',
    ];

    public function saleAgent()
    {
        return $this->belongsTo(SalesAgent::class);
    }
}
