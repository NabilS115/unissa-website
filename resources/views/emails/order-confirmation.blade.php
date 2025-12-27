<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Unissa Cafe</title>
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
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
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
        .product-details {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .product-details h3 {
            color: #374151;
            margin: 0 0 20px;
            font-size: 18px;
        }
        .product-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .product-image {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            flex-shrink: 0;
        }
        .product-info {
            flex: 1;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin: 0 0 8px;
        }
        .product-details-text {
            color: #6b7280;
            font-size: 14px;
            margin: 0 0 12px;
        }
        .product-price {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 600;
        }
        .total-section {
            text-align: center;
            padding: 24px;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            color: white;
            border-radius: 12px;
            margin: 32px 0;
        }
        .total-section h3 {
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: normal;
            opacity: 0.9;
        }
        .total-amount {
            font-size: 32px;
            font-weight: bold;
            margin: 0;
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
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        .next-steps {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
        }
        .next-steps h3 {
            color: #374151;
            margin: 0 0 16px;
            font-size: 18px;
        }
        .next-steps ul {
            margin: 0;
            padding: 0 0 0 20px;
            color: #6b7280;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
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
            .product-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/UNISSA_CAFE.png') }}" alt="Unissa Cafe Logo">
            </div>
            <h1>Order Confirmation</h1>
            <p>Thank you for your order at Unissa Cafe</p>
        </div>

        <!-- Order Information -->
        <div class="order-info">
            <h2>Order Details</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Order Number</label>
                    <value>#{{ $order->id }}</value>
                </div>
                <div class="info-item">
                    <label>Order Date</label>
                    <value>{{ $order->created_at->format('M d, Y \a\t g:i A') }}</value>
                </div>
                <div class="info-item">
                    <label>Payment Method</label>
                    <value>{{ $order->payment_method_display }}</value>
                </div>
                <div class="info-item">
                    <label>Payment Status</label>
                    <value>
                        <span class="status-badge status-{{ $order->payment_status }}">
                            {{ ucfirst($order->payment_status) }}
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

        <!-- Product Details -->
        <div class="product-details">
            <h3>Your Order</h3>
            <div class="product-item">
                <div class="product-image">
                    🛍️
                </div>
                <div class="product-info">
                    <div class="product-name">{{ $order->product ? $order->product->name : 'Product' }}</div>
                    @if($order->product && $order->product->description)
                        <div class="product-details-text">{{ Str::limit($order->product->description, 100) }}</div>
                    @endif
                    <div class="product-price">
                        <span>Quantity: {{ $order->quantity }}</span>
                        <span>B${{ number_format($order->unit_price, 2) }} each</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="total-section">
            <h3>Total Amount</h3>
            <div class="total-amount">B${{ number_format($order->total_price, 2) }}</div>
        </div>

        <!-- Payment Instructions -->
        @if($order->payment_method === 'cash')
            <div class="next-steps" style="background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 2px solid #4ade80;">
                <h3 style="color: #16a34a; margin: 0 0 16px; font-size: 18px;">💰 Cash Payment Instructions</h3>
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #bbf7d0;">
                    <ul style="margin: 0; padding: 0 0 0 20px; color: #15803d;">
                        <li style="margin-bottom: 12px;"><strong>Amount Due:</strong> B${{ number_format($order->total_price, 2) }} (please bring exact amount)</li>
                        <li style="margin-bottom: 12px;"><strong>Payment Due:</strong> When you come to collect your order</li>
                        <li style="margin-bottom: 12px;"><strong>Bring This Email:</strong> Show this confirmation for faster service</li>
                        <li style="margin-bottom: 12px;"><strong>Contact:</strong> Call/WhatsApp +673 8123456 for any questions</li>
                        <li style="margin-bottom: 12px;"><strong>Location:</strong> Unissa Cafe - We'll notify you when ready!</li>
                    </ul>
                </div>
            </div>
        @elseif($order->payment_method === 'bank_transfer')
            <div class="next-steps" style="background: linear-gradient(135deg, #f0fdfa, #ccfbf1); border: 2px solid #14b8a6;">
                <h3 style="color: #0d9488; margin: 0 0 16px; font-size: 18px;">🏦 BIBD Bank Transfer Instructions</h3>
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #5eead4;">
                    <div style="background: #f0fdfa; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #14b8a6;">
                        <div style="display: grid; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: #374151;">Bank:</span>
                                <span style="color: #0d9488; font-weight: 600;">BIBD (Bank Islam Brunei Darussalam)</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: #374151;">Account Name:</span>
                                <span style="color: #0d9488; font-weight: 600;">{{ \App\Models\ContentBlock::get('bank_account_name', 'UNISSA Café', 'text', 'bank-transfer') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: #374151;">Account Number:</span>
                                <span style="font-family: monospace; background: #14b8a6; color: white; padding: 8px 12px; border-radius: 6px; font-weight: bold; font-size: 16px;">{{ \App\Models\ContentBlock::get('bank_account_number', '[Account Number]', 'text', 'bank-transfer') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: #374151;">Amount:</span>
                                <span style="color: #dc2626; font-weight: bold; font-size: 18px;">B${{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #fef3c7; padding: 16px; border-radius: 8px; border: 1px solid #f59e0b; margin-bottom: 16px;">
                        <h4 style="color: #92400e; margin: 0 0 12px; font-size: 16px; font-weight: 600;">📝 Transfer Instructions:</h4>
                        <ul style="margin: 0; padding: 0 0 0 20px; color: #92400e;">
                            <li style="margin-bottom: 8px;">Transfer exactly <strong>B${{ number_format($order->total_price, 2) }}</strong> from your BIBD account</li>
                            <li style="margin-bottom: 8px;">Use your <strong>phone number</strong> as transfer reference</li>
                            <li style="margin-bottom: 8px;">Transfer is instant between BIBD accounts</li>
                            <li style="margin-bottom: 8px;">Keep your transfer receipt for verification</li>
                            <li style="margin-bottom: 8px;">WhatsApp confirmation to <strong>+673 8123456</strong></li>
                        </ul>
                    </div>

                    <div style="background: #dbeafe; padding: 16px; border-radius: 8px; border: 1px solid #3b82f6;">
                        <p style="margin: 0; color: #1e40af; font-weight: 600; text-align: center;">
                            💬 After Transfer: WhatsApp us your transfer receipt at <strong>+673 8123456</strong>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Next Steps -->
        <div class="next-steps">
            <h3>What's Next?</h3>
            <ul>
                @if($order->payment_status === 'pending' && $order->payment_method !== 'bank_transfer')
                    <li><strong>Payment:</strong> Complete payment as instructed above</li>
                @elseif($order->payment_method === 'bank_transfer')
                    <li><strong>Payment:</strong> Complete BIBD bank transfer and send confirmation via WhatsApp</li>
                @else
                    <li><strong>Payment:</strong> Your payment has been processed successfully</li>
                @endif
                <li><strong>Preparation:</strong> We'll start preparing your order and notify you when it's ready</li>
                <li><strong>Pickup:</strong> Come to Unissa Cafe to collect your order when notified</li>
                <li><strong>Track:</strong> You can track your order status in your account</li>
            </ul>
        </div>

        <!-- Action Button -->
        <div style="text-align: center;">
            <a href="{{ route('user.orders.show', $order) }}" class="button">
                View Order Details
            </a>
        </div>

        @if($order->notes || $order->pickup_notes)
            <!-- Notes -->
            <div class="next-steps">
                <h3>Additional Notes</h3>
                @if($order->pickup_notes)
                    <p><strong>Pickup Notes:</strong> {{ $order->pickup_notes }}</p>
                @endif
                @if($order->notes)
                    <p><strong>Order Notes:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        @endif

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