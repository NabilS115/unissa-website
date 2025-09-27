@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Admin Header Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- Admin Cover Background -->
            <div class="h-32 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>
            
            <!-- Profile Content -->
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <!-- Profile Picture -->
                        <div class="relative">
                            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" 
                                 alt="Admin Profile" 
                                 class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-purple-500 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.94l1-4H9.03z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Admin Info -->
                        <div class="lg:mb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name ?? 'System Admin' }}</h1>
                                <a href="/edit-profile" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-all duration-200" title="Edit Profile">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex items-center gap-4 text-gray-600 mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">Platform Administrator</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium">Full Access</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Administrator Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Administrator Details</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-purple-400 pl-6 py-4">
                            <div class="text-sm font-medium text-gray-500 mb-2">Email Address</div>
                            <div class="text-lg text-gray-900 font-medium break-words">{{ Auth::user()->email ?? 'admin@tijarah.bn' }}</div>
                        </div>
                        
                        <div class="border-l-4 border-indigo-400 pl-6 py-4">
                            <div class="text-sm font-medium text-gray-500 mb-2">Contact Number</div>
                            <div class="text-lg text-gray-900 font-medium">{{ Auth::user()->phone ?? '+673 xxxx xxxx' }}</div>
                        </div>
                        
                        <div class="border-l-4 border-blue-400 pl-6 py-4">
                            <div class="text-sm font-medium text-gray-500 mb-2">Admin Level</div>
                            <div class="text-lg text-gray-900 font-medium">Super Administrator</div>
                        </div>
                        
                        <div class="border-l-4 border-green-400 pl-6 py-4">
                            <div class="text-sm font-medium text-gray-500 mb-2">Last Login</div>
                            <div class="text-lg text-gray-900 font-medium">{{ now()->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Admin Actions Cards -->
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Manage Users Card -->
                    <a href="/admin/users" class="group block">
                        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group-hover:border-purple-200 h-full">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-200 transition-colors duration-300">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                                <div class="flex items-center text-purple-600 group-hover:text-purple-700 transition-colors duration-300">
                                    <span class="text-sm font-medium mr-2">Access</span>
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-purple-900 transition-colors duration-300">Manage Users</h3>
                                <p class="text-gray-600 mb-4">View, edit, and manage all registered users in the system. Control user permissions and access levels.</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        <span>User Management</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Access Control</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- View Catalog Card -->
                    <a href="/catalog" class="group block">
                        <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group-hover:border-indigo-200 h-full">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center group-hover:bg-indigo-200 transition-colors duration-300">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div class="flex items-center text-indigo-600 group-hover:text-indigo-700 transition-colors duration-300">
                                    <span class="text-sm font-medium mr-2">Browse</span>
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-900 transition-colors duration-300">View Catalog</h3>
                                <p class="text-gray-600 mb-4">Browse all products in the catalog. Add, edit, or remove items from the food and merchandise collections.</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                        <span>Product Management</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                        </svg>
                                        <span>Reviews & Ratings</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
