<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\ProductCatgeory;
use App\Http\Controllers\Controller;
use App\Models\SalesAgent;
use App\Traits\CountryApiRequestTrait;

class ReportsController extends Controller
{
    use CountryApiRequestTrait;

    public function reportsIndex()
    {
        $url = 'https://api.countrystatecity.in/v1/countries/PK/cities';
        $cities = $this->fetchApiData($url);
        $suppliers = Supplier::where('status', '1')->get();
        $categories = Category::where('status', '1')->where('is_draft','1')->get();
        $products = Product::where('status', '1')->where('is_draft','1')->get();
        $managers = SalesAgent::where('status','1')->where('is_draft','1')->get();
        return view('admin.reports.index', compact('suppliers','products','categories','cities','managers'));
    }

    public function getProductsData(Request $request){
        $categoryId = $request->input('cat_with');
        $products = ProductCatgeory::with(['products' => function($query) {
            $query->where('is_draft', 1); // Corrected by adding a semicolon
        }])
        ->where('category_id', $categoryId)
        ->latest()
        ->get();
        return response()->json($products);
    }
    public function getReportInVoiceDetails($id)
    {
        try {
            $orders = Order::with('users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem.productVariant.products')->where('id', $id)->findOrFail($id);

            return view('admin.reports.reportdetail', compact('orders'));
        } catch (\Exception $e) {
            return response()->json([
                'alert' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function getReportsData(Request $request)
    // {
    //     // return $request;
    //     $query = Order::with(['users:id,name,phone,email', 'salesAgent:id,name,email', 'orderItem.productVariant.products.productCategory.categories'])->where('status', 'completed')
    //         ->latest();
    //     if ($request->filled('startDate') && $request->filled('endDate')) {
    //         $query->whereBetween('created_at', [
    //             Carbon::parse($request->startDate)->startOfDay(),
    //             Carbon::parse($request->endDate)->endOfDay()
    //         ]);
    //     } elseif ($request->filled('startDate')) {
    //         $query->whereDate('created_at', '>=', Carbon::parse($request->startDate));
    //     } elseif ($request->filled('endDate')) {
    //         $query->whereDate('created_at', '<=', Carbon::parse($request->endDate));
    //     }
    //     // else {
    //     //     $period = $request->input('period', 'daily');
    //     //     switch ($period) {
    //     //         case 'daily':
    //     //             $query->whereDate('created_at', Carbon::today());
    //     //             break;
    //     //         case 'weekly':
    //     //             $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    //     //             break;
    //     //         case 'monthly':
    //     //             $query->whereMonth('created_at', Carbon::now()->month);
    //     //             break;
    //     //         case 'yearly':
    //     //             $query->whereYear('created_at', Carbon::now()->year);
    //     //             break;
    //     //     }
    //     // }

    //     // Area, supplier, and product filters
    //     if ($request->filled('area')) {
    //         $query->where('area_id', $request->area);
    //     }
    //     if ($request->filled('city')) {
    //         $query->where('city', $request->city);
    //     }
    //     if ($request->filled('manager')) {
    //         $query->where('sales_agent_id', $request->manager);
    //     }
    //     if ($request->filled('supplier')) {
    //         $query->whereHas('orderItem.productVariant.products', function ($q) use ($request) {
    //             $q->where('supplier_name', $request->supplier);
    //         });
    //     }
    //     if ($request->filled('product')) {
    //         $query->whereHas('orderItem.productVariant.products', function ($q) use ($request) {
    //             $q->where('id', $request->product);
    //         });
    //     }
    //     if ($request->filled('category')) {
    //         $query->whereHas('orderItem.productVariant.products.productCategory.categories', function ($q) use ($request) {
    //             $q->where('id', $request->category);
    //         });
    //     }
    //     $salesData = [];
    //     $totalAmount = [];
    //     if (!empty(array_filter($request->only(['startDate', 'endDate', 'supplier', 'area', 'product', 'category', 'city','manager'])))) {
    //         $salesData = $query->get();
    //         $totalAmount = $salesData->sum(function ($order) {
    //             return $order->discounted_total ?? $order->total;
    //         });
    //     }
    //     return response()->json([
    //         'salesData' => $salesData,
    //         'totalAmount' => $totalAmount,
    //     ]);

    // }
    public function getReportsData(Request $request)
    {
        // Initialize the query with relations
        $query = Order::with([
            'users:id,name,phone,email',
            'salesAgent:id,id_number,name,email',
            'orderItem.productVariant.products.productCategory.categories'
        ])->where('status', 'completed')
          ->latest();

        // Check if the request has no input data (empty request)
        if (!$request->input() && !$request->all()) {
            // If request is empty, return all data
            $salesData = $query->get();
            $totalAmount = $salesData->sum(function ($order) {
                return $order->discounted_total ?? $order->total;
            });
        } else {
            // Apply filters if the request is not empty
            if ($request->filled('startDate') && $request->filled('endDate')) {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->startDate)->startOfDay(),
                    Carbon::parse($request->endDate)->endOfDay()
                ]);
            } elseif ($request->filled('startDate')) {
                $query->whereDate('created_at', '>=', Carbon::parse($request->startDate));
            } elseif ($request->filled('endDate')) {
                $query->whereDate('created_at', '<=', Carbon::parse($request->endDate));
            }

            // Area, supplier, and product filters
            if ($request->filled('area')) {
                $query->where('area_id', $request->area);
            }
            if ($request->filled('city')) {
                $query->where('city', $request->city);
            }
            if ($request->filled('manager')) {
                $query->where('sales_agent_id', $request->manager);
            }
            if ($request->filled('supplier')) {
                $query->whereHas('orderItem.productVariant.products', function ($q) use ($request) {
                    $q->where('supplier_name', $request->supplier);
                });
            }
            if ($request->filled('product')) {
                $query->whereHas('orderItem.productVariant.products', function ($q) use ($request) {
                    $q->where('id', $request->product);
                });
            }
            if ($request->filled('category')) {
                $query->whereHas('orderItem.productVariant.products.productCategory.categories', function ($q) use ($request) {
                    $q->where('id', $request->category);
                });
            }

            // Fetch filtered data
            $salesData = $query->get();
            $totalAmount = $salesData->sum(function ($order) {
                return $order->discounted_total ?? $order->total;
            });
        }

        // Return the data as a JSON response
        return response()->json([
            'salesData' => $salesData,
            'totalAmount' => $totalAmount,
        ]);
    }


}
