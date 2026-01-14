@extends('layouts.admin.master')

@section('title', 'Manage Events')
@section('page-title', 'Manage Events')
@section('page-description', 'View and manage all events')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-col md:flex-row gap-4">
            <select name="status" class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <button type="submit" class="btn primary-button whitespace-nowrap">Filter</button>
            
            @if(request()->has('status'))
                <a href="{{ route('admin.events.index') }}" class="btn secondary-button whitespace-nowrap">Clear</a>
            @endif
        </form>
    </div>

    <!-- Events Table -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#282828]">
            <h2 class="text-xl font-bold text-white">All Events ({{ $events->total() }})</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Event</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Organizer</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Location</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Date & Time</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-white font-semibold">{{ $event->title }}</p>
                            @if($event->description)
                                <p class="text-gray-400 text-sm mt-1">{{ Str::limit($event->description, 50) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-white font-semibold">{{ $event->user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $event->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-white text-sm">{{ $event->location }}</p>
                            @if($event->venue_name)
                                <p class="text-gray-400 text-xs">{{ $event->venue_name }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-white text-sm">{{ $event->event_date->format('M d, Y') }}</p>
                            <p class="text-gray-400 text-xs">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - 
                                {{ $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : 'N/A' }}
                            </p>
                            @if($event->event_type)
                                <span class="inline-block mt-1 px-2 py-1 bg-[#282828] text-[#FFD900] text-xs rounded">{{ $event->event_type }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $event->status === 'confirmed' ? 'bg-green-500' : 
                                   ($event->status === 'pending' ? 'bg-yellow-500' : 
                                   ($event->status === 'completed' ? 'bg-blue-500' : 
                                   ($event->status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                                text-white">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.events.show', $event->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">No events found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
        <div class="p-6 border-t border-[#282828]">
            {{ $events->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
