<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminOrderNotificationMail;
use App\Models\Order;

class TestAdminNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:admin-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Get the latest order for testing
            $order = Order::with('orderItems.product')->latest()->first();
            
            if (!$order) {
                $this->error('No orders found for testing');
                return 1;
            }
            
            $adminEmail = config('mail.admin_email');
            
            $this->info("Testing admin notification for order #{$order->id}");
            $this->info("Admin email: {$adminEmail}");
            $this->info("Customer: {$order->customer_name} ({$order->customer_email})");
            $this->info("Total: B\${$order->total_price}");
            
            $this->info('Sending admin notification email...');
            
            Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
            
            $this->info('✅ Admin notification email sent successfully!');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error sending admin notification:");
            $this->error("Message: " . $e->getMessage());
            $this->error("Code: " . $e->getCode());
            $this->error("File: " . $e->getFile() . " Line: " . $e->getLine());
            
            return 1;
        }
    }
}
