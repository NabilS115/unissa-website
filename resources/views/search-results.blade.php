@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Search Results for "{{ $query }}"</h2>
    @if(empty($results) || (isset($results['users']) && $results['users']->isEmpty()) && (isset($results['foods']) && $results['foods']->isEmpty()))
        <div class="text-gray-600">No results found.</div>
    @endif
    @if(isset($results['users']) && $results['users']->isNotEmpty())
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Users</h3>
            <ul class="list-disc pl-6">
                @foreach($results['users'] as $user)
                    <li>{{ $user->name }} ({{ $user->email }})</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(isset($results['foods']) && $results['foods']->isNotEmpty())
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Foods</h3>
            <ul class="list-none pl-0 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($results['foods'] as $food)
                    <li class="flex items-center gap-4 p-3 bg-white rounded shadow">
                        @if(isset($food['img']))
                            <img src="{{ $food['img'] }}" alt="{{ $food['name'] }}" class="w-16 h-16 object-cover rounded-full border border-teal-300">
                        @endif
                        <div>
                            <div class="font-semibold text-teal-700">{{ $food['name'] }}</div>
                            <div class="text-gray-600 text-sm">{{ $food['desc'] }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Add more result sections for other models/views here --}}
</div>
@endsection
