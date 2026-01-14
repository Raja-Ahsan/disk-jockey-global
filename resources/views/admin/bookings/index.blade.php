@extends('layouts.admin.master')

@section('title', 'Manage Bookings')
@section('page-title', 'Manage Bookings')
@section('page-description', 'View and manage all bookings')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-col md:flex-row gap-4">
            <select name="status" class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Booking Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <select name="payment_status" class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Payment Status</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
            
            <button type="submit" class="btn primary-button whitespace-nowrap">Filter</button>
            
            @if(request()->hasAny(['status', 'payment_status']))
                <a href="{{ route('admin.bookings.index') }}" class="btn secondary-button whitespace-nowrap">Clear</a>
            @endif
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#282828]">
            <h2 class="text-xl font-bold text-white">All Bookings ({{ $bookings->total() }})</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">ID</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Client</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">DJ</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Date</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Amount</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Booking Status</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Payment</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-6 py-4 text-gray-400">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-white">{{ $booking->user->name }}</td>
                        <td class="px-6 py-4 text-white">{{ $booking->dj->stage_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $booking->booking_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-[#FFD900] font-bold">${{ number_format($booking->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                                   ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                                   ($booking->booking_status === 'completed' ? 'bg-blue-500' : 
                                   ($booking->booking_status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                                text-white">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                                   ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                                text-white">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">No bookings found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="p-6 border-t border-[#282828]">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
