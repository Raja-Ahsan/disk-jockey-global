@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616]">
    <div class="container mx-auto px-6 lg:px-16 py-12">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Admin Dashboard</h1>
            <p class="text-gray-400">Welcome back, {{ Auth::user()->name }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535]">
                <h3 class="text-gray-400 text-sm mb-2">Total Users</h3>
                <p class="text-3xl font-bold text-[#FFD900]">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535]">
                <h3 class="text-gray-400 text-sm mb-2">Total DJs</h3>
                <p class="text-3xl font-bold text-[#FFD900]">{{ $stats['total_djs'] }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $stats['verified_djs'] }} verified</p>
            </div>
            <div class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535]">
                <h3 class="text-gray-400 text-sm mb-2">Total Bookings</h3>
                <p class="text-3xl font-bold text-[#FFD900]">{{ $stats['total_bookings'] }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $stats['pending_bookings'] }} pending</p>
            </div>
            <div class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535]">
                <h3 class="text-gray-400 text-sm mb-2">Total Revenue</h3>
                <p class="text-3xl font-bold text-[#FFD900]">${{ number_format($stats['total_revenue'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">${{ number_format($stats['pending_revenue'], 2) }} pending</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.djs.index') }}" class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535] hover:border-[#FFD900] transition-colors">
                <h3 class="text-xl font-bold text-white mb-2">Manage DJs</h3>
                <p class="text-gray-400">View and manage all DJ profiles</p>
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535] hover:border-[#FFD900] transition-colors">
                <h3 class="text-xl font-bold text-white mb-2">Manage Bookings</h3>
                <p class="text-gray-400">View and manage all bookings</p>
            </a>
            <a href="{{ route('admin.events.index') }}" class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535] hover:border-[#FFD900] transition-colors">
                <h3 class="text-xl font-bold text-white mb-2">Manage Events</h3>
                <p class="text-gray-400">View and manage all events</p>
            </a>
        </div>

        <!-- Recent Bookings -->
        <div class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535] mb-8">
            <h2 class="text-2xl font-bold text-white mb-4">Recent Bookings</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b border-[#353535]">
                        <tr>
                            <th class="pb-3 text-gray-400 font-normal">Client</th>
                            <th class="pb-3 text-gray-400 font-normal">DJ</th>
                            <th class="pb-3 text-gray-400 font-normal">Date</th>
                            <th class="pb-3 text-gray-400 font-normal">Amount</th>
                            <th class="pb-3 text-gray-400 font-normal">Status</th>
                            <th class="pb-3 text-gray-400 font-normal">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr class="border-b border-[#353535]">
                            <td class="py-3 text-white">{{ $booking->user->name }}</td>
                            <td class="py-3 text-white">{{ $booking->dj->stage_name }}</td>
                            <td class="py-3 text-gray-400">{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td class="py-3 text-[#FFD900]">${{ number_format($booking->total_amount, 2) }}</td>
                            <td class="py-3">
                                <span class="px-3 py-1 rounded text-xs {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 'bg-gray-500') }} text-white">
                                    {{ ucfirst($booking->booking_status) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-[#FFD900] hover:underline">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">No bookings yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent DJs -->
        <div class="bg-[#1C1C1C] p-6 rounded-lg border border-[#353535]">
            <h2 class="text-2xl font-bold text-white mb-4">Recent DJs</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($recentDjs as $dj)
                <div class="bg-[#262626] p-4 rounded-lg">
                    <h3 class="text-white font-bold mb-2">{{ $dj->stage_name }}</h3>
                    <p class="text-gray-400 text-sm mb-2">{{ $dj->user->email }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs px-2 py-1 rounded {{ $dj->is_verified ? 'bg-green-500' : 'bg-yellow-500' }} text-white">
                            {{ $dj->is_verified ? 'Verified' : 'Pending' }}
                        </span>
                        <a href="{{ route('admin.djs.show', $dj->id) }}" class="text-[#FFD900] text-sm hover:underline">View</a>
                    </div>
                </div>
                @empty
                <p class="text-gray-400">No DJs yet</p>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
