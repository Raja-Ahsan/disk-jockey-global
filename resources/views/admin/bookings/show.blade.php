@extends('layouts.admin.master')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')
@section('page-description', 'View and manage booking information')

@section('content')
<div class="space-y-6">
    <!-- Booking Header -->
    <div class="bg-gradient-to-r from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Booking #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <div class="flex items-center gap-3">
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
            <div class="text-right">
                <p class="text-gray-400 text-sm">Total Amount</p>
                <p class="text-[#FFD900] text-3xl font-bold">${{ number_format($booking->total_amount, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Update Form -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Update Booking</h2>
        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Booking Status</label>
                    <select name="booking_status" class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        <option value="pending" {{ $booking->booking_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->booking_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $booking->booking_status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $booking->booking_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">Payment Status</label>
                    <select name="payment_status" class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ $booking->payment_status === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="refunded" {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="mt-6 btn primary-button">Update Booking</button>
        </form>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Client Info -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Client Information</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Name</p>
                    <p class="text-white font-semibold">{{ $booking->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="text-white">{{ $booking->user->email }}</p>
                </div>
            </div>
        </div>

        <!-- DJ Info -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">DJ Information</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Stage Name</p>
                    <p class="text-white font-semibold">{{ $booking->dj->stage_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Location</p>
                    <p class="text-white">{{ $booking->dj->city ?? 'N/A' }}, {{ $booking->dj->state ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Booking Details</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Date</p>
                    <p class="text-white font-semibold">{{ $booking->booking_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Time</p>
                    <p class="text-white">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Venue</p>
                    <p class="text-white">{{ $booking->venue_address }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Location</p>
                    <p class="text-white">{{ $booking->city }}, {{ $booking->state }} {{ $booking->zipcode }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Payment Details</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <p class="text-gray-400">Total Amount</p>
                    <p class="text-white font-bold">${{ number_format($booking->total_amount, 2) }}</p>
                </div>
                @if($booking->deposit_amount)
                <div class="flex justify-between">
                    <p class="text-gray-400">Deposit</p>
                    <p class="text-white">${{ number_format($booking->deposit_amount, 2) }}</p>
                </div>
                @endif
                <div class="flex justify-between">
                    <p class="text-gray-400">Payment Status</p>
                    <span class="px-2 py-1 rounded text-xs font-bold 
                        {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                           ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                        text-white">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </div>
                @if($booking->stripe_payment_intent_id)
                <div>
                    <p class="text-gray-400 text-sm">Stripe Payment ID</p>
                    <p class="text-white text-sm font-mono">{{ $booking->stripe_payment_intent_id }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($booking->special_requests)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Special Requests</h2>
        <p class="text-white">{{ $booking->special_requests }}</p>
    </div>
    @endif

    @if($booking->cancellation_reason)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Cancellation Reason</h2>
        <p class="text-red-400">{{ $booking->cancellation_reason }}</p>
    </div>
    @endif
</div>
@endsection
