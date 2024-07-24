<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesAgent extends Authenticatable
{
    use HasFactory;
    protected $guarded = [];

    public function agentAccounts()
    {
        return $this->hasOne(AgentAccount::class, 'agent_id');
    }
}
