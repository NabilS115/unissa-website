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
                <div class="ml-8 text-gray-700 mb-2">Universiti Islam Sultan Sharif Ali, Simpang 347,<br>Jalan Pasar Gadong, Bandar Seri Begawan, Brunei</div>
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
                <div class="ml-8 text-gray-700 mb-2">Mon-Thu & Sat, 9:00am - 4:30pm</div>
            </div>
        </div>
        <!-- Google Map Embed -->
        <div class="w-full mb-8 flex justify-center">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3639.256104800805!2d114.91331462192598!3d4.906480736062181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3222f55af6a6dbf3%3A0x7344d954a94bb9e5!2sSultan%20Sharif%20Ali%20Islamic%20University!5e1!3m2!1sen!2sus!4v1756696338976!5m2!1sen!2sus" width="600" height="450" style="border:0; border-radius:16px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <!-- Social Media Icons -->
        <div class="w-full flex justify-center gap-6 mb-8">
            <a href="#" class="text-teal-600 hover:text-teal-800" title="Instagram"><svg width="48" height="48" fill="currentColor" viewBox="0 0 32 32"><circle cx="16" cy="16" r="16"/><rect x="10" y="10" width="12" height="12" rx="4" fill="#fff"/><circle cx="16" cy="16" r="4" fill="currentColor"/><circle cx="21" cy="13" r="1.5" fill="currentColor"/></svg></a>
            <a href="#" class="text-teal-600 hover:text-teal-800" title="X"><svg viewBox="0 0 48 48" width="48" height="48" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="24" fill="#0d9488"/><path d="M35 13L25 24L35 35H31L24 27L17 35H13L23 24L13 13H17L24 21L31 13H35Z" fill="white"/></svg></a>
        </div>
    </div>
</div>
@endsection
