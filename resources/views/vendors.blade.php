@extends('layouts.app')

@section('title', 'Vendors')

@section('content')
<section class="w-full flex flex-col items-center justify-center py-12 bg-white min-h-screen">
    <h2 class="text-3xl font-bold text-teal-700 mb-8">VENDORS</h2>
    <div class="flex flex-row justify-between items-center w-full max-w-5xl mb-8 px-2">
        <input type="text" placeholder="Find vendors..." class="border border-teal-300 rounded-lg px-4 py-2 w-2/3 focus:outline-none focus:ring-2 focus:ring-teal-400" />
        <select class="ml-4 border border-teal-300 rounded-lg px-4 py-2 bg-white text-teal-700 focus:outline-none">
            <option>Filter ▼</option>
            <option>All</option>
            <option>Baked Goods</option>
            <option>Organic Produce</option>
            <option>Grilled Specialties</option>
            <option>Desserts</option>
            <option>Seafood</option>
        </select>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 w-full max-w-5xl px-2 mb-8">
        <div class="bg-teal-50 rounded-xl shadow-lg border p-6 flex flex-col items-center gap-2">
            <img src="https://randomuser.me/api/portraits/men/21.jpg" alt="Ahmad's Bakery" class="w-16 h-16 rounded-full object-cover mb-2">
            <span class="font-semibold text-teal-700">Ahmad's Bakery</span>
            <span class="text-yellow-400 text-lg">★★★★★</span>
            <a href="#" class="mt-2 px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">Visit Shop</a>
        </div>
        <div class="bg-teal-50 rounded-xl shadow-lg border p-6 flex flex-col items-center gap-2">
            <img src="https://randomuser.me/api/portraits/women/22.jpg" alt="Siti's Organics" class="w-16 h-16 rounded-full object-cover mb-2">
            <span class="font-semibold text-teal-700">Siti's Organics</span>
            <span class="text-yellow-400 text-lg">★★★★☆</span>
            <a href="#" class="mt-2 px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">Visit Shop</a>
        </div>
        <div class="bg-teal-50 rounded-xl shadow-lg border p-6 flex flex-col items-center gap-2">
            <img src="https://randomuser.me/api/portraits/men/23.jpg" alt="Joe's Grill" class="w-16 h-16 rounded-full object-cover mb-2">
            <span class="font-semibold text-teal-700">Joe's Grill</span>
            <span class="text-yellow-400 text-lg">★★★★★</span>
            <a href="#" class="mt-2 px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">Visit Shop</a>
        </div>
        <div class="bg-teal-50 rounded-xl shadow-lg border p-6 flex flex-col items-center gap-2">
            <img src="https://randomuser.me/api/portraits/women/24.jpg" alt="Maya's Sweets" class="w-16 h-16 rounded-full object-cover mb-2">
            <span class="font-semibold text-teal-700">Maya's Sweets</span>
            <span class="text-yellow-400 text-lg">★★★★★</span>
            <a href="#" class="mt-2 px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">Visit Shop</a>
        </div>
        <div class="bg-teal-50 rounded-xl shadow-lg border p-6 flex flex-col items-center gap-2">
            <img src="https://randomuser.me/api/portraits/men/25.jpg" alt="Ali's Seafood" class="w-16 h-16 rounded-full object-cover mb-2">
            <span class="font-semibold text-teal-700">Ali's Seafood</span>
            <span class="text-yellow-400 text-lg">★★★★★</span>
            <a href="#" class="mt-2 px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700 transition">Visit Shop</a>
        </div>
        <!-- Add more vendor cards as needed -->
    </div>
    <div class="flex justify-center items-center gap-2 mt-4">
        <button class="px-2 py-1 rounded bg-teal-100 text-teal-700 hover:bg-teal-200">&lt;&lt;</button>
        <button class="px-3 py-1 rounded bg-teal-600 text-white font-bold">1</button>
        <button class="px-3 py-1 rounded bg-teal-100 text-teal-700 hover:bg-teal-200">2</button>
        <button class="px-3 py-1 rounded bg-teal-100 text-teal-700 hover:bg-teal-200">3</button>
        <button class="px-2 py-1 rounded bg-teal-100 text-teal-700 hover:bg-teal-200">&gt;&gt;</button>
    </div>
</section>
@endsection
