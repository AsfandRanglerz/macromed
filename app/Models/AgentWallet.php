<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentWallet extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function saleAgent()
    {
        return $this->belongsTo(SalesAgent::class);
    }
}
