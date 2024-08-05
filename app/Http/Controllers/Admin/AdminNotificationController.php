<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SalesAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SalesAgentNotification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Validator;

class AdminNotificationController extends Controller
{
    public function adminNotificationIndex()
    {
        return view('admin.adminnotification.index');
    }
    public function adminNotificationCreate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $userIds = json_decode($request->input('user_name'));
            $customers = User::whereIn('user_type', $userIds)->get();
            $salesAgents = SalesAgent::whereIn('user_type', $userIds)->get();
            if ($customers) {
                foreach ($customers as $customer) {
                    UserNotification::create([
                        'customer_id' => $customer->id,
                        'message' => $request->message
                    ]);
                }
            }

            if ($salesAgents) {
                foreach ($salesAgents as $salesAgent) {
                    SalesAgentNotification::create([
                        'sales_agent_id' => $salesAgent->id,
                        'message' => $request->message
                    ]);
                }
            }

            return response()->json(['success' => 'Notification created successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while creating the notification: ' . $e->getMessage()], 500);
        }
    }
}
