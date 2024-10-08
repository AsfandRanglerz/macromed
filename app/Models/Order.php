<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function salesAgent()
    {
        return $this->belongsTo(SalesAgent::class, 'sales_agent_id', 'id');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
