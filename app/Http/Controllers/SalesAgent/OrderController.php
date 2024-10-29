<?php

namespace App\Http\Controllers\SalesAgent;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $salesAgentId;

    // Constructor to initialize the sales agent ID
    public function __construct()
    {
        $this->salesAgentId = Auth::guard('sales_agent')->id();
    }
    public function orderUserData(Request $request)
    {
        $status = $request->query('status', 'pending');
        $orders = Order::with('users:id,name,phone,email')->where('status', $status)->where('sales_agent_id', $this->salesAgentId)->latest()->get();
        $json_data["data"] = $orders;
        return json_encode($json_data);
    }
    public function orderUserIndex()
    {

        return view('salesagent.orders.index');
    }

    public function getUserOrderCount()
    {
        $orderCount = Order::where('status', 'pending')->where('sales_agent_id', $this->salesAgentId)->count();
        return response()->json(['count' => $orderCount]);
    }
}
