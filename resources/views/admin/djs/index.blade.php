@extends('layouts.admin.master')

@section('title', 'Manage DJs')
@section('page-title', 'Manage DJs')
@section('page-description', 'View and manage all DJ profiles')

@section('content')
<div class="space-y-6">
    <!-- Search and Filters -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <form method="GET" action="{{ route('admin.djs.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search by name, stage name, or email..."
                   class="flex-1 bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
            
            <select name="verified" 
                    class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Verification Status</option>
                <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Not Verified</option>
            </select>
            
            <button type="submit" class="btn primary-button whitespace-nowrap">
                Search
            </button>
            
            @if(request()->hasAny(['search', 'verified']))
                <a href="{{ route('admin.djs.index') }}" class="btn secondary-button whitespace-nowrap">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- DJs Table -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#282828]">
            <h2 class="text-xl font-bold text-white">All DJs ({{ $djs->total() }})</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">DJ</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Location</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Rate</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Rating</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Bookings</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($djs as $dj)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ $dj->profile_image ? asset('storage/' . $dj->profile_image) : asset('images/talent-img-00' . (($dj->id % 3) + 1) . '.png') }}" 
                                     alt="{{ $dj->stage_name }}" 
                                     class="w-12 h-12 rounded-lg object-cover">
                                <div>
                                    <p class="text-white font-semibold">{{ $dj->stage_name }}</p>
                                    <p class="text-gray-400 text-sm">{{ $dj->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-white">{{ $dj->city }}, {{ $dj->state }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[#FFD900] font-bold">${{ number_format($dj->hourly_rate, 0) }}/hr</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="text-white">{{ number_format($dj->rating, 1) }}</span>
                                <span class="text-gray-400 text-sm">({{ $dj->total_reviews }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-white">{{ $dj->total_bookings }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $dj->is_verified ? 'bg-green-500' : 'bg-yellow-500' }} text-white w-fit">
                                    {{ $dj->is_verified ? 'Verified' : 'Pending' }}
                                </span>
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $dj->is_available ? 'bg-blue-500' : 'bg-gray-500' }} text-white w-fit">
                                    {{ $dj->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.djs.show', $dj->id) }}" 
                                   class="text-[#FFD900] hover:underline text-sm">View</a>
                                <a href="{{ route('admin.djs.edit', $dj->id) }}" 
                                   class="text-blue-400 hover:underline text-sm">Edit</a>
                                @if(!$dj->is_verified)
                                    <form action="{{ route('admin.djs.verify', $dj->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-400 hover:underline text-sm">Verify</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.djs.unverify', $dj->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-400 hover:underline text-sm">Unverify</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.djs.destroy', $dj->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this DJ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            No DJs found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($djs->hasPages())
        <div class="p-6 border-t border-[#282828]">
            {{ $djs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
