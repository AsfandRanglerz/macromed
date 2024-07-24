<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesAgent extends Authenticatable
{
    use HasFactory;
    protected $table = 'sales_agents';
    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function agentAccounts()
    {
        return $this->hasOne(AgentAccount::class, 'agent_id');
    }
}
