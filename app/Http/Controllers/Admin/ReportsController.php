<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Supplier;

class ReportsController extends Controller
{
    public function reportsIndex()
    {
        $suppliers = Supplier::where('status', '1')->get();
        return view('admin.reports.index', compact('suppliers'));
    }


    public function getReportsData(Request $request)
    {
        $query = Order::with(['users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem'])->where('status', 'completed')
            ->latest();
        $period = $request->input('period', 'daily');
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
        } elseif ($request->filled('startDate')) {
            $query->whereDate('created_at', '>=', $request->startDate);
        } elseif ($request->filled('endDate')) {
            $query->whereDate('created_at', '<=', $request->endDate);
        } else {
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



        // Date range filter
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('created_at', [$request->startDate, $request->endDate]);
        } elseif ($request->filled('startDate')) {
            $query->whereDate('created_at', '>=', $request->startDate);
        } elseif ($request->filled('endDate')) {
            $query->whereDate('created_at', '<=', $request->endDate);
        }

        // Area filter
        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }

        // Supplier filter
        if ($request->filled('supplier')) {
            $query->whereHas('orderItem.productVariant.products', function ($q) use ($request) {
                $q->where('supplier_name', $request->supplier);
            });
        }

        // Product filter
        if ($request->filled('product')) {
            $query->whereHas('orderItem', function ($q) use ($request) {
                $q->where('product_id', $request->product);
            });
        }

        // Fetch results
        $salesData = $query->get();
        $totalAmount = $salesData->sum('total');

        // Return JSON response
        return response()->json([
            'salesData' => $salesData,
            'totalAmount' => $totalAmount,
        ]);
    }
}
