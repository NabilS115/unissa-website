@extends('layouts.app')

@php
    $context = session('header_context', 'tijarah');
    $pageTitle = $context === 'unissa-cafe' ? 'UNISSA Cafe - Profile' : 'Tijarah Co - Profile';
@endphp

@section('title', $pageTitle)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- User Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500 relative">
                <button type="button" onclick="window.location.href='/edit-profile'" class="absolute top-4 right-4 px-4 py-2 bg-white border border-teal-200 text-teal-700 font-semibold rounded-xl shadow hover:bg-teal-50 hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-400 transition-all duration-200 z-10" aria-label="Edit Profile">
                    Edit Profile
                </button>
            </div>
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <div class="relative">
                @if(Auth::check())
                    <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white" loading="lazy" decoding="async"
                         onerror="this.onerror=null; this.src='{{ asset('images/default-profile.svg') }}'; if(!this.src.includes('default-profile.svg')) { this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTAiIGZpbGw9IiNmNGY0ZjUiLz4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMCIgcj0iMyIgZmlsbD0iIzljYTNhZiIvPgo8cGF0aCBkPSJNNy41IDE5LjVhNy41IDcuNSAwIDAgMSA5IDAgYy0yLTIuNS02LTIuNS04IDBaIiBmaWxsPSIjOWNhM2FmIi8+Cjwvc3ZnPg=='; }">                    
                @endif
                        </div>
                        <div class="lg:mb-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                                <div class="flex flex-col gap-0 mb-2">
                                    <div class="flex items-center gap-3">
                                        <h1 class="text-3xl font-bold text-gray-900">@if(Auth::check()){{ Auth::user()->name }}@else Dr. Ahmad bin Ali @endif</h1>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600 mt-2">
                                        <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                        </svg>
                                        <span class="font-medium">@if(Auth::check()){{ Auth::user()->role }}@else Lecturer / Student / Staff @endif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <!-- Personal Information Card -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                </div>
                    
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border-l-4 border-teal-400 pl-4">
                        <div class="text-sm font-medium text-gray-500 mb-1">Full Name</div>
                        <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->name }}@else Dr. Ahmad bin Ali @endif</div>
                    </div>
                    
                    <div class="border-l-4 border-blue-400 pl-4">
                        <div class="text-sm font-medium text-gray-500 mb-1">Email Address</div>
                        <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->email }}@else name@unissa.edu.bn @endif</div>
                    </div>
                    
                    <div class="border-l-4 border-green-400 pl-4">
                        <div class="text-sm font-medium text-gray-500 mb-1">Phone Number</div>
                        <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->phone }}@else +673 xxxx xxxx @endif</div>
                    </div>
                    
                    <div class="border-l-4 border-purple-400 pl-4">
                        <div class="text-sm font-medium text-gray-500 mb-1">Faculty / Department</div>
                        <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->department }}@else Faculty of Usuluddin @endif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile optimizations for profile page */
@media (max-width: 768px) {
    /* Main container mobile */
    .min-h-screen {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
    
    /* Profile header card mobile */
    .profile-header-card {
        border-radius: 1rem !important;
        margin-bottom: 1.5rem !important;
    }
    

    
    .lg\\:items-end {
        align-items: flex-start !important;
    }
    
    .lg\\:justify-between {
        justify-content: flex-start !important;
    }
    

    

    
    /* Mobile-only carousel fixes */
    @media (max-width: 768px) {
        #reviews-carousel {
            overflow: hidden !important;
        }
        
        #reviews-track {
            display: flex !important;
            transition: transform 0.5s ease-in-out !important;
        }
        
        #reviews-track > .w-full {
            width: 100% !important;
            flex-shrink: 0 !important;
            min-width: 100% !important;
            max-width: 100% !important;
        }
        
        #reviews-track .bg-white {
            margin: 0 0.5rem !important;
            width: calc(100% - 1rem) !important;
        }
    }
    
    /* Desktop carousel - preserve original behavior */
    @media (min-width: 769px) {
        #reviews-carousel {
            margin-left: 4rem !important;
            margin-right: 4rem !important;
            overflow: hidden !important;
        }
        
        #reviews-track {
            display: flex !important;
            width: 500% !important;
            transition: transform 0.5s ease-in-out !important;
        }
        
        #reviews-track > .w-full {
            width: 20% !important;
            flex-shrink: 0 !important;
            min-width: 20% !important;
            max-width: 20% !important;
        }
        
        #reviews-track .bg-white {
            margin: 0 0.25rem !important;
            width: calc(100% - 0.5rem) !important;
        }
    }


    
    .reviews-section .text-sm {
        font-size: 0.75rem !important;
    }

}
</style>
@endsection
