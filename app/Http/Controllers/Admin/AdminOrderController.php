<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders with filtering and search.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics for dashboard
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'confirmed_orders' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
            'processing_orders' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'total_revenue' => Order::whereIn('status', [
                Order::STATUS_CONFIRMED, 
                Order::STATUS_PROCESSING, 
                Order::STATUS_COMPLETED
            ])->sum('total_price'),
            'recent_orders' => Order::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'product']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses())),
            'notes' => 'nullable|string|max:1000'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus
        ]);

        // Log status change (you could create an order_status_logs table for this)
        \Log::info("Order {$order->id} status changed from {$oldStatus} to {$newStatus} by admin " . auth()->user()->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Order status updated to " . ucfirst($newStatus),
                'status' => $newStatus,
                'status_color' => $order->status_color
            ]);
        }

        return back()->with('success', "Order status updated to " . ucfirst($newStatus));
    }

    /**
     * Bulk update order statuses.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses()))
        ]);

        $updatedCount = Order::whereIn('id', $request->order_ids)
            ->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => "Updated {$updatedCount} orders to " . ucfirst($request->status)
        ]);
    }

    /**
     * Get order statistics for dashboard.
     */
    public function statistics()
    {
        $stats = [
            'orders_by_status' => Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status'),
            
            'orders_by_date' => Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count'),
                DB::raw('sum(total_price) as revenue')
            )
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),

            'top_products' => Order::select('product_id', DB::raw('count(*) as order_count'), DB::raw('sum(quantity) as total_quantity'))
                ->with('product')
                ->groupBy('product_id')
                ->orderBy('order_count', 'desc')
                ->limit(10)
                ->get(),

            'revenue_by_month' => Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('sum(total_price) as revenue'),
                DB::raw('count(*) as order_count')
            )
                ->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_PROCESSING, Order::STATUS_COMPLETED])
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'product']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            // Apply same search logic as index
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $csvData = [];
        $csvData[] = [
            'Order ID', 'Customer Name', 'Customer Email', 'Product Name', 
            'Quantity', 'Unit Price', 'Total Price', 'Status', 'Order Date'
        ];

        foreach ($orders as $order) {
            $csvData[] = [
                $order->id,
                $order->customer_name,
                $order->customer_email,
                $order->product->name,
                $order->quantity,
                number_format($order->unit_price, 2),
                number_format($order->total_price, 2),
                ucfirst($order->status),
                $order->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'orders_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
}
