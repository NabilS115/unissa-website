<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received - Unissa Cafe Admin</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #dc2626;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #0d9488, #06b6d4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .title {
            color: #dc2626;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
            margin: 10px 0 0;
        }
        .order-info {
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 12px;
            padding: 24px;
            margin: 30px 0;
        }
        .order-id {
            color: #dc2626;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
        }
        .customer-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #374151;
            flex: 1;
        }
        .detail-value {
            color: #6b7280;
            flex: 2;
            text-align: right;
        }
        .items-section {
            margin: 30px 0;
        }
        .section-title {
            color: #1f2937;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            margin-bottom: 12px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #0d9488;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .item-quantity {
            color: #6b7280;
            font-size: 14px;
        }
        .item-price {
            font-weight: 600;
            color: #0d9488;
            font-size: 16px;
        }
        .total-section {
            background: #0f172a;
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }
        .total-label {
            font-size: 18px;
            margin-bottom: 8px;
        }
        .total-amount {
            font-size: 36px;
            font-weight: bold;
            color: #10b981;
        }
        .payment-info {
            background: #fffbeb;
            border: 2px solid #fed7aa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .payment-method {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 10px;
        }
        .admin-actions {
            background: #f0f9ff;
            border: 2px solid #bae6fd;
            border-radius: 12px;
            padding: 24px;
            margin: 30px 0;
            text-align: center;
        }
        .action-button {
            display: inline-block;
            background: #0d9488;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .action-button:hover {
            background: #0f766e;
            transform: translateY(-2px);
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .notes {
            background: #fef7ff;
            border: 1px solid #e879f9;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🛒</div>
            <h1 class="title">New Order Alert!</h1>
            <p class="subtitle">A customer has just placed an order</p>
        </div>

        <div class="order-info">
            <div class="order-id">Order #{{ $order->id }}</div>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('M j, Y - g:i A') }}</p>
            <p><strong>Status:</strong> 
                <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </p>
        </div>

        <div class="customer-details">
            <h3 style="color: #1f2937; margin-top: 0;">Customer Information</h3>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{ $order->customer_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $order->customer_email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">{{ $order->customer_phone }}</span>
            </div>
        </div>

        <div class="payment-info">
            <div class="payment-method">Payment Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
            @if($order->payment_method === 'bank_transfer' && $order->payment_reference)
                <div style="color: #92400e;">Reference: {{ $order->payment_reference }}</div>
            @endif
            <div style="color: #92400e; margin-top: 8px;">
                Payment Status: 
                <span style="background: #fed7aa; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                </span>
            </div>
        </div>

        <div class="items-section">
            <h3 class="section-title">Order Items</h3>
            @foreach($order->orderItems as $item)
                <div class="item">
                    <div class="item-info">
                        <div class="item-name">{{ $item->product->name ?? 'Product' }}</div>
                        <div class="item-quantity">Quantity: {{ $item->quantity }} × B${{ number_format($item->unit_price, 2) }}</div>
                        @if($item->notes)
                            <div style="color: #6b7280; font-size: 12px; margin-top: 4px;">
                                <strong>Notes:</strong> {{ $item->notes }}
                            </div>
                        @endif
                    </div>
                    <div class="item-price">B${{ number_format($item->total_price, 2) }}</div>
                </div>
            @endforeach
        </div>

        <div class="total-section">
            <div class="total-label">Total Order Amount</div>
            <div class="total-amount">B${{ number_format($order->total_price, 2) }}</div>
        </div>

        @if($order->pickup_notes || $order->notes)
            <div class="notes">
                <h4 style="margin-top: 0; color: #7c2d12;">Special Instructions:</h4>
                @if($order->pickup_notes)
                    <p><strong>Pickup Notes:</strong> {{ $order->pickup_notes }}</p>
                @endif
                @if($order->notes)
                    <p><strong>Additional Notes:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        @endif

        <div class="admin-actions">
            <h3 style="color: #1f2937; margin-top: 0;">Quick Actions</h3>
            <p>Manage this order from your admin panel:</p>
            <a href="{{ url('/admin/orders/' . $order->id) }}" class="action-button">View Order Details</a>
            <a href="{{ url('/admin/orders') }}" class="action-button" style="background: #059669;">View All Orders</a>
        </div>

        <div class="footer">
            <p>This is an automated notification from Unissa Cafe Admin System</p>
            <p>Please do not reply to this email. For support, contact your system administrator.</p>
        </div>
    </div>
</body>
</html>