@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-12 px-6">
    <div class="container mx-auto max-w-7xl">
        <!-- Header Section -->
        <div class="mb-8" data-aos="fade-down">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                        Welcome back, <span class="text-[#FFD900]">{{ $user->name }}</span>
                    </h1>
                    <p class="text-gray-400 text-lg">{{ $user->email }}</p>
                    @if($user->isDJ() && $user->dj)
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-3 py-1 bg-[#FFD900] text-[#333333] text-sm font-bold rounded-full">
                                DJ Profile
                            </span>
                            @if($user->dj->is_verified)
                                <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">
                                    ✓ Verified
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <a href="{{ route('profile.edit') }}" class="btn primary-button">
                    Edit Profile
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400" data-aos="fade-down">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
            <!-- Total Bookings -->
            <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-[#FFD900]/20 rounded-lg">
                        <svg class="w-8 h-8 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Total Bookings</h3>
                <p class="text-3xl font-bold text-white">{{ $user->bookings->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $user->bookings->where('booking_status', 'pending')->count() }} pending</p>
            </div>

            <!-- Completed Bookings -->
            <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Completed</h3>
                <p class="text-3xl font-bold text-white">{{ $user->bookings->where('booking_status', 'completed')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Successfully completed</p>
            </div>

            <!-- Total Spent -->
            <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Total Spent</h3>
                <p class="text-3xl font-bold text-white">${{ number_format($user->bookings->where('payment_status', 'paid')->sum('total_amount'), 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">All time</p>
            </div>

            <!-- Upcoming Bookings -->
            <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-500/20 rounded-lg">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-sm mb-1">Upcoming</h3>
                <p class="text-3xl font-bold text-white">{{ $user->bookings->whereIn('booking_status', ['pending', 'confirmed'])->where('booking_date', '>=', now()->toDateString())->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Scheduled events</p>
            </div>
        </div>

        <!-- DJ Stats (if DJ) -->
        @if($user->isDJ() && $user->dj)
        <div class="mb-8" data-aos="fade-up">
            <div class="bg-gradient-to-r from-[#1F1F1F] via-[#282828] to-[#1F1F1F] border border-[#353535] rounded-xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">DJ Performance</h2>
                    <a href="{{ route('dj.show', $user->dj->id) }}" class="text-[#FFD900] hover:underline">View Profile →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Rating</p>
                        <div class="flex items-center gap-2">
                            <p class="text-3xl font-bold text-white">{{ number_format($user->dj->rating, 1) }}</p>
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($user->dj->rating))
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Reviews</p>
                        <p class="text-3xl font-bold text-white">{{ $user->dj->total_reviews }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Bookings</p>
                        <p class="text-3xl font-bold text-white">{{ $user->dj->total_bookings }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Hourly Rate</p>
                        <p class="text-3xl font-bold text-[#FFD900]">${{ number_format($user->dj->hourly_rate, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Bookings -->
        <div class="mb-8" data-aos="fade-up">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-white">Recent Bookings</h2>
                <a href="{{ route('bookings.index') }}" class="text-[#FFD900] hover:underline font-semibold">View All →</a>
            </div>
            @if($user->bookings->count() > 0)
                <div class="space-y-4">
                    @foreach($user->bookings->take(5) as $booking)
                    <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6 hover:border-[#FFD900] transition-all duration-300">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded text-sm font-bold 
                                            {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                                               ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                                               ($booking->booking_status === 'completed' ? 'bg-blue-500' : 'bg-gray-500')) }} 
                                            text-white">
                                            {{ strtoupper($booking->booking_status) }}
                                        </span>
                                        <span class="px-3 py-1 rounded text-sm font-bold 
                                            {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                                               ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                                            text-white">
                                            {{ strtoupper($booking->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">{{ $booking->dj->stage_name ?? 'N/A' }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-400">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $booking->booking_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>{{ $booking->city }}, {{ $booking->state }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-[#FFD900] font-bold">${{ number_format($booking->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn primary-button whitespace-nowrap">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-12 text-center">
                    <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-white mb-2">No bookings yet</h3>
                    <p class="text-gray-400 mb-6">Start booking your favorite DJs!</p>
                    <a href="{{ route('browse') }}" class="btn primary-button inline-block">
                        Browse DJs
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Orders -->
        @if($user->orders->count() > 0)
        <div class="mb-8" data-aos="fade-up">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-white">Recent Orders</h2>
                <a href="{{ route('merch') }}" class="text-[#FFD900] hover:underline font-semibold">Shop More →</a>
            </div>
            <div class="space-y-4">
                @foreach($user->orders->take(5) as $order)
                <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6 hover:border-[#FFD900] transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 rounded text-sm font-bold 
                                    {{ $order->status === 'delivered' ? 'bg-green-500' : 
                                       ($order->status === 'shipped' ? 'bg-blue-500' : 
                                       ($order->status === 'processing' ? 'bg-yellow-500' : 
                                       ($order->status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                                    text-white">
                                    {{ strtoupper($order->status) }}
                                </span>
                                <span class="px-3 py-1 rounded text-sm font-bold 
                                    {{ $order->payment_status === 'paid' ? 'bg-green-500' : 
                                       ($order->payment_status === 'failed' ? 'bg-red-500' : 'bg-gray-500') }} 
                                    text-white">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Order {{ $order->order_number }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-400">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span>{{ $order->items->count() }} item(s)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-[#FFD900] font-bold">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('orders.confirmation', $order->id) }}" class="btn primary-button whitespace-nowrap">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up">
            <a href="{{ route('browse') }}" class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20 group">
                <div class="p-3 bg-[#FFD900]/20 rounded-lg w-fit mb-4 group-hover:bg-[#FFD900]/30 transition-colors">
                    <svg class="w-8 h-8 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Browse DJs</h3>
                <p class="text-gray-400">Find the perfect DJ for your event</p>
            </a>

            @if($user->isDJ() && !$user->dj)
            <a href="{{ route('dj.create') }}" class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20 group">
                <div class="p-3 bg-[#FFD900]/20 rounded-lg w-fit mb-4 group-hover:bg-[#FFD900]/30 transition-colors">
                    <svg class="w-8 h-8 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Create DJ Profile</h3>
                <p class="text-gray-400">Set up your DJ profile to get bookings</p>
            </a>
            @elseif($user->isDJ() && $user->dj)
            <a href="{{ route('dj.edit', $user->dj->id) }}" class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20 group">
                <div class="p-3 bg-[#FFD900]/20 rounded-lg w-fit mb-4 group-hover:bg-[#FFD900]/30 transition-colors">
                    <svg class="w-8 h-8 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Edit DJ Profile</h3>
                <p class="text-gray-400">Update your DJ profile information</p>
            </a>
            @endif

            <a href="{{ route('profile.edit') }}" class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20 group">
                <div class="p-3 bg-blue-500/20 rounded-lg w-fit mb-4 group-hover:bg-blue-500/30 transition-colors">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Edit Profile</h3>
                <p class="text-gray-400">Update your account information</p>
            </a>
        </div>
    </div>
</main>
@endsection
