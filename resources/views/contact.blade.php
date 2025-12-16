@extends('layouts.app')

@section('title', 'Tijarah Co - Contact Us')

@section('content')
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Edit Button -->
        <div class="fixed top-20 right-4 z-50">
            <a href="{{ route('content.contact') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
               style="background-color: #0d9488 !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Page
            </a>
        </div>
    @endif
<div class="w-full flex flex-col items-center bg-white min-h-screen pt-8">
    <div class="w-full max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold text-teal-700 mb-2 text-center">{!! \App\Models\ContentBlock::get('contact_title', 'Contact Us', 'text', 'contact') !!}</h2>
        <p class="text-lg text-gray-600 mb-8 text-center">{!! \App\Models\ContentBlock::get('contact_subtitle', 'Get in touch with us', 'text', 'contact') !!}</p>
        
        <!-- Form Description -->
        <div class="text-gray-700 text-center mb-8">
            {!! \App\Models\ContentBlock::get('contact_form_description', '<p>We would love to hear from you! Send us a message and we will respond as soon as possible.</p>', 'html', 'contact') !!}
        </div>
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="max-w-5xl mx-auto mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-5xl mx-auto mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Contact Form -->
            <form class="bg-white rounded-lg shadow p-8 flex flex-col gap-4" action="{{ route('contact.store') }}" method="POST">
                @csrf
                
                <label class="font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400 {{ $errors->has('name') ? 'border-red-500' : 'border-teal-300' }}" required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <label class="font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400 {{ $errors->has('email') ? 'border-red-500' : 'border-teal-300' }}" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <label class="font-semibold">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" 
                       class="border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400 {{ $errors->has('subject') ? 'border-red-500' : 'border-teal-300' }}">
                @error('subject')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <label class="font-semibold">Message</label>
                <textarea name="message" rows="4" 
                          class="border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400 {{ $errors->has('message') ? 'border-red-500' : 'border-teal-300' }}" required>{{ old('message') }}</textarea>
                @error('message')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror

                <button type="submit" class="bg-teal-600 text-white font-semibold py-2 px-4 rounded hover:bg-teal-700 transition mt-2">Send Message</button>
            </form>
            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow p-8 flex flex-col gap-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">📍</span>
                    <span class="font-bold">Address</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">{!! \App\Models\ContentBlock::get('contact_address', 'Universiti Islam Sultan Sharif Ali, Simpang 347,<br>Jalan Pasar Gadong, Bandar Seri Begawan, Brunei', 'html', 'contact') !!}</div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">📞</span>
                    <span class="font-bold">Phone</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">{!! \App\Models\ContentBlock::get('contact_phone', '+673 123 4567', 'text', 'contact') !!}</div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">✉️</span>
                    <span class="font-bold">Email</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">{!! \App\Models\ContentBlock::get('contact_email', 'tijarahco@unissa.edu.bn', 'text', 'contact') !!}</div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">🕒</span>
                    <span class="font-bold">Hours</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">
                    {!! \App\Models\ContentBlock::get('contact_business_hours', '<p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM<br><strong>Saturday:</strong> 9:00 AM - 2:00 PM<br><strong>Sunday:</strong> Closed</p>', 'html', 'contact') !!}
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-2xl">📱</span>
                    <span class="font-bold">Follow Us</span>
                </div>
                <div class="ml-8 text-gray-700 mb-2">
                    <a href="https://www.instagram.com/tijarahco.bn/" class="flex items-center gap-2 text-teal-600 hover:text-teal-700 transition" title="Instagram" target="_blank" rel="noopener">
                        <img src="{{ asset('images/instagramLogo.png') }}" alt="Instagram" width="20" height="20" class="object-contain">
                        <span>@tijarahco.bn</span>
                    </a>
                </div>
            </div>
        </div>
        <!-- Google Map Embed -->
        <div class="w-full mb-8 flex justify-center">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3639.256104800805!2d114.91331462192598!3d4.906480736062181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3222f55af6a6dbf3%3A0x7344d954a94bb9e5!2sSultan%20Sharif%20Ali%20Islamic%20University!5e1!3m2!1sen!2sus!4v1756696338976!5m2!1sen!2sus" width="600" height="450" style="border:0; border-radius:16px;" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>
@endsection
