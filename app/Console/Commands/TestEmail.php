<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'test:email {recipient}';
    protected $description = 'Send a test email to verify mail configuration';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        try {
            Mail::raw('This is a test email from UNISSA Cafe system. If you receive this, your email configuration is working correctly!', function ($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('Test Email from UNISSA Cafe - Email Configuration Test');
            });
            
            $this->info("✅ Test email sent successfully to: {$recipient}");
            $this->info("Check the recipient's inbox (including spam folder).");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            $this->error("Please check your email configuration in .env file.");
        }
    }
}