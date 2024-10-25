<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function reportsIndex()
    {
        return view('admin.reports.index');
    }

    // public function getReportsData($period)
    // {
    //     $query = Order::with('users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem')->where('status', 'completed')->latest()->get();
    //     switch ($period) {
    //         case 'daily':
    //             $query->whereDate('created_at', Carbon::today());
    //             break;
    //         case 'weekly':
    //             $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    //             break;
    //         case 'monthly':
    //             $query->whereMonth('created_at', Carbon::now()->month);
    //             break;
    //         case 'yearly':
    //             $query->whereYear('created_at', Carbon::now()->year);
    //             break;
    //         default:
    //             return response()->json(['error' => 'Invalid period'], 400);
    //     }
    //     $salesData = $query->latest()->get();
    //     $totalAmount = $salesData->sum('total');
    //     return response()->json([
    //         'salesData' => $salesData,
    //         'totalAmount' => $totalAmount
    //     ]);
    // }

    public function getReportsData(Request $request)
    {
        // Initialize the base query with relationships and base conditions
        $query = Order::with(['users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem'])
            ->where('status', 'completed')
            ->latest();

        // Set default period to 'daily' if not provided
        $period = $request->filled('period') ? $request->period : 'daily';

        // Apply the period-based filtering
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
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }

        // Apply date range filtering if both dates are provided
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
        }

        // Apply area, supplier, and product filters
        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }

        if ($request->filled('supplier')) {
            $query->whereHas('orderItem', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier);
            });
        }

        if ($request->filled('product')) {
            $query->whereHas('orderItem', function ($q) use ($request) {
                $q->where('product_id', $request->product);
            });
        }

        // Execute the query
        $salesData = $query->get();
        $totalAmount = $salesData->sum('total');

        return response()->json([
            'salesData' => $salesData,
            'totalAmount' => $totalAmount
        ]);
    }
}
