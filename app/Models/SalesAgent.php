<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesAgent extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function agentAccounts()
    {
        return $this->hasOne(AgentAccount::class,'agent_id');
    }
}
