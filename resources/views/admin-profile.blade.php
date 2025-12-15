@extends('layouts.app')

@php
    $context = session('header_context', 'tijarah');
    $pageTitle = $context === 'unissa-cafe' ? 'Unissa Cafe - Admin Profile' : 'Tijarah - Admin Profile';
@endphp

@section('title', $pageTitle)

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Admin Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 overflow-hidden mb-8">
            <!-- Admin Cover Background -->
            <div class="h-32 bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-600 relative">
                <button type="button" onclick="window.location.href='/edit-profile'" class="absolute top-4 right-4 px-4 py-2 bg-white border border-teal-200 text-teal-700 font-semibold rounded-xl shadow hover:bg-teal-50 hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-400 transition-all duration-200 z-10" aria-label="Edit Profile">
                    Edit Profile
                </button>
            </div>
            
            <!-- Profile Content -->
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <!-- Profile Picture -->
                        <div class="relative">
                            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" 
                                 alt="Admin Profile" 
                                 class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                        </div>
                        
                        <!-- Admin Info -->
                        <div class="lg:mb-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                                <div class="flex flex-col gap-0 mb-2">
                                    <div class="flex items-center gap-3">
                                        <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name ?? 'System Admin' }}</h1>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-gray-600 mt-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                            </svg>
                                            <span class="font-medium">admin</span>
                                        </div>
                                        <!-- Full access label removed to match edit-profile page for admin -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <!-- Left Column: Administrator Details -->
            <div class="xl:col-span-1">
                <!-- Administrator Details -->
                <div class="bg-white rounded-2xl shadow-xl border border-teal-100 p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 bg-gradient-to-r from-teal-100 to-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent">Administrator Details</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border-l-4 border-teal-400 pl-6 py-4">
                            <div class="text-sm font-semibold text-gray-600 mb-2">Email Address</div>
                            <div class="text-lg text-gray-900 font-medium break-words">{{ Auth::user()->email ?? 'admin@tijarah.bn' }}</div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border-l-4 border-emerald-400 pl-6 py-4">
                            <div class="text-sm font-semibold text-gray-600 mb-2">Contact Number</div>
                            <div class="text-lg text-gray-900 font-medium">{{ Auth::user()->phone ?? '+673 xxxx xxxx' }}</div>
                        </div>
                        
                        <!-- Admin Level removed: controlled via admin-levels management -->
                        
                        <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border-l-4 border-teal-500 pl-6 py-4">
                            <div class="text-sm font-semibold text-gray-600 mb-2">Last Login</div>
                            <div class="text-lg text-teal-700 font-bold">{{ now()->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Admin Actions Cards & Statistics -->
            <div class="xl:col-span-3 space-y-8">
                <!-- Admin Actions Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-4">
                    <!-- Manage Users Card -->
                    <a href="/admin/users" class="group block">
                        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 p-4 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group-hover:border-teal-300 h-full">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-teal-100 to-emerald-100 rounded-xl flex items-center justify-center group-hover:from-teal-200 group-hover:to-emerald-200 transition-all duration-300">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11a4 4 0 10-8 0 4 4 0 008 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 20v-1a4 4 0 014-4h12a4 4 0 014 4v1" />
                                    </svg>
                                </div>
                                <div class="flex items-center text-teal-600 group-hover:text-teal-700 transition-colors duration-300">
                                    <span class="text-sm font-medium mr-2">Access</span>
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-900 transition-colors duration-300 line-clamp-2">Manage Users</h3>
                                <p class="text-gray-600 mb-3 text-xs leading-relaxed line-clamp-3">View, edit, and manage all registered users in the system. Control user permissions and access levels.</p>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        <span>Users</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Control</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- View Catalog Card -->
                    <a href="{{ route('unissa-cafe.catalog') }}" class="group block">
                        <div class="bg-white rounded-2xl shadow-xl border border-emerald-100 p-4 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group-hover:border-emerald-300 h-full">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center group-hover:from-emerald-200 group-hover:to-teal-200 transition-all duration-300">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </div>
                                <div class="flex items-center text-emerald-600 group-hover:text-emerald-700 transition-colors duration-300">
                                    <span class="text-sm font-medium mr-2">Browse</span>
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-900 mb-2 group-hover:text-emerald-900 transition-colors duration-300 line-clamp-2">View Catalog</h3>
                                <p class="text-gray-600 mb-3 text-xs leading-relaxed line-clamp-3">Browse all products in the catalog. Add, edit, or remove items from the food and merchandise collections.</p>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                        <span>Products</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                        </svg>
                                        <span>Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Order Management Card -->
                    <a href="{{ route('admin.orders.index') }}" class="group block">
                        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 p-4 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group-hover:border-teal-300 h-full">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-teal-100 to-emerald-100 rounded-xl flex items-center justify-center group-hover:from-teal-200 group-hover:to-emerald-200 transition-all duration-300">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div class="flex items-center text-teal-600 group-hover:text-teal-700 transition-colors duration-300">
                                    <span class="text-sm font-medium mr-2">Manage</span>
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-900 mb-2 group-hover:text-teal-900 transition-colors duration-300 line-clamp-2">Manage Orders</h3>
                                <p class="text-gray-600 mb-3 text-xs leading-relaxed line-clamp-3">View, process, and manage customer orders. Track order status, update fulfillment, and handle customer requests.</p>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                        <span>Orders</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Status</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Product Management Card -->
                    <a href="{{ route('admin.products.index') }}" class="group block">
                        <div class="bg-white rounded-2xl shadow-xl border border-emerald-100 p-4 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group-hover:border-emerald-300 h-full">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center group-hover:from-emerald-200 group-hover:to-teal-200 transition-all duration-300">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 16V8l-9-4-9 4v8l9 4 9-4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.27 6.96L12 11.07l8.73-4.11" />
                                    </svg>
                                </div>
                                <div class="flex items-center text-emerald-600 group-hover:text-emerald-700 transition-colors duration-300">
                                    <span class="text-sm font-medium mr-2">Manage</span>
                                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-gray-900 mb-2 group-hover:text-emerald-900 transition-colors duration-300 line-clamp-2">Product Inventory</h3>
                                <p class="text-gray-600 mb-3 text-xs leading-relaxed line-clamp-3">Manage stock levels, product availability, and pricing. Control what's available for customers to order.</p>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 4a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1V5a1 1 0 00-1-1H5zM5 8a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1V9a1 1 0 00-1-1H5zM5 12a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1v-1a1 1 0 00-1-1H5zM9 4a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1V5a1 1 0 00-1-1H9zM9 8a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1V9a1 1 0 00-1-1H9zM9 12a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1v-1a1 1 0 00-1-1H9zM13 4a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1V5a1 1 0 00-1-1h-1zM13 8a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1V9a1 1 0 00-1-1h-1zM13 12a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1v-1a1 1 0 00-1-1h-1z"/>
                                        </svg>
                                        <span>Stock</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Control</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Enhanced Statistics Overview Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-teal-100 overflow-hidden">
                    <!-- Header with Gradient Background -->
                    <div class="bg-gradient-to-r from-teal-600 via-emerald-600 to-teal-700 px-8 py-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Platform Analytics</h2>
                                <p class="text-teal-100 text-sm">Real-time insights and key performance metrics</p>
                            </div>
                        </div>
                        <div class="text-teal-100 text-sm">
                            Last updated: {{ now()->format('M d, Y \a\t H:i') }}
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <!-- Main Statistics Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
                            <!-- Total Users -->
                            <div class="relative group">
                                <div class="bg-gradient-to-br from-teal-50 to-emerald-100 rounded-2xl p-6 border border-teal-200/50 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                            </svg>
                                        </div>
                                        <div class="flex items-center text-teal-600 text-sm font-semibold">
                                            @php
                                                $recentUsers = \App\Models\User::where('created_at', '>=', now()->subDays(7))->count();
                                                $totalUsers = \App\Models\User::count();
                                                $userGrowth = $totalUsers > 0 ? round(($recentUsers / $totalUsers) * 100, 1) : 0;
                                            @endphp
                                            @if($recentUsers > 0)
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                +{{ $userGrowth }}%
                                            @else
                                                <span class="text-gray-500">--</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold text-teal-700 mb-1">{{ number_format($totalUsers) }}</div>
                                    <div class="text-sm font-medium text-teal-600">Total Users</div>
                                    <div class="text-xs text-teal-500 mt-1">+{{ $recentUsers }} this week</div>
                                </div>
                            </div>

                            <!-- Total Products -->
                            <div class="relative group">
                                <div class="bg-gradient-to-br from-emerald-50 to-teal-100 rounded-2xl p-6 border border-emerald-200/50 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex items-center text-emerald-600 text-sm font-semibold">
                                            @php
                                                $recentProducts = \App\Models\Product::where('created_at', '>=', now()->subDays(7))->count();
                                            @endphp
                                            @if($recentProducts > 0)
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                New
                                            @else
                                                <span class="text-gray-500">--</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold text-emerald-700 mb-1">{{ number_format(\App\Models\Product::count()) }}</div>
                                    <div class="text-sm font-medium text-emerald-600">Total Products</div>
                                    <div class="text-xs text-emerald-500 mt-1">+{{ $recentProducts }} this week</div>
                                </div>
                            </div>

                            <!-- Total Reviews -->
                            <div class="relative group">
                                <div class="bg-gradient-to-br from-teal-50 to-emerald-100 rounded-2xl p-6 border border-teal-200/50 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                            </svg>
                                        </div>
                                        <div class="flex items-center text-teal-600 text-sm font-semibold">
                                            @php
                                                $recentReviews = \App\Models\Review::where('created_at', '>=', now()->subDays(7))->count();
                                            @endphp
                                            @if($recentReviews > 0)
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Active
                                            @else
                                                <span class="text-gray-500">--</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold text-teal-700 mb-1">{{ number_format(\App\Models\Review::count()) }}</div>
                                    <div class="text-sm font-medium text-teal-600">Total Reviews</div>
                                    <div class="text-xs text-teal-500 mt-1">+{{ $recentReviews }} this week</div>
                                </div>
                            </div>

                            <!-- Average Rating -->
                            <div class="relative group">
                                <div class="bg-gradient-to-br from-emerald-50 to-teal-100 rounded-2xl p-6 border border-emerald-200/50 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="flex items-center text-teal-600 text-sm font-semibold">
                                            @php
                                                $averageRating = \App\Models\Review::avg('rating');
                                                $ratingQuality = $averageRating >= 4.5 ? 'Excellent' : ($averageRating >= 4.0 ? 'Great' : ($averageRating >= 3.0 ? 'Good' : 'Fair'));
                                            @endphp
                                            {{ $ratingQuality }}
                                        </div>
                                    </div>
                                    @php
                                        $formattedRating = $averageRating ? number_format($averageRating, 1) : '0.0';
                                    @endphp
                                    <div class="text-3xl font-bold text-emerald-700 mb-1">{{ $formattedRating }}</div>
                                    <div class="text-sm font-medium text-emerald-600">Average Rating</div>
                                    <div class="flex items-center mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Total Orders -->
                            <div class="relative group">
                                <div class="bg-gradient-to-br from-teal-50 to-cyan-100 rounded-2xl p-6 border border-teal-200/50 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                        </div>
                                        <div class="flex items-center text-teal-600 text-sm font-semibold">
                                            @php
                                                $recentOrders = \App\Models\Order::where('created_at', '>=', now()->subDays(7))->count();
                                                $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                                            @endphp
                                            @if($pendingOrders > 0)
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $pendingOrders }} Pending
                                            @else
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Up to Date
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold text-teal-700 mb-1">{{ number_format(\App\Models\Order::count()) }}</div>
                                    <div class="text-sm font-medium text-teal-600">Total Orders</div>
                                    <div class="text-xs text-teal-500 mt-1">+{{ $recentOrders }} this week</div>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Breakdown Section -->
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Detailed Analytics
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Content Breakdown -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                        </svg>
                                        Content Distribution
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600 flex items-center gap-2">
                                                <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                                                Food products
                                            </span>
                                            <span class="font-semibold text-teal-600">{{ \App\Models\Product::where('type', 'food')->count() }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600 flex items-center gap-2">
                                                <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                                Merchandise
                                            </span>
                                            <span class="font-semibold text-indigo-600">{{ \App\Models\Product::where('type', 'merch')->count() }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600 flex items-center gap-2">
                                                <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                                                Categories
                                            </span>
                                            <span class="font-semibold text-teal-600">{{ \App\Models\Product::distinct('category')->count('category') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Engagement -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        User Engagement
                                    </h4>
                                    <div class="space-y-3">
                                        @php
                                            $totalUsers = \App\Models\User::count();
                                            $usersWithReviews = \App\Models\User::has('reviews')->count();
                                            $engagementRate = $totalUsers > 0 ? round(($usersWithReviews / $totalUsers) * 100, 1) : 0;
                                        @endphp
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">Active reviewers</span>
                                            <span class="font-semibold text-green-600">{{ $usersWithReviews }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">Engagement rate</span>
                                            <span class="font-semibold text-blue-600">{{ $engagementRate }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">Avg reviews/user</span>
                                            <span class="font-semibold text-teal-600">{{ $usersWithReviews > 0 ? number_format(\App\Models\Review::count() / $usersWithReviews, 1) : '0.0' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                        </svg>
                                        Quick Actions
                                    </h4>
                                    <div class="space-y-2">
                                        <a href="/admin/users" class="flex items-center gap-2 text-sm text-gray-600 hover:text-teal-600 transition-colors">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        View all users
                                    </a>
                                    <a href="{{ route('unissa-cafe.catalog') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-teal-600 transition-colors">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Manage products
                                    </a>
                                    <button onclick="window.location.reload()" class="flex items-center gap-2 text-sm text-gray-600 hover:text-teal-600 transition-colors">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                        </svg>
                                        Refresh data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
