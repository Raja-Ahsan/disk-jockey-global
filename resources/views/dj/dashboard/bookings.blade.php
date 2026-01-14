@extends('layouts.dj.master')

@section('title', 'My Bookings')
@section('page-title', 'My Bookings')
@section('page-description', 'View and manage all your bookings')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-4 sm:p-6">
        <form method="GET" action="{{ route('dj.dashboard.bookings') }}" class="flex flex-col sm:flex-row gap-4">
            <select name="status" class="flex-1 bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-6 py-3 bg-[#FFD900] text-[#333333] rounded-lg hover:bg-[#FFA500] font-bold transition-colors whitespace-nowrap">
                Filter
            </button>
            @if(request('status'))
                <a href="{{ route('dj.dashboard.bookings') }}" class="px-6 py-3 bg-[#282828] text-white rounded-lg hover:bg-[#353535] transition-colors whitespace-nowrap text-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if($bookings->count() > 0)
        <div class="space-y-4">
            @foreach($bookings as $booking)
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-4 sm:p-6 hover:border-[#FFD900] transition-all duration-300">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 sm:gap-3 mb-4 flex-wrap">
                            <span class="px-3 py-1 rounded text-xs sm:text-sm font-bold 
                                {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                                   ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                                   ($booking->booking_status === 'completed' ? 'bg-blue-500' : 
                                   ($booking->booking_status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                                text-white">
                                {{ strtoupper($booking->booking_status) }}
                            </span>
                            <span class="px-3 py-1 rounded text-xs sm:text-sm font-bold 
                                {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                                   ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                                text-white">
                                Payment: {{ strtoupper($booking->payment_status) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @if($booking->user)
                            <div>
                                <p class="text-gray-400 text-xs sm:text-sm mb-1">Client</p>
                                <p class="text-white font-semibold">{{ $booking->user->name }}</p>
                                <p class="text-gray-500 text-xs">{{ $booking->user->email }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-gray-400 text-xs sm:text-sm mb-1">Booking Date</p>
                                <p class="text-white font-semibold">{{ $booking->booking_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs sm:text-sm mb-1">Time</p>
                                <p class="text-white font-semibold">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs sm:text-sm mb-1">Venue</p>
                                <p class="text-white">{{ $booking->venue_address }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs sm:text-sm mb-1">Location</p>
                                <p class="text-white">{{ $booking->city }}, {{ $booking->state }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs sm:text-sm mb-1">Total Amount</p>
                                <p class="text-[#FFD900] text-xl font-bold">${{ number_format($booking->total_amount, 2) }}</p>
                                @if($booking->payment_status === 'partial')
                                    <p class="text-yellow-400 text-xs">Deposit: ${{ number_format($booking->deposit_amount, 2) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 sm:gap-3">
                        <a href="{{ route('dj.dashboard.bookings.show', $booking->id) }}" class="px-4 py-2 bg-[#FFD900] text-[#333333] rounded-lg hover:bg-[#FFA500] font-bold transition-colors text-center text-sm sm:text-base whitespace-nowrap">
                            View Details
                        </a>
                        @if($booking->booking_status === 'pending')
                        <form action="{{ route('bookings.confirm', $booking->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm sm:text-base whitespace-nowrap">
                                Confirm
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-8 sm:p-12 text-center">
            <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-white mb-2">No bookings found</h3>
            <p class="text-gray-400">You don't have any bookings yet.</p>
        </div>
    @endif
</div>
@endsection
