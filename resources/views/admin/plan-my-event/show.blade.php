@extends('layouts.admin.master')

@section('title', 'Plan My Event Details')
@section('page-title', 'Plan My Event Details')
@section('page-description', 'View and manage Plan My Event request')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-[#1F1F1F] to-[#282828] border border-[#353535] rounded-xl p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Request #{{ str_pad($planMyEventRequest->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded text-sm font-bold
                        {{ $planMyEventRequest->status === 'new' ? 'bg-yellow-500' :
                           ($planMyEventRequest->status === 'in_progress' ? 'bg-blue-500' :
                           ($planMyEventRequest->status === 'contacted' ? 'bg-green-500' :
                           ($planMyEventRequest->status === 'closed' ? 'bg-gray-500' : 'bg-red-500'))) }}
                        text-white">
                        {{ strtoupper(str_replace('_', ' ', $planMyEventRequest->status)) }}
                    </span>
                    <span class="text-gray-400 text-sm">Submitted {{ $planMyEventRequest->created_at->format('M d, Y g:i A') }}</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-sm">Budget</p>
                <p class="text-[#FFD900] text-2xl font-bold">
                    @if($planMyEventRequest->budget_min || $planMyEventRequest->budget_max)
                        ${{ number_format($planMyEventRequest->budget_min, 0) }} – ${{ number_format($planMyEventRequest->budget_max, 0) }}
                    @else
                        {{ $planMyEventRequest->budget_range ?? 'N/A' }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Update Request</h2>
        <form action="{{ route('admin.plan-my-event.update', $planMyEventRequest->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white font-semibold mb-2">Status</label>
                    <select name="status" class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        <option value="new" {{ $planMyEventRequest->status === 'new' ? 'selected' : '' }}>New</option>
                        <option value="in_progress" {{ $planMyEventRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="contacted" {{ $planMyEventRequest->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="closed" {{ $planMyEventRequest->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="cancelled" {{ $planMyEventRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">Admin Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none"
                              placeholder="Internal notes...">{{ old('notes', $planMyEventRequest->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="btn primary-button">Update Request</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Client Information</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Name</p>
                    <p class="text-white font-semibold">{{ $planMyEventRequest->client_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="text-white">{{ $planMyEventRequest->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Phone</p>
                    <p class="text-white">{{ $planMyEventRequest->phone ?? 'N/A' }}</p>
                </div>
                @if($planMyEventRequest->user)
                <div>
                    <p class="text-gray-400 text-sm">Account</p>
                    <p class="text-white">{{ $planMyEventRequest->user->name }} (ID #{{ $planMyEventRequest->user_id }})</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Event Details</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Date</p>
                    <p class="text-white font-semibold">{{ $planMyEventRequest->event_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Event Type</p>
                    <p class="text-white">{{ $planMyEventRequest->event_type }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">City / Zip</p>
                    <p class="text-white">{{ $planMyEventRequest->city }}, {{ $planMyEventRequest->zipcode }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Preferred DJ / MC</p>
                    <p class="text-white">{{ $planMyEventRequest->dj_name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Venue</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-400 text-sm">Name</p>
                    <p class="text-white font-semibold">{{ $planMyEventRequest->venue_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Type</p>
                    <p class="text-white">{{ $planMyEventRequest->venue_type_label }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Address</p>
                    <p class="text-white">{{ $planMyEventRequest->venue_address }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Preferences</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <p class="text-gray-400">Budget Range</p>
                    <p class="text-white font-bold">{{ $planMyEventRequest->budget_range ?? 'N/A' }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-gray-400">Near Me</p>
                    <p class="text-white">{{ $planMyEventRequest->use_near_me ? 'Yes' : 'No' }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-gray-400">Rush Guarantee</p>
                    <p class="text-white">{{ $planMyEventRequest->rush_guarantee ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($planMyEventRequest->special_requests)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Special Requests</h2>
        <p class="text-white">{{ $planMyEventRequest->special_requests }}</p>
    </div>
    @endif

    @if($planMyEventRequest->notes)
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Admin Notes</h2>
        <p class="text-white whitespace-pre-wrap">{{ $planMyEventRequest->notes }}</p>
    </div>
    @endif

    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <h2 class="text-xl font-bold text-white mb-4">Danger Zone</h2>
        <form action="{{ route('admin.plan-my-event.destroy', $planMyEventRequest->id) }}" method="POST"
              onsubmit="return confirm('Delete this Plan My Event request permanently?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn secondary-button text-red-400 border-red-500/40 hover:bg-red-500/10">Delete Request</button>
        </form>
    </div>
</div>
@endsection
