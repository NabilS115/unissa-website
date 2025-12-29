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
            <h3>Your Order Items</h3>
            @foreach($order->orderItems as $orderItem)
                <div class="product-item">
                    @if($orderItem->product && $orderItem->product->category === 'Printing Services')
                        <div class="product-image" style="background: linear-gradient(135deg, #0d9488, #10b981); color: white; font-size: 20px;">
                            🖨️
                        </div>
                    @else
                        <div class="product-image">
                            🛍️
                        </div>
                    @endif
                    <div class="product-info">
                        <div class="product-name">{{ $orderItem->product ? $orderItem->product->name : 'Product' }}</div>
                        @if($orderItem->product && $orderItem->product->desc)
                            <div class="product-details-text">{{ Str::limit($orderItem->product->desc, 100) }}</div>
                        @endif
                        @if($orderItem->notes)
                            <div class="product-details-text"><strong>Notes:</strong> {{ Str::limit($orderItem->notes, 100) }}</div>
                        @endif
                        <div class="product-price">
                            <span>Quantity: {{ $orderItem->quantity }}</span>
                            <span>B${{ number_format($orderItem->unit_price, 2) }} each</span>
                            <span style="font-weight: bold; color: #0d9488;">Subtotal: B${{ number_format($orderItem->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
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
                    <div style="background: #f0fdf4; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #4ade80;">
                        <div style="text-align: center;">
                            <span style="font-size: 24px; font-weight: bold; color: #16a34a;">B${{ number_format($order->total_price, 2) }}</span>
                            <div style="font-size: 14px; color: #15803d; margin-top: 4px;">Total Amount Due (Please bring exact amount)</div>
                        </div>
                    </div>
                    
                    <h4 style="color: #16a34a; margin: 0 0 12px; font-size: 16px; font-weight: 600;">📋 Payment Details:</h4>
                    <ul style="margin: 0; padding: 0 0 0 20px; color: #15803d;">
                        <li style="margin-bottom: 12px;"><strong>Payment Due:</strong> When you come to collect your order</li>
                        <li style="margin-bottom: 12px;"><strong>Accepted:</strong> Cash only (exact amount preferred)</li>
                        <li style="margin-bottom: 12px;"><strong>Bring This Email:</strong> Show this confirmation for faster service</li>
                        <li style="margin-bottom: 12px;"><strong>Order ID:</strong> #{{ $order->id }} (mention this when collecting)</li>
                    </ul>
                    
                    <div style="background: #ecfdf5; padding: 16px; border-radius: 8px; border: 1px solid #a7f3d0; margin-top: 16px;">
                        <h4 style="color: #16a34a; margin: 0 0 8px; font-size: 14px; font-weight: 600;">📍 Collection Information:</h4>
                        <p style="margin: 0; color: #15803d; font-size: 14px;">
                            <strong>Location:</strong> Unissa Cafe<br>
                            <strong>Contact:</strong> +673 8123456 (Call/WhatsApp)<br>
                            <strong>Note:</strong> We'll notify you when your order is ready for pickup
                        </p>
                    </div>
                </div>
            </div>
        @elseif($order->payment_method === 'bank_transfer')
            <div class="next-steps" style="background: linear-gradient(135deg, #f0fdfa, #ccfbf1); border: 2px solid #14b8a6;">
                <h3 style="color: #0d9488; margin: 0 0 16px; font-size: 18px;">🏦 BIBD Bank Transfer Instructions</h3>
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #5eead4;">
                    
                    <div style="background: #fed7d7; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fc8181; text-align: center;">
                        <div style="color: #c53030; font-weight: bold; margin-bottom: 8px;">⏰ URGENT: Transfer Required Within 24 Hours</div>
                        <div style="color: #742a2a; font-size: 14px;">Your order may be cancelled if payment is not received within 24 hours</div>
                    </div>
                    
                    <div style="background: #f0fdfa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #14b8a6;">
                        <h4 style="color: #0d9488; margin: 0 0 16px; font-size: 16px; font-weight: 600; text-align: center;">💳 Bank Transfer Details</h4>
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
                                <span style="color: #dc2626; font-weight: bold; font-size: 18px; background: #fef2f2; padding: 8px 12px; border-radius: 6px;">B${{ number_format($order->total_price, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 600; color: #374151;">Reference:</span>
                                <span style="background: #fbbf24; color: #92400e; padding: 8px 12px; border-radius: 6px; font-weight: 600;">{{ $order->customer_phone }} (Your Phone)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #fef3c7; padding: 16px; border-radius: 8px; border: 1px solid #f59e0b; margin-bottom: 16px;">
                        <h4 style="color: #92400e; margin: 0 0 12px; font-size: 16px; font-weight: 600;">📝 Step-by-Step Transfer Instructions:</h4>
                        <ol style="margin: 0; padding: 0 0 0 20px; color: #92400e;">
                            <li style="margin-bottom: 8px;">Login to your BIBD mobile app or visit a branch</li>
                            <li style="margin-bottom: 8px;">Select <strong>"Transfer"</strong> option</li>
                            <li style="margin-bottom: 8px;">Enter account number: <strong>{{ \App\Models\ContentBlock::get('bank_account_number', '[Account Number]', 'text', 'bank-transfer') }}</strong></li>
                            <li style="margin-bottom: 8px;">Transfer exactly <strong>B${{ number_format($order->total_price, 2) }}</strong></li>
                            <li style="margin-bottom: 8px;">Use your phone number <strong>{{ $order->customer_phone }}</strong> as reference</li>
                            <li style="margin-bottom: 8px;">Complete the transfer and save the receipt</li>
                        </ol>
                    </div>

                    <div style="background: #dbeafe; padding: 16px; border-radius: 8px; border: 1px solid #3b82f6; margin-bottom: 16px;">
                        <h4 style="color: #1e40af; margin: 0 0 8px; font-size: 16px; font-weight: 600;">📱 After Successful Transfer:</h4>
                        <ol style="margin: 0; padding: 0 0 0 20px; color: #1e40af;">
                            <li style="margin-bottom: 8px;">Take a screenshot or photo of your transfer receipt</li>
                            <li style="margin-bottom: 8px;">WhatsApp the receipt to <strong>+673 8123456</strong></li>
                            <li style="margin-bottom: 8px;">Include your order number: <strong>#{{ $order->id }}</strong></li>
                            <li style="margin-bottom: 8px;">We'll confirm receipt and start preparing your order!</li>
                        </ol>
                    </div>

                    <div style="background: #ecfdf5; padding: 16px; border-radius: 8px; border: 1px solid #4ade80; text-align: center;">
                        <p style="margin: 0; color: #16a34a; font-weight: 600;">
                            💬 <strong>WhatsApp:</strong> +673 8123456 | <strong>Order ID:</strong> #{{ $order->id }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif($order->payment_method === 'online' || $order->payment_method === 'paypal' || $order->payment_method === 'card')
            <div class="next-steps" style="background: linear-gradient(135deg, #fef7ff, #f3e8ff); border: 2px solid #8b5cf6;">
                <h3 style="color: #7c3aed; margin: 0 0 16px; font-size: 18px;">💳 Online Payment Instructions</h3>
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #c4b5fd;">
                    
                    @if($order->payment_status === 'paid')
                        <div style="background: #ecfdf5; padding: 16px; border-radius: 8px; border: 1px solid #4ade80; text-align: center; margin-bottom: 16px;">
                            <div style="color: #16a34a; font-weight: bold; font-size: 16px; margin-bottom: 8px;">✅ Payment Successfully Processed</div>
                            <div style="color: #15803d; font-size: 14px;">Your payment of B${{ number_format($order->total_price, 2) }} has been confirmed</div>
                        </div>
                    @elseif($order->payment_status === 'pending')
                        <div style="background: #fef3c7; padding: 16px; border-radius: 8px; border: 1px solid #f59e0b; margin-bottom: 16px;">
                            <div style="color: #92400e; font-weight: bold; font-size: 16px; margin-bottom: 8px;">⏳ Payment Processing</div>
                            <div style="color: #92400e; font-size: 14px;">
                                Your online payment is currently being processed. You will receive an update shortly.
                            </div>
                        </div>
                        
                        <h4 style="color: #7c3aed; margin: 0 0 12px; font-size: 16px; font-weight: 600;">📋 Payment Details:</h4>
                        <ul style="margin: 0 0 16px; padding: 0 0 0 20px; color: #7c3aed;">
                            <li style="margin-bottom: 8px;"><strong>Amount:</strong> B${{ number_format($order->total_price, 2) }}</li>
                            <li style="margin-bottom: 8px;"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</li>
                            <li style="margin-bottom: 8px;"><strong>Status:</strong> Processing</li>
                            <li style="margin-bottom: 8px;"><strong>Order ID:</strong> #{{ $order->id }}</li>
                        </ul>
                        
                        <div style="background: #dbeafe; padding: 16px; border-radius: 8px; border: 1px solid #3b82f6;">
                            <p style="margin: 0; color: #1e40af; font-size: 14px; text-align: center;">
                                💬 Questions about your payment? Contact us at <strong>+673 8123456</strong>
                            </p>
                        </div>
                    @else
                        <div style="background: #fef2f2; padding: 16px; border-radius: 8px; border: 1px solid #fc8181; margin-bottom: 16px;">
                            <div style="color: #c53030; font-weight: bold; font-size: 16px; margin-bottom: 8px;">❌ Payment Issue Detected</div>
                            <div style="color: #742a2a; font-size: 14px;">
                                There seems to be an issue with your online payment. Please contact us immediately.
                            </div>
                        </div>
                        
                        <div style="background: #dbeafe; padding: 16px; border-radius: 8px; border: 1px solid #3b82f6;">
                            <h4 style="color: #1e40af; margin: 0 0 8px; font-size: 16px; font-weight: 600;">📞 Contact Support:</h4>
                            <p style="margin: 0; color: #1e40af; font-size: 14px;">
                                <strong>WhatsApp/Call:</strong> +673 8123456<br>
                                <strong>Order Reference:</strong> #{{ $order->id }}<br>
                                <strong>Email:</strong> Show this email when contacting us
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Fallback for any other payment methods -->
            <div class="next-steps" style="background: linear-gradient(135deg, #f9fafb, #f3f4f6); border: 2px solid #9ca3af;">
                <h3 style="color: #374151; margin: 0 0 16px; font-size: 18px;">💼 Payment Information</h3>
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #d1d5db;">
                    <div style="background: #f3f4f6; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                        <h4 style="color: #374151; margin: 0 0 12px; font-size: 16px; font-weight: 600;">📋 Payment Details:</h4>
                        <ul style="margin: 0; padding: 0 0 0 20px; color: #374151;">
                            <li style="margin-bottom: 8px;"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</li>
                            <li style="margin-bottom: 8px;"><strong>Amount:</strong> B${{ number_format($order->total_price, 2) }}</li>
                            <li style="margin-bottom: 8px;"><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</li>
                            <li style="margin-bottom: 8px;"><strong>Order ID:</strong> #{{ $order->id }}</li>
                        </ul>
                    </div>
                    
                    <div style="background: #dbeafe; padding: 16px; border-radius: 8px; border: 1px solid #3b82f6;">
                        <p style="margin: 0; color: #1e40af; font-weight: 600; text-align: center;">
                            📞 For payment instructions, please contact us at <strong>+673 8123456</strong>
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