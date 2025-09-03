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
        <div class="w-full flex justify-center mb-8">
            <div class="flex gap-4 mb-4">
                <a href="#" class="flex items-center justify-center w-12 h-12 rounded-full bg-teal-100 text-teal-600 hover:bg-teal-200 transition" title="Instagram">
                    <!-- Instagram Official SVG -->
                    <svg width="24" height="24" viewBox="0 0 448 512" fill="currentColor"><path d="M224.1 141c-63.6 0-115.1 51.5-115.1 115.1S160.5 371.3 224.1 371.3 339.2 319.8 339.2 256.1 287.7 141 224.1 141zm0 186.6c-39.6 0-71.7-32.1-71.7-71.7s32.1-71.7 71.7-71.7 71.7 32.1 71.7 71.7-32.1 71.7-71.7 71.7zm146.4-194.3c0 14.9-12.1 27-27 27s-27-12.1-27-27 12.1-27 27-27 27 12.1 27 27zm76.1 27.2c-1.7-35.3-9.9-66.7-36.2-92.9S388.6 9.7 353.3 8c-35.3-1.7-138.6-1.7-173.9 0-35.3 1.7-66.7 9.9-92.9 36.2S9.7 123.4 8 158.7c-1.7 35.3-1.7 138.6 0 173.9 1.7 35.3 9.9 66.7 36.2 92.9s57.6 34.5 92.9 36.2c35.3 1.7 138.6 1.7 173.9 0 35.3-1.7 66.7-9.9 92.9-36.2s34.5-57.6 36.2-92.9c1.7-35.3 1.7-138.6 0-173.9zM398.8 388c-7.8 19.6-22.9 34.7-42.5 42.5-29.5 11.7-99.5 9-132.3 9s-102.7 2.6-132.3-9c-19.6-7.8-34.7-22.9-42.5-42.5-11.7-29.5-9-99.5-9-132.3s-2.6-102.7 9-132.3c7.8-19.6 22.9-34.7 42.5-42.5 29.5-11.7 99.5-9 132.3-9s102.7-2.6 132.3 9c19.6 7.8 34.7 22.9 42.5 42.5 11.7 29.5 9 99.5 9 132.3s2.6 102.7-9 132.3z"/></svg>
                </a>
                <a href="#" class="flex items-center justify-center w-12 h-12 rounded-full bg-teal-100 text-teal-600 hover:bg-teal-200 transition" title="Facebook">
                    <!-- Facebook Official SVG -->
                    <svg width="24" height="24" viewBox="0 0 320 512" fill="currentColor"><path d="M279.14 288l14.22-92.66h-88.91V127.89c0-25.35 12.42-50.06 52.24-50.06H293V6.26S259.5 0 225.36 0c-73.22 0-121.36 44.38-121.36 124.72V195.3H22.89V288h81.11v224h100.2V288z"/></svg>
                </a>
                <a href="#" class="flex items-center justify-center w-12 h-12 rounded-full bg-teal-100 text-teal-600 hover:bg-teal-200 transition" title="X (FKA Twitter)">
                    <!-- X Official SVG -->
                    <svg width="24" height="24" viewBox="0 0 512 512" fill="none">
                        <path d="M437.252 80H338.748L256 196.252 173.252 80H74.748L208.252 256 74.748 432H173.252L256 315.748 338.748 432h98.504L303.748 256 437.252 80z" fill="#0d9488"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
