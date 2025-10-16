<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'ready_for_pickup_orders' => Order::where('status', Order::STATUS_READY_FOR_PICKUP)->count(),
            'picked_up_orders' => Order::where('status', Order::STATUS_PICKED_UP)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'total_revenue' => Order::whereIn('status', [
                Order::STATUS_CONFIRMED, 
                Order::STATUS_PROCESSING, 
                Order::STATUS_READY_FOR_PICKUP,
                Order::STATUS_PICKED_UP
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

        // Send email notification to the user about status change
        if ($order->user && $order->user->email) {
            try {
                \Mail::to($order->user->email)->send(new \App\Mail\OrderStatusChangedMail($order));
            } catch (\Exception $e) {
                \Log::error("Failed to send order status changed email for order {$order->id}: " . $e->getMessage());
            }
        }

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
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:' . implode(',', array_keys(Order::getPaymentStatuses()))
        ]);

        $oldPaymentStatus = $order->payment_status;
        $newPaymentStatus = $request->payment_status;

        $order->update([
            'payment_status' => $newPaymentStatus
        ]);

        // Log payment status change
        \Log::info("Order {$order->id} payment status changed from {$oldPaymentStatus} to {$newPaymentStatus} by admin " . auth()->user()->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Payment status updated to " . ucfirst($newPaymentStatus),
                'payment_status' => $newPaymentStatus
            ]);
        }

        return back()->with('success', "Payment status updated to " . ucfirst($newPaymentStatus));
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
                ->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_PROCESSING, Order::STATUS_READY_FOR_PICKUP, Order::STATUS_PICKED_UP])
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
        Log::info('Order export accessed', ['request' => $request->all()]);
        
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

        try {
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Order export failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Import orders from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        Log::info('Order import started', ['file' => $request->file('csv_file')->getClientOriginalName()]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            
            $csv = array_map('str_getcsv', file($path));
            $header = array_shift($csv);
            
            $imported = 0;
            $errors = [];
            
            foreach ($csv as $index => $row) {
                try {
                    if (count($row) !== count($header)) {
                        $errors[] = "Row " . ($index + 2) . ": Column count mismatch";
                        continue;
                    }
                    
                    $data = array_combine($header, $row);
                    
                    // Map CSV columns to database fields
                    $orderData = [
                        'customer_name' => $data['Customer Name'] ?? $data['customer_name'] ?? '',
                        'customer_email' => $data['Customer Email'] ?? $data['customer_email'] ?? '',
                        'quantity' => intval($data['Quantity'] ?? $data['quantity'] ?? 1),
                        'unit_price' => floatval($data['Unit Price'] ?? $data['unit_price'] ?? 0),
                        'total_price' => floatval($data['Total Price'] ?? $data['total_price'] ?? 0),
                        'status' => $data['Status'] ?? $data['status'] ?? 'pending',
                    ];
                    
                    // Validate required fields
                    if (empty($orderData['customer_name']) || empty($orderData['customer_email'])) {
                        $errors[] = "Row " . ($index + 2) . ": Customer name and email are required";
                        continue;
                    }
                    
                    // Find product by name
                    $productName = $data['Product Name'] ?? $data['product_name'] ?? '';
                    if (empty($productName)) {
                        $errors[] = "Row " . ($index + 2) . ": Product name is required";
                        continue;
                    }
                    
                    $product = Product::where('name', $productName)->first();
                    if (!$product) {
                        $errors[] = "Row " . ($index + 2) . ": Product '{$productName}' not found";
                        continue;
                    }
                    
                    $orderData['product_id'] = $product->id;
                    
                    // Calculate total price if not provided
                    if ($orderData['total_price'] == 0) {
                        $orderData['total_price'] = $orderData['unit_price'] * $orderData['quantity'];
                    }
                    
                    // Create new order
                    Order::create($orderData);
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }
            
            Log::info('Order import completed', [
                'imported' => $imported,
                'errors' => count($errors)
            ]);
            
            $message = "Import completed! {$imported} orders imported.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors occurred.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Order import failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
