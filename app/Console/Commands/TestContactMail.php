<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestContactMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-contact-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ];

        try {
            $mailable = new \App\Mail\ContactFormMail($testData);
            \Illuminate\Support\Facades\Mail::to('admin@unissa.com')->send($mailable);
            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
