@extends('layouts.dj.master')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')
@section('page-description', 'View booking information and manage status')

@section('content')
<div class="max-w-6xl space-y-6">
    <!-- Status Badges -->
    <div class="flex items-center gap-4 flex-wrap">
        <span class="px-4 py-2 rounded text-sm font-bold 
            {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
               ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
               ($booking->booking_status === 'completed' ? 'bg-blue-500' : 'bg-gray-500')) }} 
            text-white">
            {{ strtoupper($booking->booking_status) }}
        </span>
        <span class="px-4 py-2 rounded text-sm font-bold 
            {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
               ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
            text-white">
            Payment: {{ strtoupper($booking->payment_status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Booking Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <p class="text-gray-400 text-sm mb-1">Venue Address</p>
                        <p class="text-white">{{ $booking->venue_address }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Location</p>
                        <p class="text-white">{{ $booking->city }}, {{ $booking->state }} {{ $booking->zipcode }}</p>
                    </div>
                    @if($booking->special_requests)
                    <div class="md:col-span-2">
                        <p class="text-gray-400 text-sm mb-1">Special Requests</p>
                        <p class="text-white">{{ $booking->special_requests }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Client Information -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Client Information</h2>
                @if($booking->user)
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-[#FFD900] flex items-center justify-center text-[#333333] font-bold text-xl">
                        {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1">{{ $booking->user->name }}</h3>
                        <p class="text-gray-400">{{ $booking->user->email }}</p>
                        @if($booking->user->phone)
                            <p class="text-gray-400 text-sm">{{ $booking->user->phone }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Summary -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Payment Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Amount</span>
                        <span class="text-[#FFD900] text-xl font-bold">${{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                    @if($booking->payment_status === 'partial')
                    <div class="flex justify-between">
                        <span class="text-gray-400">Deposit Paid</span>
                        <span class="text-green-400 font-semibold">${{ number_format($booking->deposit_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-[#282828]">
                        <span class="text-gray-400">Remaining</span>
                        <span class="text-white font-semibold">${{ number_format($booking->total_amount - $booking->deposit_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Actions</h2>
                <div class="space-y-3">
                    @if($booking->booking_status === 'pending')
                    <form action="{{ route('bookings.confirm', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-semibold">
                            Confirm Booking
                        </button>
                    </form>
                    @endif

                    @if($booking->booking_status === 'confirmed')
                    <form action="{{ route('bookings.complete', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Mark this booking as completed?')" 
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold">
                            Mark as Completed
                        </button>
                    </form>
                    @endif

                    @if(in_array($booking->booking_status, ['pending', 'confirmed']))
                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to cancel this booking?')" 
                                class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-semibold">
                            Cancel Booking
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('dj.dashboard.bookings') }}" class="block w-full px-4 py-2 bg-[#282828] text-white rounded-lg hover:bg-[#353535] transition-colors text-center font-semibold">
                        Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
