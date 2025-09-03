@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover border-4 border-teal-600">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-teal-700">{{ Auth::user()->name ?? 'Dr. Ahmad bin Ali' }}</span>
                    <a href="/edit-profile" class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-teal-100 transition" title="Edit Profile">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" class="text-teal-700">
                            <path d="M3 17.25V21h3.75l11.06-11.06a1.06 1.06 0 0 0-1.5-1.5L6.25 19.5H3z" fill="currentColor"/>
                            <path d="M14.06 7.94l2 2" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <div class="text-gray-600">Role: {{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</div>
            </div>
        </div>
    <!-- Edit button moved to beside name -->
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <div class="font-semibold">Faculty / Department:</div>
            <div class="text-gray-700">{{ Auth::user()->department ?? 'Faculty of Usuluddin' }}</div>
        </div>
        <div>
            <div class="font-semibold">Email:</div>
            <div class="text-gray-700">{{ Auth::user()->email ?? 'name@unissa.edu.bn' }}</div>
        </div>
        <div>
            <div class="font-semibold">Phone:</div>
            <div class="text-gray-700">{{ Auth::user()->phone ?? '+673 xxxx xxxx' }}</div>
        </div>
    </div>
    <div class="mb-4 font-semibold text-lg">Short Bio / Academic Background</div>
    <div class="mt-8">
        <div class="font-semibold text-lg mb-4">My Reviews</div>
        <div class="relative flex items-center justify-center max-w-xl mx-auto">
            <button onclick="moveReview(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-transparent border border-transparent text-teal-600 hover:bg-teal-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full z-10 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <div id="reviews-carousel" class="overflow-hidden w-full">
                <div id="reviews-track" class="flex transition-transform duration-500 ease-in-out" style="width:100%">
                    <div class="min-w-full px-4">
                        <div class="bg-white rounded-xl shadow-lg p-6 border flex flex-col gap-2">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-yellow-400 text-2xl">★★★★☆</span>
                                <span class="text-gray-900 font-medium">"Great quality, fast delivery"</span>
                            </div>
                            <div class="text-gray-600 text-sm">Product: Handmade Tote Bag</div>
                        </div>
                    </div>
                    <div class="min-w-full px-4">
                        <div class="bg-white rounded-xl shadow-lg p-6 border flex flex-col gap-2">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-yellow-400 text-2xl">★★★★★</span>
                                <span class="text-gray-900 font-medium">"Excellent communication and support."</span>
                            </div>
                            <div class="text-gray-600 text-sm">Product: Islamic Book Set</div>
                        </div>
                    </div>
                    <div class="min-w-full px-4">
                        <div class="bg-white rounded-xl shadow-lg p-6 border flex flex-col gap-2">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-yellow-400 text-2xl">★★★☆☆</span>
                                <span class="text-gray-900 font-medium">"Product as described, but shipping was slow."</span>
                            </div>
                            <div class="text-gray-600 text-sm">Product: Prayer Mat</div>
                        </div>
                    </div>
                </div>
            </div>
            <button onclick="moveReview(1)" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border border-transparent text-teal-600 hover:bg-teal-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full z-10 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>
    </div>
    <script>
        let currentReview = 0;
        function moveReview(dir) {
            const track = document.getElementById('reviews-track');
            const total = track.children.length;
            currentReview = (currentReview + dir + total) % total;
            track.style.transform = `translateX(-${currentReview * 100}%)`;
        }
    </script>
</div>
</div>
@endsection
