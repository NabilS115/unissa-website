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
                    
                    <!-- Admin Actions -->
                    <div class="lg:mb-4 flex gap-3">
                        <a href="/admin/users" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 text-purple-600 rounded-xl hover:bg-purple-100 border border-purple-200 hover:border-purple-300 transition-all duration-200 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            Manage Users
                        </a>
                        <a href="/catalog" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 border border-indigo-200 hover:border-indigo-300 transition-all duration-200 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                            </svg>
                            View Catalog
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column: Admin Details -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Personal Information Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Administrator Details</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-purple-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Email Address</div>
                            <div class="text-gray-900 font-medium">{{ Auth::user()->email ?? 'admin@tijarah.bn' }}</div>
                        </div>
                        
                        <div class="border-l-4 border-indigo-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Contact Number</div>
                            <div class="text-gray-900 font-medium">{{ Auth::user()->phone ?? '+673 xxxx xxxx' }}</div>
                        </div>
                        
                        <div class="border-l-4 border-blue-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Admin Level</div>
                            <div class="text-gray-900 font-medium">Super Administrator</div>
                        </div>
                        
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Last Login</div>
                            <div class="text-gray-900 font-medium">{{ now()->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Platform Stats</h2>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">1,240</div>
                            <div class="text-sm text-gray-600 mt-1">Total Users</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">2,450</div>
                            <div class="text-sm text-gray-600 mt-1">Products</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">6,540</div>
                            <div class="text-sm text-gray-600 mt-1">Reviews</div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-orange-600">320</div>
                            <div class="text-sm text-gray-600 mt-1">Vendors</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Admin Dashboard -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Recent Activity Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Recent Activity</h2>
                            <p class="text-gray-600">Latest platform events and updates</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">New User Registration</div>
                                <div class="text-sm text-gray-600 mt-1">@hijabstore registered as a new seller</div>
                                <div class="text-xs text-gray-500 mt-2">2 minutes ago</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 bg-red-50 rounded-xl border border-red-100">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Issue Reported</div>
                                <div class="text-sm text-gray-600 mt-1">Buyer reported issue with Order #2043</div>
                                <div class="text-xs text-gray-500 mt-2">15 minutes ago</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Product Flagged</div>
                                <div class="text-sm text-gray-600 mt-1">Product flagged for review (ID #1832)</div>
                                <div class="text-xs text-gray-500 mt-2">1 hour ago</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Tools Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Management Tools</h2>
                            <p class="text-gray-600">Platform administration and control panel</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Management -->
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900">User Management</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Manage user accounts, roles, and permissions</p>
                            <div class="flex gap-2">
                                <a href="/admin/users" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors text-center">
                                    Manage Users
                                </a>
                                <button class="px-3 py-2 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition-colors">
                                    Approve (12)
                                </button>
                                <button class="px-3 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition-colors">
                                    Suspend
                                </button>
                            </div>
                        </div>

                        <!-- Reports & Issues -->
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900">Reports & Issues</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Monitor and resolve platform issues</p>
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Pending Reports:</span>
                                    <span class="font-medium text-red-600">12</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Resolved:</span>
                                    <span class="font-medium text-green-600">48</span>
                                </div>
                            </div>
                            <button class="w-full px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                View Report Details
                            </button>
                        </div>

                        <!-- System Controls -->
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                                        <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V9a1 1 0 00-1-1h-1v4.396a2 2 0 11-2 0V7z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900">System Controls</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">Configure platform settings and policies</p>
                            <div class="space-y-2">
                                <button class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded border-l-4 border-purple-400">
                                    Manage Categories
                                </button>
                                <button class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded border-l-4 border-blue-400">
                                    Site Policies
                                </button>
                                <button class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded border-l-4 border-green-400">
                                    Payment Settings
                                </button>
                            </div>
                        </div>

                        <!-- Analytics -->
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900">Analytics</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">View platform performance and metrics</p>
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Monthly Growth:</span>
                                    <span class="font-medium text-green-600">+12.5%</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Active Users:</span>
                                    <span class="font-medium text-blue-600">1,240</span>
                                </div>
                            </div>
                            <button class="w-full px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                View Analytics
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
