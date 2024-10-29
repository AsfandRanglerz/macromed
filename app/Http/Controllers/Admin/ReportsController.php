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
        // $query = Order::with(['users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem.productVariant.products'])->where('status', 'completed')
        // ->latest()->get();
        // return $query;
        return view('admin.reports.index', compact('suppliers'));
    }

    public function getReportsData(Request $request)
    {
        $query = Order::with(['users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem.productVariant'])->where('status', 'completed')
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

        // Area, supplier, and product filters
        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }
        if ($request->filled('supplier')) {
            $query->whereHas('orderItem.productVariant.products', function ($q) use ($request) {
                $q->where('supplier_name', $request->supplier);
            });
        }
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
