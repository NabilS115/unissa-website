@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-profile.svg') }}" alt="Admin Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-teal-600">
            <div>
                <div class="flex items-center gap-2">
                    <div class="text-2xl font-bold text-teal-700">{{ Auth::user()->name ?? 'System Admin' }}</div>
                    <a href="/edit-profile" class="inline-flex items-center justify-center w-6 h-6 text-teal-600 hover:text-teal-800 transition" title="Edit Profile">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20h4l10-10-4-4L4 16v4z" />
                        </svg>
                    </a>
                </div>
                <div class="text-gray-600 text-base mt-0">Role: Platform Administrator</div>
            </div>
        </div>
        <div class="flex gap-2">
            
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <div class="font-semibold">Email:</div>
            <div class="text-gray-700">{{ Auth::user()->email ?? 'admin@tijarah.bn' }}</div>
        </div>
        <div>
            <div class="font-semibold">Contact:</div>
            <div class="text-gray-700">{{ Auth::user()->phone ?? '+673 xxxx xxxx' }}</div>
        </div>
    </div>
    <!-- Dashboard Sections -->
    <div class="mb-8">
        <div class="font-bold text-lg mb-2">Platform Stats</div>
        <ul class="list-disc ml-6 text-gray-700">
            <li>Total Users: 1,240</li>
            <li>Sellers: 320 | Buyers: 920</li>
            <li>Products Listed: 2,450</li>
            <li>Orders Completed: 6,540</li>
        </ul>
    </div>
    <div class="mb-8">
        <div class="font-bold text-lg mb-2">Recent Activity</div>
        <ul class="list-disc ml-6 text-gray-700">
            <li>New Seller registered: @hijabstore</li>
            <li>Buyer reported issue with Order #2043</li>
            <li>Product flagged for review (ID #1832)</li>
        </ul>
    </div>
    <div class="mb-8">
        <div class="font-bold text-lg mb-2">User Management</div>
        <div class="flex gap-4 mb-2">
            <a href="#" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">View All Sellers</a>
            <a href="#" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">View All Buyers</a>
        </div>
        <div class="flex gap-4">
            <a href="#" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Suspend Accounts</a>
            <a href="#" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Approve Accounts</a>
        </div>
    </div>
    <div class="mb-8">
        <div class="font-bold text-lg mb-2">Reports & Issues</div>
        <ul class="list-disc ml-6 text-gray-700">
            <li>Pending Reports: 12</li>
            <li>Resolved: 48</li>
        </ul>
        <a href="#" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 mt-2 inline-block">View Report Details</a>
    </div>
    <div>
        <div class="font-bold text-lg mb-2">System Controls</div>
        <ul class="list-disc ml-6 text-gray-700">
            <li>Manage Categories</li>
            <li>Manage Site Policies</li>
            <li>Configure Payment & Shipping Settings</li>
        </ul>
    </div>
</div>
@endsection
