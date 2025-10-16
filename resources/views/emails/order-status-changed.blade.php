<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated - Unissa Cafe</title>
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
            border-bottom: 3px solid #0d9488;
        }
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            border-radius: 12px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }
        .header h1 {
            color: #0d9488;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            color: #6b7280;
            margin: 8px 0 0;
            font-size: 16px;
        }
        .order-info {
            background: #f0fdfa;
            border: 2px solid #5eead4;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .order-info h2 {
            color: #0d9488;
            margin: 0 0 16px;
            font-size: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }
        .info-item {
            padding: 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .info-item label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }
        .info-item value {
            font-size: 16px;
            color: #111827;
            font-weight: 600;
            display: block;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 16px 0;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-confirmed {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-processing {
            background: #f3f4f6;
            color: #374151;
        }
        .status-ready_for_pickup {
            background: #f0fdfa;
            color: #0d9488;
        }
        .status-picked_up {
            background: #d1fae5;
            color: #065f46;
        }
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">U</div>
            <h1>Order Status Updated</h1>
            <p>Your order status has changed at Unissa Cafe</p>
        </div>

        <!-- Order Information -->
        <div class="order-info">
            <h2>Order #{{ $order->id }}</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Order Date</label>
                    <value>{{ $order->created_at->format('M d, Y \a\t g:i A') }}</value>
                </div>
                <div class="info-item">
                    <label>Status</label>
                    <value>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </value>
                </div>
            </div>
            <div class="info-item" style="grid-column: 1 / -1;">
                <label>Customer</label>
                <value>{{ $order->customer_name }}</value>
                @if($order->customer_phone)
                    <br><small style="color: #6b7280;">{{ $order->customer_phone }}</small>
                @endif
            </div>
        </div>

        <!-- Status Message -->
        <div class="order-info">
            <h2>What does this mean?</h2>
            <p>Your order status is now: <strong>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</strong>.</p>
            @if($order->status === 'ready_for_pickup')
                <p>Your order is ready for pickup! Please come to Unissa Cafe to collect it at your convenience.</p>
            @elseif($order->status === 'picked_up')
                <p>Your order has been picked up. Thank you for choosing Unissa Cafe!</p>
            @elseif($order->status === 'cancelled')
                <p>Your order has been cancelled. If you have any questions, please contact us.</p>
            @else
                <p>We are processing your order. You will receive further updates as your order progresses.</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing Unissa Cafe!</p>
            <p>If you have any questions, please contact us or visit our cafe.</p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
