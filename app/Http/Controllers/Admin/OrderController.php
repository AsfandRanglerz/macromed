<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\AgentWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\SalesAgentNotification;

class OrderController extends Controller
{
    public function orderData()
    {
        $orders = Order::with('users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem')->where('status', 'pending')->latest()->get();
        $json_data["data"] = $orders;
        return json_encode($json_data);
    }
    public function orderIndex()
    {
        $orders = Order::with('users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem')->where('status', 'pending')->latest()->get();
        // return  $orders;
        return view('admin.order.index', compact('orders'));
    }
    public function orderDeliveredData()
    {
        $orders = Order::with('users.wallet', 'Items.products')->where('status', 'completed')->latest()->get();
        $json_data["data"] = $orders;
        return json_encode($json_data);
    }
    public function getStatus($id)
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json(['status' => $order->status]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed To Get' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $order = Order::findOrFail($id);
            $order->status = $request->status;
            $order->save();
            if ($order->status == 'completed') {
                $totalCommission = $order->product_commission;
                $agentWallet = AgentWallet::where('sales_agent_id', $order->sales_agent_id)->first();
                if ($agentWallet) {
                    $agentWallet->total_commission += $totalCommission;
                    $agentWallet->save();
                }
                SalesAgentNotification::create([
                    'sales_agent_id' => $order->sales_agent_id,
                    'message' => 'You received a commission of $' . $totalCommission . '!',
                ]);
            }
            DB::commit();
            return response()->json(['alert' => 'success', 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update status: ' . $e->getMessage()], 500);
        }
    }

    public function deleteOrder($id)
    {
        $product = Order::findOrFail($id);
        $product->delete();
        return response()->json(['alert' => 'success', 'message' => 'Order Deleted SuccessFully!']);
    }
    public function getOrderCount()
    {
        $orderCount = Order::where('status', 'pending')->count();
        return response()->json(['count' => $orderCount]);
    }
    public function getInVoiceDetails($id)
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
