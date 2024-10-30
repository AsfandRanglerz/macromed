<?php

namespace App\Http\Controllers\SalesAgent;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function reportsUserIndex()
    {

        return view('salesagent.reports.index');
    }

    public function getUserReportsData(Request $request)
    {
        $query = Order::with('users:id,name,phone,email')->where('status', 'completed')->where('sales_agent_id', auth()->guard('sales_agent')->id())
            ->latest();
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->startDate)->startOfDay(),
                Carbon::parse($request->endDate)->endOfDay()
            ]);
        } elseif ($request->filled('startDate')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->startDate));
        } elseif ($request->filled('endDate')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->endDate));
        } else {
            $period = $request->input('period', 'daily');
            switch ($period) {
                case 'daily':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'weekly':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'monthly':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    break;
                case 'yearly':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        }


        // Fetch results
        $salesData = $query->get();
        $totalAmount = $salesData->sum('product_commission');

        // Return JSON response
        return response()->json([
            'salesData' => $salesData,
            'totalAmount' => $totalAmount,
        ]);
    }
}
