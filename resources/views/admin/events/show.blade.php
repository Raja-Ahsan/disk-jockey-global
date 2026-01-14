@extends('layouts.admin.master')

@section('title', 'Event Details')
@section('page-title', 'Event Details')
@section('page-description', 'View complete event information and project details')

@section('content')
<div class="space-y-6">
    <!-- Event Header -->
    <div class="bg-gradient-to-r from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $event->title }}</h1>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded text-sm font-bold 
                        {{ $event->status === 'confirmed' ? 'bg-green-500' : 
                           ($event->status === 'pending' ? 'bg-yellow-500' : 
                           ($event->status === 'completed' ? 'bg-blue-500' : 
                           ($event->status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                        text-white">
                        {{ strtoupper($event->status) }}
                    </span>
                    @if($event->event_type)
                        <span class="px-3 py-1 bg-[#FFD900] text-[#333333] text-sm font-bold rounded">{{ $event->event_type }}</span>
                    @endif
                </div>
            </div>
            @if($event->budget_min || $event->budget_max)
            <div class="text-right">
                <p class="text-gray-400 text-sm">Budget Range</p>
                <p class="text-[#FFD900] text-2xl font-bold">
                    ${{ number_format($event->budget_min ?? 0, 0) }} - ${{ number_format($event->budget_max ?? 0, 0) }}
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Update Status -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Update Event Status</h2>
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-white font-semibold mb-2">Status</label>
                    <select name="status" class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        <option value="pending" {{ $event->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $event->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $event->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $event->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn primary-button">Update Status</button>
            </div>
        </form>
    </div>

    <!-- Project Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Event Information -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Event Information</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Title</p>
                    <p class="text-white font-semibold">{{ $event->title }}</p>
                </div>
                @if($event->event_type)
                <div>
                    <p class="text-gray-400 text-sm mb-1">Event Type</p>
                    <p class="text-white">{{ $event->event_type }}</p>
                </div>
                @endif
                @if($event->description)
                <div>
                    <p class="text-gray-400 text-sm mb-1">Description</p>
                    <p class="text-white">{{ $event->description }}</p>
                </div>
                @endif
                <div>
                    <p class="text-gray-400 text-sm mb-1">Event Date</p>
                    <p class="text-white font-semibold">{{ $event->event_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Time</p>
                    <p class="text-white">
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - 
                        {{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : 'N/A' }}
                    </p>
                </div>
                @if($event->guest_count)
                <div>
                    <p class="text-gray-400 text-sm mb-1">Expected Guests</p>
                    <p class="text-white font-semibold">{{ number_format($event->guest_count) }} guests</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Venue Information -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Venue Information</h2>
            <div class="space-y-4">
                @if($event->venue_name)
                <div>
                    <p class="text-gray-400 text-sm mb-1">Venue Name</p>
                    <p class="text-white font-semibold">{{ $event->venue_name }}</p>
                </div>
                @endif
                <div>
                    <p class="text-gray-400 text-sm mb-1">Address</p>
                    <p class="text-white">{{ $event->address }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Location</p>
                    <p class="text-white">{{ $event->city }}, {{ $event->state }} {{ $event->zipcode }}</p>
                </div>
            </div>
        </div>

        <!-- Budget Information -->
        @if($event->budget_min || $event->budget_max)
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Budget Information</h2>
            <div class="space-y-3">
                @if($event->budget_min)
                <div class="flex justify-between">
                    <p class="text-gray-400">Minimum Budget</p>
                    <p class="text-white font-semibold">${{ number_format($event->budget_min, 2) }}</p>
                </div>
                @endif
                @if($event->budget_max)
                <div class="flex justify-between">
                    <p class="text-gray-400">Maximum Budget</p>
                    <p class="text-white font-semibold">${{ number_format($event->budget_max, 2) }}</p>
                </div>
                @endif
                @if($event->budget_min && $event->budget_max)
                <div class="pt-3 border-t border-[#282828]">
                    <div class="flex justify-between">
                        <p class="text-gray-400">Budget Range</p>
                        <p class="text-[#FFD900] font-bold text-lg">${{ number_format($event->budget_min, 0) }} - ${{ number_format($event->budget_max, 0) }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Requirements -->
        @if($event->requirements && count($event->requirements) > 0)
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Requirements & Preferences</h2>
            <div class="space-y-2">
                @foreach($event->requirements as $requirement)
                    <div class="flex items-center gap-2 p-2 bg-[#282828] rounded-lg">
                        <svg class="w-5 h-5 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-white">{{ $requirement }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Organizer Information -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Organizer Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-400 text-sm mb-1">Name</p>
                <p class="text-white font-semibold text-lg">{{ $event->user->name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Email</p>
                <p class="text-white">{{ $event->user->email }}</p>
            </div>
        </div>
    </div>

    <!-- Related Bookings with DJ Details -->
    @if($event->bookings && $event->bookings->count() > 0)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Booked DJs ({{ $event->bookings->count() }})</h2>
        <div class="space-y-6">
            @foreach($event->bookings as $booking)
            @if($booking->dj)
            <div class="bg-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- DJ Profile Section -->
                    <div class="lg:col-span-1">
                        <div class="flex flex-col md:flex-row lg:flex-col gap-4">
                            <div class="flex-shrink-0">
                                <img src="{{ $booking->dj->profile_image ? asset('storage/' . $booking->dj->profile_image) : asset('images/talent-img-00' . (($booking->dj->id % 3) + 1) . '.png') }}" 
                                     alt="{{ $booking->dj->stage_name }}" 
                                     class="w-32 h-32 rounded-xl object-cover mx-auto md:mx-0 lg:mx-auto">
                            </div>
                            <div class="flex-1 text-center md:text-left lg:text-center">
                                <h3 class="text-xl font-bold text-white mb-2">{{ $booking->dj->stage_name }}</h3>
                                <div class="flex items-center justify-center md:justify-start lg:justify-center gap-2 mb-2">
                                    @if($booking->dj->is_verified)
                                        <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded">Verified</span>
                                    @endif
                                    @if($booking->dj->rating > 0)
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span class="text-white font-semibold">{{ number_format($booking->dj->rating, 1) }}</span>
                                            <span class="text-gray-400 text-xs">({{ $booking->dj->total_reviews }})</span>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-gray-400 text-sm mb-2">{{ $booking->dj->city }}, {{ $booking->dj->state }}</p>
                                <p class="text-[#FFD900] font-bold">${{ number_format($booking->dj->hourly_rate, 0) }}/hr</p>
                                <a href="{{ route('admin.djs.show', $booking->dj->id) }}" class="text-[#FFD900] hover:underline text-sm mt-2 inline-block">View DJ Profile →</a>
                            </div>
                        </div>
                    </div>

                    <!-- DJ Details Section -->
                    <div class="lg:col-span-1">
                        <h4 class="text-white font-semibold mb-3">DJ Details</h4>
                        <div class="space-y-2 text-sm">
                            @if($booking->dj->specialties && count($booking->dj->specialties) > 0)
                            <div>
                                <p class="text-gray-400 mb-1">Specialties</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($booking->dj->specialties, 0, 3) as $specialty)
                                        <span class="px-2 py-1 bg-[#FFD900] text-[#333333] text-xs font-semibold rounded">{{ $specialty }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($booking->dj->genres && count($booking->dj->genres) > 0)
                            <div>
                                <p class="text-gray-400 mb-1">Genres</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($booking->dj->genres, 0, 3) as $genre)
                                        <span class="px-2 py-1 bg-[#353535] text-white text-xs rounded">{{ $genre }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($booking->dj->experience_years)
                            <div>
                                <p class="text-gray-400">Experience</p>
                                <p class="text-white font-semibold">{{ $booking->dj->experience_years }} years</p>
                            </div>
                            @endif
                            @if($booking->dj->total_bookings)
                            <div>
                                <p class="text-gray-400">Total Bookings</p>
                                <p class="text-white font-semibold">{{ $booking->dj->total_bookings }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Booking Details Section -->
                    <div class="lg:col-span-1">
                        <h4 class="text-white font-semibold mb-3">Booking Details</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-gray-400 text-sm">Booking Date</p>
                                <p class="text-white font-semibold">{{ $booking->booking_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Time</p>
                                <p class="text-white">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Total Amount</p>
                                <p class="text-[#FFD900] font-bold text-lg">${{ number_format($booking->total_amount, 2) }}</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                                       ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                                       ($booking->booking_status === 'completed' ? 'bg-blue-500' : 'bg-gray-500')) }} 
                                    text-white">
                                    {{ ucfirst($booking->booking_status) }}
                                </span>
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                                       ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                                    text-white">
                                    Payment: {{ ucfirst($booking->payment_status) }}
                                </span>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-[#FFD900] hover:underline text-sm font-semibold">View Full Booking →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-400 text-lg">No bookings associated with this event yet.</p>
        </div>
    </div>
    @endif
</div>
@endsection
