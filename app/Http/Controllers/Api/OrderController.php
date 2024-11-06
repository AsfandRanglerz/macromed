<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SalesAgent;
use App\Models\AgentWallet;
use Illuminate\Http\Request;
use App\Models\ProductVaraint;
use App\Mail\orderConfirmation;
use App\Traits\ProductHelperTrait;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\SalesAgentNotification;

class OrderController extends Controller
{
    use ProductHelperTrait;
    public function selesAgent()
    {
        try {
            $salesAgent = SalesAgent::select('id', 'name')->get();
            if ($salesAgent->isEmpty()) {
                return response()->json([
                    'message' => [],
                ], 404);
            } else {
                return response()->json([
                    'salesAgent' =>  $salesAgent,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
    private function generateRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $maxAttempts = 5; // Maximum number of attempts to generate a unique string
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            if (!$this->isStringExists($randomString)) {
                return $randomString;
            }
        }
        return false;
    }
    private function isStringExists($string)
    {
        return Order::where('order_id', $string)->exists();
    }

    public function order(Request $request)
    {
        DB::beginTransaction();

        try {
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;
            $userId = $request->input('user_id');
            $cart = new Order();
            $cart->user_id = $userId;
            $cart->sales_agent_id = $request->sales_agent_id;
            $cart->address = $request->address;
            $cart->billing_address = $request->billing_address;
            $cart->country = $request->country;
            $cart->state = $request->state;
            $cart->city = $request->city;
            $cart->payment_type = $request->payment_type;
            $cart->card_number = $request->card_number;
            $cart->card_date = $request->card_date;
            $cart->cvc = $request->cvc;
            $cart->order_id = $this->generateRandomString(6);
            $cart->total = $request->total / $pkrAmount;
            $cart->status = 'pending';
            $cart->product_commission = 0;
            $cart->save();
            $products = json_decode($request->products, true);
            if (!$products || !is_array($products)) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or missing products data',
                ], 400);
            }

            $totalCommission = 0;
            foreach ($products as $product) {
                $productInfo = ProductVaraint::find($product['varaint_id']);
                if ($productInfo) {
                    // Calculate the product commission
                    $productCommissionRate = $productInfo->products->product_commission;
                    $productCommissionAmount = ($product['price'] * $product['quantity'] * ($productCommissionRate / 100));
                    $totalCommission += $productCommissionAmount;
                    if ($productInfo->remaining_quantity >= $product['quantity']) {
                        $productInfo->remaining_quantity -= $product['quantity'];
                        $productInfo->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Insufficient stock for product variant: ' . $product['variant_number'] . '. Only ' . $productInfo->remaining_quantity . ' units remaining.',
                        ], 400);
                    }

                    $orderItem = new OrderItem();
                    $orderItem->order_id = $cart->id;
                    $orderItem->varaint_id = $product['varaint_id'];
                    $orderItem->variant_number = $product['variant_number'];
                    $orderItem->image = $product['image'];
                    $orderItem->quantity = $product['quantity'];
                    $orderItem->price = $product['price'] / $pkrAmount;
                    $orderItem->subtotal = $product['quantity'] * $product['price'] / $pkrAmount;
                    $orderItem->product_discount = $product['product_discount'];
                    $orderItem->brand_discount = $product['brand_discount'];
                    $orderItem->category_discount = $product['category_discount'];
                    $orderItem->total_discount = $product['total_discount'];
                    $orderItem->save();
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product variant not found: ' . $product['variant_number'],
                    ], 400);
                }
            }
            $cart->product_commission = $totalCommission / $pkrAmount;
            $cart->order_confirmation_message = 'Your order #' . $cart->order_id . ' is pending. The admin will review it shortly. Please check back later for updates.';
            $cart->save();
            if ($cart->status == 'pending') {
                $totalCommission = $cart->product_commission;
                $agentWallet = AgentWallet::where('sales_agent_id', $cart->sales_agent_id)->first();
                if ($agentWallet) {
                    $agentWallet->pending_commission += $totalCommission;
                    $agentWallet->total_commission += $totalCommission;
                    $agentWallet->save();
                };
            }
            $data['useremail'] =  $cart->users->email;
            $data['username'] =  $cart->users->name;
            $data['ordercode'] = $cart->order_id;
            $data['total'] = $cart->total * $pkrAmount;
            Mail::to($data['useremail'])->send(new orderConfirmation($data));
            DB::commit();
            $cart->load('orderItem');
            return response()->json([
                'status' => 'success',
                'message' => 'Items added to cart successfully',
                'cart' => $cart,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getOrderDetail($userId)
    {
        try {
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }

            $pkrAmount = $currency->pkr_amount;
            $getUserOrders = Order::where('user_id', $userId)
                ->select('id', 'user_id', 'order_id', 'billing_address', 'total', 'address', 'payment_type', 'card_number', 'created_at', 'status')
                ->with([
                    'users:id,name,phone,email',
                    'orderItem' => function ($query) {
                        $query->select('order_id', 'variant_number', 'image', 'price', 'quantity', 'subtotal');
                    }
                ])
                ->latest()
                ->get();
            $getUserOrders->each(function ($order) use ($pkrAmount) {
                $order->total_in_pkr = $order->total * $pkrAmount;
                $order->orderItem->each(function ($item) use ($pkrAmount) {
                    $item->price_in_pkr = $item->price * $pkrAmount;
                    $item->subtotal_in_pkr = $item->subtotal * $pkrAmount;
                });
            });
            $totalOrders = $getUserOrders->count();
            $pendingOrders = $getUserOrders->where('status', 'pending')->count();
            $deliveredOrders = $getUserOrders->where('status', 'completed')->count();
            return response()->json([
                'status' => 'success',
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'delivered_orders' => $deliveredOrders,
                'order_details' => $getUserOrders,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function getOrderCount($userId)
    {
        try {
            $totalOrders = Order::where('user_id', $userId)->count();
            $pendingOrders = Order::where('user_id', $userId)
                ->where('status', 'pending')
                ->count();
            $deliveredOrders = Order::where('user_id', $userId)
                ->where('status', 'delivered')
                ->count();
            return response()->json([
                'status' => 'success',
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'delivered_orders' => $deliveredOrders,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function getOrderNotification($userId)
    {
        try {
            $seenCount = Order::where('user_id', $userId)->where('seen_by', 0)->count();
            $notificationMessage = Order::where('user_id', $userId)
                ->select('status', 'order_confirmation_message')
                ->get();
            return response()->json([
                'status' => 'success',
                'seen_notification' => $seenCount,
                'notifcation_message' => $notificationMessage,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}
