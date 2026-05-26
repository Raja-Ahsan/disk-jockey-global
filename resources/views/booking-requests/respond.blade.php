@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-20 px-6 flex items-center justify-center">
    <div class="container mx-auto max-w-lg text-center">
        <div class="bg-[#1F1F1F] border border-[#282828] p-10 rounded-xl">
            @if($success ?? false)
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-green-500/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            @else
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-red-500/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            @endif
            <h1 class="text-2xl font-bold text-white mb-4">Booking Response</h1>
            <p class="text-gray-300">{{ $message }}</p>
            <a href="{{ url('/') }}" class="btn primary-button inline-block mt-8">Go to Homepage</a>
        </div>
    </div>
</main>
@endsection
