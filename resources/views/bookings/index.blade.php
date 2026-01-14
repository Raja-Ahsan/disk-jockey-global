@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-12 px-6">
    <div class="container mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-8" data-aos="fade-down">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                        My <span class="text-[#FFD900]">Bookings</span>
                    </h1>
                    <p class="text-gray-400">View and manage all your bookings</p>
                </div>
                <a href="{{ route('browse') }}" class="btn primary-button">
                    Book a DJ
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400" data-aos="fade-down">
                {{ session('success') }}
            </div>
        @endif

        @if($bookings->count() > 0)
            <div class="space-y-4" data-aos="fade-up">
                @foreach($bookings as $booking)
                <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6 hover:border-[#FFD900] transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-3 py-1 rounded text-sm font-bold 
                                        {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                                           ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                                           ($booking->booking_status === 'completed' ? 'bg-blue-500' : 
                                           ($booking->booking_status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                                        text-white">
                                        {{ strtoupper($booking->booking_status) }}
                                    </span>
                                    <span class="px-3 py-1 rounded text-sm font-bold 
                                        {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                                           ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                                        text-white">
                                        Payment: {{ strtoupper($booking->payment_status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex flex-col md:flex-row md:items-start gap-6">
                                @if($booking->dj)
                                <div class="flex items-center gap-4">
                                    <img src="{{ $booking->dj->profile_image ? asset('storage/' . $booking->dj->profile_image) : asset('images/talent-img-00' . (($booking->dj->id % 3) + 1) . '.png') }}" 
                                         alt="{{ $booking->dj->stage_name }}" 
                                         class="w-20 h-20 rounded-lg object-cover">
                                    <div>
                                        <h3 class="text-xl font-bold text-white mb-1">{{ $booking->dj->stage_name }}</h3>
                                        <p class="text-gray-400 text-sm">{{ $booking->dj->city }}, {{ $booking->dj->state }}</p>
                                        <a href="{{ route('dj.show', $booking->dj->id) }}" class="text-[#FFD900] hover:underline text-sm">View Profile</a>
                                    </div>
                                </div>
                                @endif

                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-400 text-sm mb-1">Booking Date</p>
                                        <p class="text-white font-semibold">{{ $booking->booking_date->format('F d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm mb-1">Time</p>
                                        <p class="text-white font-semibold">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                            {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm mb-1">Venue</p>
                                        <p class="text-white">{{ $booking->venue_address }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm mb-1">Location</p>
                                        <p class="text-white">{{ $booking->city }}, {{ $booking->state }} {{ $booking->zipcode }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm mb-1">Total Amount</p>
                                        <p class="text-[#FFD900] text-xl font-bold">${{ number_format($booking->total_amount, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn primary-button whitespace-nowrap text-center">
                                View Details
                            </a>
                            @if($booking->booking_status === 'pending' || $booking->booking_status === 'confirmed')
                            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to cancel this booking?')"
                                        class="btn secondary-button w-full whitespace-nowrap">
                                    Cancel Booking
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8" data-aos="fade-up">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-12 text-center" data-aos="fade-up">
                <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-white mb-2">No bookings yet</h3>
                <p class="text-gray-400 mb-6">Start booking your favorite DJs for your events!</p>
                <a href="{{ route('browse') }}" class="btn primary-button inline-block">
                    Browse DJs
                </a>
            </div>
        @endif
    </div>
</main>
@endsection
