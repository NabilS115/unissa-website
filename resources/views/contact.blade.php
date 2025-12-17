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
            <div class="bg-white rounded-lg shadow p-8">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-2xl">📍</span>
                    <span class="font-bold">Address</span>
                </div>
                <div class="ml-8 text-gray-700 mb-4 leading-tight">{!! \App\Models\ContentBlock::get('contact_address', 'Universiti Islam Sultan Sharif Ali, Simpang 347,<br>Jalan Pasar Gadong, Bandar Seri Begawan, Brunei', 'html', 'contact') !!}</div>
                
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-2xl">📞</span>
                    <span class="font-bold">Phone</span>
                </div>
                <div class="ml-8 text-gray-700 mb-4 leading-tight">{!! \App\Models\ContentBlock::get('contact_phone', '+673 123 4567', 'text', 'contact') !!}</div>
                
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-2xl">✉️</span>
                    <span class="font-bold">Email</span>
                </div>
                <div class="ml-8 text-gray-700 mb-4 leading-tight">{!! \App\Models\ContentBlock::get('contact_email', 'tijarahco@unissa.edu.bn', 'text', 'contact') !!}</div>
                
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-2xl">🕒</span>
                    <span class="font-bold">Hours</span>
                </div>
                <div class="ml-8 text-gray-700 mb-4 leading-tight">
                    {!! \App\Models\ContentBlock::get('contact_business_hours', '<p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM<br><strong>Saturday:</strong> 9:00 AM - 2:00 PM<br><strong>Sunday:</strong> Closed</p>', 'html', 'contact') !!}
                </div>
                
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-2xl">📱</span>
                    <span class="font-bold">Follow Us</span>
                </div>
                <div class="ml-8 text-gray-700 leading-tight">
                    @php
                        $instagramLink = \App\Models\ContentBlock::get('contact_instagram_link', '', 'text', 'contact');
                        $facebookLink = \App\Models\ContentBlock::get('contact_facebook_link', '', 'text', 'contact');
                        $twitterLink = \App\Models\ContentBlock::get('contact_twitter_link', '', 'text', 'contact');
                        $hasAnyLinks = !empty($instagramLink) || !empty($facebookLink) || !empty($twitterLink);
                    @endphp
                    
                    @if($hasAnyLinks)
                        <div class="space-y-2">
                            @if(!empty($instagramLink) && $instagramLink !== '#')
                                <a href="{{ $instagramLink }}" class="flex items-center gap-2 text-teal-600 hover:text-teal-700 transition" title="Instagram" target="_blank" rel="noopener">
                                    <img src="{{ asset('images/instagramLogo.png') }}" alt="Instagram" width="20" height="20" class="object-contain">
                                    <span>@tijarahco.bn</span>
                                </a>
                            @endif
                            
                            @if(!empty($facebookLink) && $facebookLink !== '#')
                                <a href="{{ $facebookLink }}" class="flex items-center gap-2 text-teal-600 hover:text-teal-700 transition" title="Facebook" target="_blank" rel="noopener">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                            @endif
                            
                            @if(!empty($twitterLink) && $twitterLink !== '#')
                                <a href="{{ $twitterLink }}" class="flex items-center gap-2 text-teal-600 hover:text-teal-700 transition" title="Twitter" target="_blank" rel="noopener">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    <span>Twitter</span>
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 italic">No social media links available at this time.</p>
                    @endif
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
