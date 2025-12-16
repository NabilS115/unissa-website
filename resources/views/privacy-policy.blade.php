@extends('layouts.app')

@section('title', 'Unissa Cafe - Privacy Policy')

@section('content')
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Edit Button -->
        <div class="fixed top-20 right-4 z-50">
            <a href="{{ route('content.privacy') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
               style="background-color: #0d9488 !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Privacy Policy
            </a>
        </div>
    @endif
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{!! \App\Models\ContentBlock::get('privacy_title', 'Privacy Policy', 'text', 'privacy') !!}</h1>
                <p class="text-gray-600">{!! \App\Models\ContentBlock::get('privacy_last_updated', 'Last updated: ' . date('F j, Y'), 'text', 'privacy') !!}</p>
            </div>
            
            <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-teal-500 rounded-full mr-2"></div>
                    Universiti Islam Sultan Sharif Ali (UNISSA) - Brunei
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                    TIJARAH CO SDN BHD & Unissa Cafe
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm p-8">
            <div class="prose prose-gray max-w-none text-gray-700">
                {!! \App\Models\ContentBlock::get('privacy_content', '<h3>Information We Collect</h3><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p><h3>How We Use Your Information</h3><p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>', 'html', 'privacy') !!}
            </div>
        </div>
    </div>
</div>
@endsection
