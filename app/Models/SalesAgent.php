<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesAgent extends Authenticatable
{
    use HasFactory;
    // protected $table = 'sales_agents';
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
        return $this->hasOne(AgentAccount::class, 'agent_id','id');
    }

    public function agentWallet()
    {
        return $this->hasOne(AgentWallet::class, 'sales_agent_id');
    }

    public function adminNotification()
    {
        return $this->hasMany(SalesAgentNotification::class, 'sales_agent_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'sales_agent_id');
    }
}
