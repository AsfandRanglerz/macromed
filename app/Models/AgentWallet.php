<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_agent_id',
        'recevied_commission',
        'pending_commission',
        'total_commission',
    ];


    public function saleAgents()
    {
        return $this->belongsTo(SalesAgent::class,'sales_agent_id','id');
    }
}
