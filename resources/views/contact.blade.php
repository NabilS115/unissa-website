@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="w-full flex flex-col items-center bg-white min-h-screen pt-8">
    <div class="w-full max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold text-teal-700 mb-2 text-center">Contact Us</h2>
        <p class="text-lg text-gray-600 mb-8 text-center">Have questions? We're here to help.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Contact Form -->
            <form class="bg-white rounded-lg shadow p-8 flex flex-col gap-4" action="#" method="POST">
                <label class="font-semibold">Name</label>
                <input type="text" name="name" class="border border-teal-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" required>
                <label class="font-semibold">Email</label>
                <input type="email" name="email" class="border border-teal-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" required>
                <label class="font-semibold">Subject</label>
                <input type="text" name="subject" class="border border-teal-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400">
                <label class="font-semibold">Message</label>
                <textarea name="message" rows="4" class="border border-teal-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" required></textarea>
                <button type="submit" class="bg-teal-600 text-white font-semibold py-2 px-4 rounded hover:bg-teal-700 transition mt-2">Send Message</button>
            </form>
            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow p-8 flex flex-col gap-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">📍</span>
                    <span class="font-bold">Address</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">123 Street Name<br>City, Country</div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">📞</span>
                    <span class="font-bold">Phone</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">+XXX XXX XXXX</div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">✉️</span>
                    <span class="font-bold">Email</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">info@somethingcompany.com</div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">🕒</span>
                    <span class="font-bold">Hours</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">Mon-Fri, 9:00am - 5:00pm</div>
            </div>
        </div>
        <!-- Google Map Embed -->
        <div class="w-full mb-8 flex justify-center">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3974.123456789!2d114.948123!3d4.903123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z4K6k4K6w4K6V4K6w4K6V!5e0!3m2!1sen!2sbn!4v0000000000000" width="600" height="300" style="border:0; border-radius:16px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <!-- Social Media Icons -->
        <div class="w-full flex justify-center gap-6 mb-8">
            <a href="#" class="text-teal-600 hover:text-teal-800" title="Facebook"><svg width="48" height="48" fill="currentColor" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16"/><path fill="#fff" d="M18.5 16h-1.5v6h-2v-6h-1v-1.5h1V13c0-.6.4-1 1-1h1.5v1.5h-1c-.2 0-.5.1-.5.5v1h1.5L18.5 16z"/></svg></a>
            <a href="#" class="text-teal-600 hover:text-teal-800" title="Instagram"><svg width="48" height="48" fill="currentColor" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16"/><rect x="10" y="10" width="12" height="12" rx="4" fill="#fff"/><circle cx="16" cy="16" r="4" fill="currentColor"/><circle cx="21" cy="13" r="1.5" fill="currentColor"/></svg></a>
            <a href="#" class="text-teal-600 hover:text-teal-800" title="Twitter"><svg width="48" height="48" fill="currentColor" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16"/><path fill="#fff" d="M23.5 12.5c-.6.3-1.2.5-1.9.6.7-.4 1.1-1 1.3-1.7-.6.4-1.3.7-2 .8-.6-.6-1.4-1-2.2-1-1.7 0-3 1.4-3 3 0 .2 0 .4.1.6-2.5-.1-4.7-1.3-6.2-3.2-.3.5-.5 1-.5 1.6 0 1.1.6 2.1 1.5 2.7-.5 0-1-.2-1.4-.4v.1c0 1.6 1.1 2.9 2.6 3.2-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.2 1.5 2.1 2.8 2.1-1 .8-2.2 1.2-3.5 1.2-.2 0-.4 0-.6-.1C9.5 22.1 11.2 22.5 13 22.5c5.5 0 8.5-4.6 8.5-8.5v-.4c.6-.4 1.1-1 1.5-1.6z"/></svg></a>
            <a href="#" class="text-teal-600 hover:text-teal-800" title="LinkedIn"><svg width="48" height="48" fill="currentColor" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16"/><rect x="10" y="13" width="2" height="6" fill="#fff"/><circle cx="11" cy="11" r="1" fill="#fff"/><rect x="15" y="13" width="2" height="6" fill="#fff"/><rect x="19" y="13" width="2" height="6" fill="#fff"/></svg></a>
        </div>
    </div>
</div>
@endsection
