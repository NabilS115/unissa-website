<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\AdminNotification;

class CreateTestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test admin notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $order = Order::latest()->first();
        
        if (!$order) {
            $this->error('No orders found');
            return 1;
        }

        $notification = AdminNotification::createOrderNotification($order);
        
        $this->info("✅ Test notification created!");
        $this->info("Notification ID: {$notification->id}");
        $this->info("Order ID: {$order->id}");
        $this->info("Total unread notifications: " . AdminNotification::unreadCount());
        
        return 0;
    }
}
