<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Send email to admin
            $this->sendContactEmail($validated);

            return back()->with('success', 'Your message has been sent successfully! We\'ll get back to you soon.');
        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            return back()->with('error', 'There was an error sending your message. Please try again later.');
        }
    }

    /**
     * Send contact email
     */
    private function sendContactEmail($data)
    {
        $adminEmail = config('mail.admin_email', 'admin@unissa.com');
        
        Mail::to($adminEmail)->send(new ContactFormMail($data));
    }
}
