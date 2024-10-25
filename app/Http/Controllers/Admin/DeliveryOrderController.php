<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryOrderController extends Controller
{
    public function orderDeliverData()
    {
        $orders = Order::with('users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem')->where('status', 'completed')->latest()->get();
        $json_data["data"] = $orders;
        return json_encode($json_data);
    }
    public function orderDeliverIndex()
    {
        return view('admin.deliverorder.index');
    }
    public function getInVoiceDeliverDetails($id)
    {
        try {
            $orders = Order::with('users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem')->where('id', $id)->findOrFail($id);

            return view('admin.order.invoice', compact('orders'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
