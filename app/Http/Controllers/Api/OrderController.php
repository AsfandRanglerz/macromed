<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductVaraint;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Models\SalesAgent;

class OrderController extends Controller
{
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
            if (!auth()->check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not authenticated',
                ], 401);
            }

            $userId = auth()->id();
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
            $cart->total = $request->total;
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
                    $orderItem->price = $product['price'];
                    $orderItem->subtotal = $product['quantity'] * $product['price'];
                    $orderItem->save();
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product variant not found: ' . $product['variant_number'],
                    ], 400);
                }
            }
            $cart->product_commission = $totalCommission;
            $cart->save();
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
}
