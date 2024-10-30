<?php

namespace App\Http\Controllers\SalesAgent;

use App\Models\AgentWallet;
use Illuminate\Http\Request;
use App\Models\WithDrawLimit;
use App\Models\WithDrawRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WithDrawRequestController extends Controller
{
    protected function getSalesAgentId()
    {
        return auth()->guard('sales_agent')->id();
    }
    public function requestIndexData(Request $request)
    {
        $status = $request->query('status', 'requested');
        $withDrawRequests = WithDrawRequest::where('sales_agent_id', $this->getSalesAgentId())->where('status', $status)->latest()->get();
        $json_data["data"] = $withDrawRequests;
        return json_encode($json_data);
    }

    public function requestIndex()
    {
        // $categories = WithDrawRequest::where('sales_agent_id', $this->getSalesAgentId())->get();
        return view('salesagent.withdrawrequest.index');
    }



    public function requestCreate(Request $request)
    {
        try {

            $salesAgentId = $this->getSalesAgentId();
            $withdrawLimit = WithDrawLimit::first();
            if (!$withdrawLimit) {
                return response()->json(['alert' => 'error', 'message' => 'Withdraw limits not defined.'], 400);
            }
            $amount = $request->amount;
            if ($amount < $withdrawLimit->min_limits || $amount > $withdrawLimit->max_limits) {
                return response()->json([
                    'alert' => 'error',
                    'message' => 'Requested amount must be between ' . $withdrawLimit->min_limits . ' and ' . $withdrawLimit->max_limits . '.'
                ], 422);
            }
            $agentWallet = AgentWallet::where('sales_agent_id', $salesAgentId)->first();
            if (!$agentWallet || $agentWallet->recevied_commission < $amount) {
                return response()->json([
                    'alert' => 'error',
                    'message' => 'Insufficient balance in the wallet for this request.'
                ], 422);
            }
            $agentWallet->recevied_commission -= $amount;
            $agentWallet->save();
            $withDrawRequest = new WithDrawRequest();
            $withDrawRequest->sales_agent_id = $salesAgentId;
            $withDrawRequest->amount = $amount;
            $withDrawRequest->save();
            return response()->json(['alert' => 'success', 'message' => 'Withdrawal Request Sent Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while creating the request: ' . $e->getMessage()], 500);
        }
    }
}
