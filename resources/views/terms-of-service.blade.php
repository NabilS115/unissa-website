@extends('layouts.app')

@section('title', 'UNISSA Cafe - Terms of Service')

@section('content')
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Edit Button -->
        <div class="fixed top-20 right-4 z-50">
            <a href="{{ route('content.terms') }}" 
               class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
               style="background-color: #0d9488 !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Terms of Service
            </a>
        </div>
    @endif
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{!! \App\Models\ContentBlock::get('terms_title', 'Terms of Service', 'text', 'terms') !!}</h1>
                <p class="text-gray-600">{!! \App\Models\ContentBlock::get('terms_last_updated', 'Last updated: ' . date('F j, Y'), 'text', 'terms') !!}</p>
            </div>
            
            <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-teal-500 rounded-full mr-2"></div>
                    Universiti Islam Sultan Sharif Ali (UNISSA) - Brunei
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                    TIJARAH CO SDN BHD & UNISSA Cafe
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm p-8">
            <div class="prose prose-gray max-w-none text-gray-700">
                {!! \App\Models\ContentBlock::get('terms_content', '<h3>1. Acceptance of Terms</h3><p>By using our services, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p><h3>2. Use of Services</h3><p>You may use our services only for lawful purposes and in accordance with these Terms.</p>', 'html', 'terms') !!}
            </div>
        </div>
    </div>
</div>
@endsection
