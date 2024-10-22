<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

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
    public function orderDeliverIndex()
    {
        $orders = Order::with('users', 'Items.products', 'cashBack')->latest()->get();
        // return  $orders;
        return view('admin.deliveredorder.index', compact('orders'));
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
            $order = Order::with('users')->findOrFail($id);
            $order->status = $request->status;
            $order->save();

            // if ($request->status == 'completed') {
            //     $title = 'Order Delivered';
            //     $description = "Your order has been successfully delivered. Thank you for shopping with us!";
            //     EzaaShopNotificationHelper::sendFcmNotification($order->users->fcm_token, $title, $description);
            //     Notification::create([
            //         'title' => $title,
            //         'descriptions' => $description,
            //         'receiver_user_id' => $order->users->id,
            //         'admin' => 'EzaaShop',
            //     ]);
            // }

            return response()->json(['alert' => 'success', 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
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
        $orderCount = Order::where('status', 'inProcess')->count();
        return response()->json(['count' => $orderCount]);
    }
    public function getInVoiceDetails($id)
    {
        try {
            // $orderDetails = Order::with('users', 'Items.products', '')->findOrFail($id);
            return view('admin.order.invoice');

        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
