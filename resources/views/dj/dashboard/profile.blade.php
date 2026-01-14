@extends('layouts.dj.master')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-description', 'View your DJ profile')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            @php
                $imageIndex = ($dj->id % 3) + 1;
                $defaultImage = 'images/talent-img-00' . $imageIndex . '.png';
            @endphp
            <div class="flex-shrink-0">
                <img src="{{ $dj->profile_image ? asset('storage/' . $dj->profile_image) : asset($defaultImage) }}" 
                     alt="{{ $dj->stage_name }}" 
                     class="w-32 h-32 md:w-40 md:h-40 rounded-lg object-cover border-4 border-[#FFD900]">
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-bold text-[#FFD900] mb-2">{{ $dj->stage_name }}</h1>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-4">
                    <div class="flex items-center text-gray-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $dj->city }}, {{ $dj->state }}</span>
                    </div>
                    @if($dj->rating > 0)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-white font-bold">{{ number_format($dj->rating, 1) }}</span>
                        <span class="text-gray-400 ml-1">({{ $dj->total_reviews }})</span>
                    </div>
                    @endif
                    @if($dj->is_verified)
                        <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">✓ Verified</span>
                    @else
                        <span class="px-3 py-1 bg-yellow-500 text-white text-sm font-bold rounded-full">Pending Verification</span>
                    @endif
                    @if($dj->is_available)
                        <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">Available</span>
                    @else
                        <span class="px-3 py-1 bg-gray-500 text-white text-sm font-bold rounded-full">Not Available</span>
                    @endif
                </div>
                <p class="text-gray-300 text-lg mb-4">{{ $dj->bio ?? 'Professional DJ/MC with years of experience.' }}</p>
                <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                    @if($dj->specialties)
                        @foreach($dj->specialties as $specialty)
                            <span class="bg-[#FFD900] text-[#333333] px-3 py-1 rounded font-semibold text-sm">{{ $specialty }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('dj.dashboard.edit') }}" class="btn primary-button">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Hourly Rate</h3>
            <p class="text-3xl font-bold text-[#FFD900]">${{ number_format($dj->hourly_rate, 0) }}/hr</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Experience</h3>
            <p class="text-3xl font-bold text-white">{{ $dj->experience_years }} Years</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Total Bookings</h3>
            <p class="text-3xl font-bold text-white">{{ $dj->total_bookings ?? 0 }}</p>
        </div>
    </div>

    <!-- Details Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Genres -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Genres</h2>
            @if($dj->genres && count($dj->genres) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($dj->genres as $genre)
                        <span class="px-3 py-1 bg-[#282828] text-white rounded">{{ $genre }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">No genres specified</p>
            @endif
        </div>

        <!-- Categories -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Categories</h2>
            @if($dj->categories && $dj->categories->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($dj->categories as $category)
                        <span class="px-3 py-1 bg-[#282828] text-white rounded">{{ $category->name }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">No categories assigned</p>
            @endif
        </div>

        <!-- Equipment -->
        @if($dj->equipment)
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Equipment</h2>
            <p class="text-gray-300">{{ $dj->equipment }}</p>
        </div>
        @endif

        <!-- Contact Info -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Contact Information</h2>
            <div class="space-y-2">
                @if($dj->phone)
                <div class="flex items-center text-gray-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>{{ $dj->phone }}</span>
                </div>
                @endif
                @if($dj->website)
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    <a href="{{ $dj->website }}" target="_blank" class="text-[#FFD900] hover:underline">{{ $dj->website }}</a>
                </div>
                @endif
                <div class="flex items-center text-gray-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $dj->user->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    @if($dj->reviews && $dj->reviews->count() > 0)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Recent Reviews</h2>
        <div class="space-y-4">
            @foreach($dj->reviews->take(5) as $review)
            <div class="border-b border-[#282828] pb-4 last:border-0 last:pb-0">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-white font-semibold">{{ $review->user->name }}</span>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm">{{ $review->created_at->format('M d, Y') }}</span>
                </div>
                <p class="text-gray-300">{{ $review->comment }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
