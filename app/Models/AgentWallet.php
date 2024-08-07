<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'agent_id',
        'remaning_commission_amount',
        'total_commission_amount',
    ];

    public function saleAgent()
    {
        return $this->belongsTo(SalesAgent::class);
    }
}
