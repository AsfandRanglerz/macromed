<?php

namespace App\Http\Controllers\SalesAgent;

use App\Http\Controllers\Controller;
use App\Models\SalesAgentNotification;


class SalesAgentNotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = SalesAgentNotification::where('sales_agent_id', auth()->guard('sales_agent')->id())->latest()->limit(10)->get();
        $unreadCount = SalesAgentNotification::where('status', '0')->count();
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
    public function markAllAsRead()
    {
        SalesAgentNotification::where('sales_agent_id', auth()->guard('sales_agent')->id())->where('status', '0')->update(['status' => '1']);
        return response()->json(['message' => 'All notifications marked as read']);
    }
    public function markAsRead($id)
    {
        $notification = SalesAgentNotification::find($id);
        if ($notification) {
            $notification->status = '1';
            $notification->save();
            return response()->json(['message' => 'Notification marked as read']);
        }
        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function notificationScreen()
    {
        $notificationScreens = SalesAgentNotification::where('sales_agent_id', auth()->guard('sales_agent')->id())->latest()->get();
        return view('salesagent.notifcationscreen.index', compact('notificationScreens'));
    }
}
