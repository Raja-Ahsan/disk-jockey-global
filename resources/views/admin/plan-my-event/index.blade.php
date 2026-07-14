@extends('layouts.admin.master')

@section('title', 'Plan My Event')
@section('page-title', 'Plan My Event')
@section('page-description', 'View and manage Plan My Event requests')

@section('content')
<div class="space-y-6">
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <form method="GET" action="{{ route('admin.plan-my-event.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search client, city, or email..."
                   class="flex-1 bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">

            <select name="status" class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Status</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <button type="submit" class="btn primary-button whitespace-nowrap">Filter</button>

            @if(request()->hasAny(['status', 'search']))
                <a href="{{ route('admin.plan-my-event.index') }}" class="btn secondary-button whitespace-nowrap">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#282828]">
            <h2 class="text-xl font-bold text-white">All Requests ({{ $requests->total() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">ID</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Client</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">City</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Event Date</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Event Type</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Budget</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Submitted</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $item)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-6 py-4 text-gray-400">#{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <p class="text-white">{{ $item->client_name }}</p>
                            @if($item->email)
                                <p class="text-gray-500 text-xs">{{ $item->email }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $item->city }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $item->event_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-white">{{ $item->event_type }}</td>
                        <td class="px-6 py-4 text-[#FFD900] font-bold">
                            @if($item->budget_min || $item->budget_max)
                                ${{ number_format($item->budget_min, 0) }} – ${{ number_format($item->budget_max, 0) }}
                            @else
                                {{ $item->budget_range ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold
                                {{ $item->status === 'new' ? 'bg-yellow-500' :
                                   ($item->status === 'in_progress' ? 'bg-blue-500' :
                                   ($item->status === 'contacted' ? 'bg-green-500' :
                                   ($item->status === 'closed' ? 'bg-gray-500' : 'bg-red-500'))) }}
                                text-white">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $item->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.plan-my-event.show', $item->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">No Plan My Event requests found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="p-6 border-t border-[#282828]">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
