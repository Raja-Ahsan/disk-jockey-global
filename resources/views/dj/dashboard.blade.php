@extends('layouts.dj.master')

@section('title', 'DJ Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Manage your bookings and track your performance')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Welcome, <span class="text-[#FFD900]">{{ $dj->stage_name }}</span>
                </h1>
                <div class="flex items-center gap-3">
                    @if($dj->is_verified)
                        <span class="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-full">✓ Verified</span>
                    @else
                        <span class="px-3 py-1 bg-yellow-500 text-white text-sm font-bold rounded-full">Pending Verification</span>
                    @endif
                    <span class="px-3 py-1 bg-[#FFD900] text-[#333333] text-sm font-bold rounded-full">
                        {{ $dj->city }}, {{ $dj->state }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-gray-400 text-sm">Hourly Rate</p>
                    <p class="text-[#FFD900] text-2xl font-bold">${{ number_format($dj->hourly_rate, 0) }}/hr</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 text-sm">Rating</p>
                    <div class="flex items-center gap-1">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-white font-bold text-xl">{{ number_format($dj->rating, 1) }}</span>
                        <span class="text-gray-400">({{ $dj->total_reviews }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Bookings -->
        <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-400 text-sm mb-1">Total Bookings</h3>
            <p class="text-3xl font-bold text-white">{{ $stats['total_bookings'] }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $stats['pending_bookings'] }} pending</p>
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
            <p class="text-3xl font-bold text-white">{{ $stats['completed_bookings'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Successfully completed</p>
        </div>

        <!-- Total Earnings -->
        <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-[#FFD900]/20 rounded-lg">
                    <svg class="w-8 h-8 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-400 text-sm mb-1">Total Earnings</h3>
            <p class="text-3xl font-bold text-[#FFD900]">${{ number_format($stats['total_earnings'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-2">
                @if($stats['this_month_earnings'] > $stats['last_month_earnings'])
                    <span class="text-green-400">↑</span>
                @else
                    <span class="text-red-400">↓</span>
                @endif
                This month: ${{ number_format($stats['this_month_earnings'], 2) }}
            </p>
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
            <p class="text-3xl font-bold text-white">{{ $upcomingBookings->count() }}</p>
            <p class="text-xs text-gray-500 mt-2">Scheduled events</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Earnings Chart -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Monthly Earnings</h2>
            <canvas id="earningsChart" height="100"></canvas>
        </div>

        <!-- Booking Status Chart -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Booking Status Distribution</h2>
            <canvas id="bookingStatusChart" height="100"></canvas>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Confirmed Bookings</h3>
            <p class="text-3xl font-bold text-green-500">{{ $stats['confirmed_bookings'] }}</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Pending Earnings</h3>
            <p class="text-3xl font-bold text-yellow-500">${{ number_format($stats['pending_earnings'], 2) }}</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Cancelled Bookings</h3>
            <p class="text-3xl font-bold text-red-500">{{ $stats['cancelled_bookings'] }}</p>
        </div>
    </div>

    <!-- Upcoming Bookings -->
    @if($upcomingBookings->count() > 0)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Upcoming Bookings</h2>
            <a href="{{ route('dj.dashboard.bookings') }}" class="text-[#FFD900] hover:underline text-sm">View All →</a>
        </div>
        <div class="space-y-4">
            @foreach($upcomingBookings as $booking)
            <div class="bg-[#282828] border border-[#353535] rounded-lg p-4 hover:border-[#FFD900] transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-white font-semibold">{{ $booking->user->name }}</h3>
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 'bg-yellow-500' }} 
                                text-white">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $booking->booking_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $booking->city }}, {{ $booking->state }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-[#FFD900] font-bold">${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('dj.dashboard.bookings.show', $booking->id) }}" class="btn primary-button whitespace-nowrap">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Bookings -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Recent Bookings</h2>
            <a href="{{ route('dj.dashboard.bookings') }}" class="text-[#FFD900] hover:underline text-sm">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Client</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Date</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Amount</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Payment</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
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
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                                   ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                                text-white">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('dj.dashboard.bookings.show', $booking->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">No bookings yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Orders -->
@if($recentOrders && $recentOrders->count() > 0)
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white">Recent Orders</h2>
        <a href="{{ route('merch') }}" class="text-[#FFD900] hover:underline text-sm">Shop More →</a>
    </div>
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Order #</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Date</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Items</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Total</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-4 py-3 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-4 py-3 text-white font-semibold">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $order->items->count() }} item(s)</td>
                        <td class="px-4 py-3 text-[#FFD900] font-semibold">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $order->status === 'delivered' ? 'bg-green-500' : 
                                   ($order->status === 'shipped' ? 'bg-blue-500' : 
                                   ($order->status === 'processing' ? 'bg-yellow-500' : 
                                   ($order->status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                                text-white">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('orders.confirmation', $order->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<script>
    // Earnings Chart
    const earningsCtx = document.getElementById('earningsChart').getContext('2d');
    new Chart(earningsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyEarnings, 'month')) !!},
            datasets: [{
                label: 'Earnings ($)',
                data: {!! json_encode(array_column($monthlyEarnings, 'earnings')) !!},
                borderColor: '#FFD900',
                backgroundColor: 'rgba(255, 217, 0, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#FFD900',
                pointBorderColor: '#000',
                pointBorderWidth: 2,
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#9CA3AF',
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: '#282828'
                    }
                },
                x: {
                    ticks: {
                        color: '#9CA3AF'
                    },
                    grid: {
                        color: '#282828'
                    }
                }
            }
        }
    });

    // Booking Status Chart
    const statusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $bookingStatusData['pending'] }},
                    {{ $bookingStatusData['confirmed'] }},
                    {{ $bookingStatusData['completed'] }},
                    {{ $bookingStatusData['cancelled'] }}
                ],
                backgroundColor: [
                    '#FBBF24', // Yellow for pending
                    '#10B981', // Green for confirmed
                    '#3B82F6', // Blue for completed
                    '#EF4444'  // Red for cancelled
                ],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#9CA3AF',
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
