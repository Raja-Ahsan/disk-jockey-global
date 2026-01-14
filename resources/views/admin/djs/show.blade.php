@extends('layouts.admin.master')

@section('title', 'DJ Details')
@section('page-title', 'DJ Details')
@section('page-description', 'View detailed information about the DJ')

@section('content')
<div class="space-y-6">
    <!-- DJ Profile Header -->
    <div class="bg-gradient-to-r from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-8">
        <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
            <img src="{{ $dj->profile_image ? asset('storage/' . $dj->profile_image) : asset('images/talent-img-00' . (($dj->id % 3) + 1) . '.png') }}" 
                 alt="{{ $dj->stage_name }}" 
                 class="w-32 h-32 rounded-xl object-cover">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-3xl font-bold text-white">{{ $dj->stage_name }}</h1>
                    @if($dj->is_verified)
                        <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">✓ Verified</span>
                    @endif
                </div>
                <p class="text-gray-400 mb-2">{{ $dj->user->name }} ({{ $dj->user->email }})</p>
                <p class="text-gray-400 mb-4">{{ $dj->city }}, {{ $dj->state }} {{ $dj->zipcode }}</p>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-1">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-white font-bold text-xl">{{ number_format($dj->rating, 1) }}</span>
                        <span class="text-gray-400">({{ $dj->total_reviews }} reviews)</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Hourly Rate:</span>
                        <span class="text-[#FFD900] font-bold text-xl ml-2">${{ number_format($dj->hourly_rate, 0) }}/hr</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.djs.edit', $dj->id) }}" class="btn primary-button">Edit</a>
                @if(!$dj->is_verified)
                    <form action="{{ route('admin.djs.verify', $dj->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn secondary-button bg-green-500 hover:bg-green-600">Verify</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Total Bookings</h3>
            <p class="text-3xl font-bold text-white">{{ $dj->total_bookings }}</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Total Reviews</h3>
            <p class="text-3xl font-bold text-white">{{ $dj->total_reviews }}</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Experience</h3>
            <p class="text-3xl font-bold text-white">{{ $dj->experience_years }} years</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Status</h3>
            <p class="text-lg font-bold {{ $dj->is_available ? 'text-green-500' : 'text-red-500' }}">
                {{ $dj->is_available ? 'Available' : 'Unavailable' }}
            </p>
        </div>
    </div>

    <!-- Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bio & Info -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Bio & Information</h2>
            <div class="space-y-4">
                <div>
                    <h3 class="text-gray-400 text-sm mb-1">Bio</h3>
                    <p class="text-white">{{ $dj->bio ?? 'No bio provided' }}</p>
                </div>
                @if($dj->genres)
                <div>
                    <h3 class="text-gray-400 text-sm mb-2">Genres</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($dj->genres as $genre)
                            <span class="px-3 py-1 bg-[#282828] text-white rounded-full text-sm">{{ $genre }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($dj->specialties)
                <div>
                    <h3 class="text-gray-400 text-sm mb-2">Specialties</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($dj->specialties as $specialty)
                            <span class="px-3 py-1 bg-[#FFD900] text-[#333333] rounded-full text-sm font-semibold">{{ $specialty }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($dj->equipment)
                <div>
                    <h3 class="text-gray-400 text-sm mb-1">Equipment</h3>
                    <p class="text-white">{{ $dj->equipment }}</p>
                </div>
                @endif
                @if($dj->phone)
                <div>
                    <h3 class="text-gray-400 text-sm mb-1">Phone</h3>
                    <p class="text-white">{{ $dj->phone }}</p>
                </div>
                @endif
                @if($dj->website)
                <div>
                    <h3 class="text-gray-400 text-sm mb-1">Website</h3>
                    <a href="{{ $dj->website }}" target="_blank" class="text-[#FFD900] hover:underline">{{ $dj->website }}</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Categories</h2>
            @if($dj->categories->count() > 0)
                <div class="space-y-2">
                    @foreach($dj->categories as $category)
                        <div class="flex items-center justify-between p-3 bg-[#282828] rounded-lg">
                            <span class="text-white">{{ $category->name }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">No categories assigned</p>
            @endif
        </div>
    </div>

    <!-- Recent Bookings -->
    @if($dj->bookings->count() > 0)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Recent Bookings</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Client</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Date</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Amount</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dj->bookings->take(10) as $booking)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-4 py-3 text-white">{{ $booking->user->name }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $booking->booking_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-[#FFD900] font-semibold">${{ number_format($booking->total_amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                                   ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                                   ($booking->booking_status === 'completed' ? 'bg-blue-500' : 'bg-gray-500')) }} 
                                text-white">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Reviews -->
    @if($dj->reviews->count() > 0)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Recent Reviews</h2>
        <div class="space-y-4">
            @foreach($dj->reviews->take(5) as $review)
            <div class="bg-[#282828] p-4 rounded-lg">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <p class="text-white font-semibold">{{ $review->user->name }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $review->rating)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm">{{ $review->created_at->format('M d, Y') }}</span>
                </div>
                @if($review->comment)
                    <p class="text-gray-300 mt-2">{{ $review->comment }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
