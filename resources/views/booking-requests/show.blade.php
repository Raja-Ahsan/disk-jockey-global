@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-20 px-6">
    <div class="container mx-auto max-w-2xl text-center">
        <h1 class="text-3xl font-bold text-white mb-4">Booking Request Sent</h1>
        @if(session('success'))
            <p class="text-[#FFD900] mb-6">{{ session('success') }}</p>
        @endif
        <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-xl text-left space-y-3 mb-8">
            <p class="text-white"><strong>DJ/MC:</strong> {{ $bookingRequest->dj->stage_name }}</p>
            <p class="text-white"><strong>Event Date:</strong> {{ $bookingRequest->event_date->format('M d, Y') }}</p>
            <p class="text-white"><strong>Status:</strong> <span class="text-[#FFD900]">{{ ucfirst($bookingRequest->status) }}</span></p>
            <p class="text-gray-400 text-sm">Expires: {{ $bookingRequest->expires_at->format('M d, Y g:i A') }}</p>
            <p class="text-gray-400 text-sm">You will receive email and text when the DJ responds. Payment is only required if they accept.</p>
        </div>
        <a href="{{ route('browse') }}" class="btn primary-button">Browse More DJs</a>
    </div>
</main>
@endsection
