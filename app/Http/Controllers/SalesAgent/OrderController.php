<?php

namespace App\Http\Controllers\SalesAgent;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class OrderController extends Controller
{
    protected function getSalesAgentId()
    {
        return auth()->guard('sales_agent')->id();
    }
    public function orderUserData(Request $request)
    {
        $status = $request->query('status', 'pending');
        $orders = Order::with('users:id,name,phone,email')->where('status', $status)->where('sales_agent_id', $this->getSalesAgentId())->latest()->get();
        $json_data["data"] = $orders;
        return json_encode($json_data);
    }
    public function orderUserIndex()
    {

        return view('salesagent.orders.index');
    }

    public function getUserOrderCount()
    {
        $orderCount = Order::where('status', 'pending')->where('sales_agent_id', $this->getSalesAgentId())->count();
        return response()->json(['count' => $orderCount]);
    }
}
