<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\AgentWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\orderDelivery;
use App\Models\SalesAgent;
use Illuminate\Support\Facades\Mail;
use App\Models\SalesAgentNotification;
use App\Traits\ProductHelperTrait;

class OrderController extends Controller
{
    use ProductHelperTrait;
    public function orderData(Request $request)
    {
        $status = $request->query('status', 'pending');
        $orders = Order::with('users:id,name,phone,email', 'salesAgent:id,id_number,name,email', 'orderItem')->where('status', $status)->latest()->get();
        $json_data["data"] = $orders;
        return json_encode($json_data);
    }
    public function orderIndex()
    {
        $salesAgents = SalesAgent::all();
        return view('admin.order.index', compact('salesAgents'));
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
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;
            $order = Order::findOrFail($id);
            if ($order->sales_agent_id === null) {
                return response()->json(['status' => 'error', 'message' => 'Please select a sales agent before marking as delivered.']);
            }
            $order->status = $request->status;
            $order->order_confirmation_message = 'Your order #' . $order->order_id . ' has been delivered.Thank you for shipping with us!';
            $order->save();
            if ($order->status == 'completed') {
                $totalCommission = $order->product_commission;
                $agentWallet = AgentWallet::where('sales_agent_id', $order->sales_agent_id)->first();
                if ($agentWallet) {
                    $agentWallet->pending_commission -= $totalCommission;
                    $agentWallet->recevied_commission += $totalCommission;
                    $agentWallet->save();
                }
                SalesAgentNotification::create([
                    'sales_agent_id' => $order->sales_agent_id,
                    'message' => 'You received a commission of $' . $totalCommission . '!',
                ]);
                $data['useremail'] =  $order->users->email;
                $data['username'] =  $order->users->name;
                $data['ordercode'] = $order->order_id;
                $data['total'] = $order->total * $pkrAmount;
                Mail::to($data['useremail'])->send(new orderDelivery($data));
            }
            DB::commit();
            return response()->json(['alert' => 'success', 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update status: ' . $e->getMessage()], 500);
        }
    }
    public function saveSalesAgent(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $order = Order::findOrFail($id);
            $order->sales_agent_id = $request->sales_agent_id;
            $order->save();
            $totalCommission = $order->product_commission;
            $agentWallet = AgentWallet::where('sales_agent_id', $order->sales_agent_id)->first();
            if ($agentWallet) {
                $agentWallet->pending_commission += $totalCommission;
                $agentWallet->total_commission += $totalCommission;
                $agentWallet->save();
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
            $orders = Order::with([
                'users:id,name,phone,email',
                'salesAgent:id,name,email',
                'orderItem.productVariant:id,product_id', // Fetch only `id` and `product_id` from productVariants
                'orderItem.productVariant.products:id,product_name' // Fetch only `id` and `product_name` from products
            ])->where('id', $id)->firstOrFail();
            // return $orders;
            return view('admin.order.invoice', compact('orders'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
