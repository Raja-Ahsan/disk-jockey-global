@extends('layouts.admin.master')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your platform statistics and analytics')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-400 text-sm mb-1">Total Users</h3>
            <p class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</p>
            <p class="text-xs text-gray-500 mt-2">All registered users</p>
        </div>

        <!-- Total DJs -->
        <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-400 text-sm mb-1">Total DJs</h3>
            <p class="text-3xl font-bold text-white">{{ $stats['total_djs'] }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $stats['verified_djs'] }} verified</p>
        </div>

        <!-- Total Bookings -->
        <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-400 text-sm mb-1">Total Bookings</h3>
            <p class="text-3xl font-bold text-white">{{ $stats['total_bookings'] }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $stats['pending_bookings'] }} pending</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6 hover:border-[#FFD900] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFD900]/20">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-[#FFD900]/20 rounded-lg">
                    <svg class="w-8 h-8 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-gray-400 text-sm mb-1">Total Revenue</h3>
            <p class="text-3xl font-bold text-[#FFD900]">${{ number_format($stats['total_revenue'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-2">
                @if($stats['this_month_revenue'] > $stats['last_month_revenue'])
                    <span class="text-green-400">↑</span>
                @else
                    <span class="text-red-400">↓</span>
                @endif
                This month: ${{ number_format($stats['this_month_revenue'], 2) }}
            </p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Monthly Revenue</h2>
            <canvas id="revenueChart" height="100"></canvas>
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
            <h3 class="text-gray-400 text-sm mb-2">Completed Bookings</h3>
            <p class="text-3xl font-bold text-blue-500">{{ $stats['completed_bookings'] }}</p>
        </div>
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h3 class="text-gray-400 text-sm mb-2">Cancelled Bookings</h3>
            <p class="text-3xl font-bold text-red-500">{{ $stats['cancelled_bookings'] }}</p>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white">Recent Bookings</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-[#FFD900] hover:underline text-sm">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b border-[#353535]">
                        <tr>
                            <th class="pb-3 text-gray-400 font-normal text-sm">Client</th>
                            <th class="pb-3 text-gray-400 font-normal text-sm">DJ</th>
                            <th class="pb-3 text-gray-400 font-normal text-sm">Amount</th>
                            <th class="pb-3 text-gray-400 font-normal text-sm">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings->take(5) as $booking)
                        <tr class="border-b border-[#353535] hover:bg-[#282828] transition-colors">
                            <td class="py-3 text-white text-sm">{{ $booking->user->name }}</td>
                            <td class="py-3 text-white text-sm">{{ $booking->dj->stage_name ?? 'N/A' }}</td>
                            <td class="py-3 text-[#FFD900] font-semibold text-sm">${{ number_format($booking->total_amount, 2) }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                                       ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                                       ($booking->booking_status === 'completed' ? 'bg-blue-500' : 'bg-gray-500')) }} 
                                    text-white">
                                    {{ ucfirst($booking->booking_status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">No bookings yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing DJs -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white">Top Performing DJs</h2>
                <a href="{{ route('admin.djs.index') }}" class="text-[#FFD900] hover:underline text-sm">View All →</a>
            </div>
            <div class="space-y-4">
                @forelse($topDjs as $dj)
                <div class="flex items-center justify-between p-3 bg-[#282828] rounded-lg hover:bg-[#353535] transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#FFD900] flex items-center justify-center text-[#333333] font-bold">
                            {{ strtoupper(substr($dj->stage_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $dj->stage_name }}</p>
                            <p class="text-gray-400 text-xs">{{ $dj->total_bookings }} bookings</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[#FFD900] font-bold">${{ number_format($dj->hourly_rate, 0) }}/hr</p>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="text-white text-sm">{{ number_format($dj->rating, 1) }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-center py-8">No DJs yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent DJs -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">Recent DJ Registrations</h2>
            <a href="{{ route('admin.djs.index') }}" class="text-[#FFD900] hover:underline text-sm">View All →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentDjs as $dj)
            <div class="bg-[#282828] p-4 rounded-lg hover:bg-[#353535] transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white font-bold">{{ $dj->stage_name }}</h3>
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $dj->is_verified ? 'bg-green-500' : 'bg-yellow-500' }} text-white">
                        {{ $dj->is_verified ? 'Verified' : 'Pending' }}
                    </span>
                </div>
                <p class="text-gray-400 text-sm mb-2">{{ $dj->user->email }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-xs">{{ $dj->city }}, {{ $dj->state }}</span>
                    <a href="{{ route('admin.djs.show', $dj->id) }}" class="text-[#FFD900] text-sm hover:underline">View →</a>
                </div>
            </div>
            @empty
            <p class="text-gray-400 col-span-3 text-center py-8">No DJs yet</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
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
