<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SalesAgent;
use App\Models\AgentWallet;
use App\Models\DiscountCode;
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
    // ############## couponCode #################
    public function couponCode(Request $request)
    {
        try {
            $discountCode = $request->input('discount_code');
            if ($discountCode) {
                $discount = DiscountCode::where('discount_code', $discountCode)->select('id', 'discount_code', 'discount_percentage', 'expiration_status', 'remaining_usage_limit')->first();
                if (!$discount) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid discount code.',
                    ], 400);
                }
                if ($discount->expiration_status !== 'active') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The discount code is expired.',
                    ], 400);
                }

                if ($discount->remaining_usage_limit <= 0) {
                    $discount->expiration_status = 'inactive';
                    $discount->save();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The discount code has reached its usage limit.',
                    ], 400);
                }

                // If all validations pass, return the discount data
                return response()->json([
                    'status' => 'success',
                    'message' => 'Discount code applied successfully.',
                    'data' => $discount, // Return all data associated with the discount code
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No discount code provided.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }




    public function order(Request $request)
    {
        DB::beginTransaction();

        try {
            // Step 1: Retrieve currency
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ], 400);
            }

            $pkrAmount = $currency->pkr_amount;
            $userId = $request->input('user_id');
            $discountCode = $request->input('discount_code');

            // Step 2: Validate Discount Code
            $discount = null;
            if ($discountCode) {
                $discount = DiscountCode::where('discount_code', $discountCode)->first();
                if (!$discount) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid discount code.',
                    ], 400);
                }
                if ($discount->expiration_status !== 'active') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The discount code is expired.',
                    ], 400);
                }
                if ($discount->remaining_usage_limit <= 0) {
                    $discount->expiration_status = 'inactive';
                    $discount->save();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The discount code has reached its usage limit.',
                    ], 400);
                }
            }
            $cart = new Order();
            $cart->user_id = $userId;
            $cart->sales_agent_id = $request->input('sales_agent_id');
            $cart->address = $request->input('address');
            $cart->billing_address = $request->input('billing_address');
            $cart->country = $request->input('country');
            $cart->state = $request->input('state');
            $cart->city = $request->input('city');
            $cart->payment_type = $request->input('payment_type');
            $cart->card_number = $request->input('card_number');
            $cart->card_date = $request->input('card_date');
            $cart->cvc = $request->input('cvc');
            $cart->order_id = $this->generateRandomString(6);
            $cart->total = $request->input('total') / $pkrAmount;
            $cart->discount_code = $discountCode;
            $cart->discounted_total = $request->input('discounted_total') / $pkrAmount;
            $cart->dicount_code_percentage = $discount ? $discount->discount_percentage : null;
            $cart->status = 'pending';
            $cart->product_commission = 0;
            $cart->save();

            // Step 4: Process products in the order
            $products = json_decode($request->input('products'), true);
            if (!$products || !is_array($products)) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or missing products data.',
                ], 400);
            }

            $totalCommission = 0;
            $validDiscount = false;

            foreach ($products as $product) {
                $productInfo = ProductVaraint::find($product['varaint_id']);
                if (!$productInfo) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product variant not found: ' . $product['variant_number'],
                    ], 400);
                }

                // Check stock availability
                if ($productInfo->remaining_quantity < $product['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Insufficient stock for product variant: ' . $product['variant_number'] . '. Only ' . $productInfo->remaining_quantity . ' units remaining.',
                    ], 400);
                }
                $productInfo->remaining_quantity -= $product['quantity'];
                $productInfo->save();
                if ($discount && $product['quantity'] >= $discount->min_quantity && $product['quantity'] <= $discount->max_quantity) {
                    $validDiscount = true;
                }
                $productCommissionRate = $productInfo->products->product_commission;
                $price = ($product['discounted_price'] != 0) ? $product['discounted_price'] : $product['price'];
                $productCommissionAmount = ($price * $product['quantity'] * ($productCommissionRate / 100));
                $totalCommission += $productCommissionAmount;
                // Create order item
                $orderItem = new OrderItem();
                $orderItem->order_id = $cart->id;
                $orderItem->varaint_id = $product['varaint_id'];
                $orderItem->variant_number = $product['variant_number'];
                $orderItem->image = $product['image'];
                $orderItem->quantity = $product['quantity'];
                $orderItem->price = $product['price'] / $pkrAmount;
                $orderItem->discounted_price = ($product['discounted_price'] ?? $product['price']) / $pkrAmount;
                $orderItem->subtotal = $product['quantity'] * $price / $pkrAmount;
                $orderItem->product_discount = $product['product_discount'] ?? null;
                $orderItem->brand_discount = $product['brand_discount'] ?? null;
                $orderItem->category_discount = $product['category_discount'] ?? null;
                $orderItem->total_discount = $product['total_discount'] ?? null;
                $orderItem->save();
            }
            if ($discount) {
                if (!$validDiscount) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The discount code does not apply to the products in your order. Product quantities must be between ' . $discount->min_quantity . ' and ' . $discount->max_quantity . '.',
                    ], 400);
                }
                $discount->remaining_usage_limit -= 1;
                $discount->save();
            }
            $cart->product_commission = $totalCommission / $pkrAmount;
            $cart->order_confirmation_message = 'Your order #' . $cart->order_id . ' is pending. The admin will review it shortly. Please check back later for updates.';
            $cart->save();
            if ($cart->status === 'pending') {
                $agentWallet = AgentWallet::where('sales_agent_id', $cart->sales_agent_id)->first();
                if ($agentWallet) {
                    $agentWallet->pending_commission +=  $cart->product_commission;
                    $agentWallet->total_commission +=  $cart->product_commission;
                    $agentWallet->save();
                }
            }
            $data = [
                'useremail' => $cart->users->email,
                'username' => $cart->users->name,
                'ordercode' => $cart->order_id,
                'total' => $cart->total * $pkrAmount,
            ];
            Mail::to($data['useremail'])->send(new OrderConfirmation($data));
            $cart->load('orderItem');
            DB::commit();

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
                ->select('id', 'user_id', 'order_id', 'billing_address', 'total', 'discounted_total', 'address', 'payment_type', 'card_number', 'dicount_code_percentage', 'discount_code', 'created_at', 'status')
                ->with([
                    'users:id,name,phone,email',
                    'orderItem'
                ])
                ->latest()
                ->get();
            $getUserOrders->each(function ($order) use ($pkrAmount) {
                $order->total_in_pkr = $order->total * $pkrAmount;
                $order->discounted_total = $order->discounted_total * $pkrAmount;
                $order->orderItem->each(function ($item) use ($pkrAmount) {
                    $item->price_in_pkr = $item->price * $pkrAmount;
                    $item->subtotal_in_pkr = $item->subtotal * $pkrAmount;
                    $item->discounted_price_in_pkr = $item->discounted_price * $pkrAmount;
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


    public function orderDiscount()
    {
        try {
            $discountCodes = DiscountCode::select('id', 'discount_code', 'discount_percentage', 'start_date', 'end_date', 'status', 'expiration_status')
                ->where('status', '1')
                ->get();

            if ($discountCodes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Discount Code Found',
                ], 404);
            } else {
                $discountCodes->transform(function ($discount) {
                    if ($discount->expiration_status === 'active') {
                        $now = now();
                        $endDate = Carbon::parse($discount->end_date);
                        $remainingTime = $endDate->diff($now);

                        $discount->discount_message = "{$remainingTime->d} DAY ONLY";
                    } else {
                        $discount->discount_message = "Discount is not active.";
                    }

                    return $discount;
                });

                return response()->json([
                    'status' => 'success',
                    'discountCodes' => $discountCodes,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function seenBy($id)
    {
        try {
            $updatedRows = Order::where('user_id', $id)->update(['seen_by' => '1']);

            if ($updatedRows == '0') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Notifcation Found!',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Notifications have been seen!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkCronJob()
    {
        DB::enableQueryLog();
        $now = Carbon::now('UTC')->toDateTimeString();

        // Update expired discount codes whose end_date is less than or equal to the current UTC time
        $expiredDiscountCodes = DiscountCode::where('end_date', '<=', $now)
            ->where('expiration_status', 'active')
            ->where('status', 0) // Use an integer here
            ->get();

        // ->update([
        //     'expiration_status' => 'inactive',
        //     'status' => '0'
        // ]);
        // dd(DB::getQueryLog());
        return   $expiredDiscountCodes;
    }
}
