<?php

namespace App\Http\Controllers\Admin;

use App\Mail\paymentProof;
use App\Models\SalesAgent;
use App\Models\AgentWallet;
use Illuminate\Http\Request;
use App\Models\WithDrawRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WithDrawRequestController extends Controller
{
    public function paymentRequestData()
    {
        $paymentRequests = WithDrawRequest::with('salesAgent.agentWallet')->where('status', 'requested')->latest()->get();
        $json_data["data"] =  $paymentRequests;
        return json_encode($json_data);
    }

    public function paymentRequestIndex()
    {
        return view('admin.withdrawrequest.index');
    }

    public function getAccountDetails(Request $request, $userId)
    {
        try {
            $user = SalesAgent::findOrFail($userId);
            $bankInfos = $user->agentAccounts()->first();

            return view('admin.paymentrequest.useraccountdetails', compact('bankInfos'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showPaymentRequest($id)
    {
        $paymentRequest = WithDrawRequest::with('salesAgent.agentAccounts')->find($id);
        if (!$paymentRequest) {
            return response()->json(['alert' => 'error', 'message' => 'Payment Id Not Found'], 500);
        }
        return response()->json($paymentRequest);
    }
    public function updatePaymentRequest(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpg,jpeg,png|max:1024', // Max size in KB (1MB)
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $paymentRequest = WithDrawRequest::with('salesAgent.agentAccounts')->findOrFail($id);
            $agentWallet = AgentWallet::where('sales_agent_id', $paymentRequest->salesAgent->id)->first();
            if ($agentWallet) {
                $agentWallet->recevied_commission -= $paymentRequest->amount;
                $agentWallet->with_drawal_amount += $paymentRequest->amount;
                $agentWallet->save();
            }

            if ($request->hasFile('image')) {
                $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('admin/assets/images/users/'), $filename);
                $paymentRequest->image = 'public/admin/assets/images/users/' . $filename;
            } else {
                return response()->json(['alert' => 'error', 'message' => 'Error in uploading image!'], 400);
            }
            $paymentRequest->status = 'approved';
            $paymentRequest->save();
            if ($paymentRequest) {
                $data['username'] =  $paymentRequest->salesAgent->name;
                $data['useremail'] =  $paymentRequest->salesAgent->email;
                $data['amount'] =  $paymentRequest->amount;
                $data['image'] =  $paymentRequest->image;
                Mail::to($paymentRequest->salesAgent->email)->send(new paymentProof($data));
            }
            DB::commit();
            return response()->json([
                'alert' => 'success',
                'message' => 'Payment Proof Sent Successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPaymentRequestCount()
    {
        try {
            $paymentRequest = WithDrawRequest::where('status', 'requested')->count();
            return response()->json(['paymentRequest' => $paymentRequest]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
