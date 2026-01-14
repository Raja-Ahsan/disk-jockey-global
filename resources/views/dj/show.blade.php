@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden">
    <!-- DJ Profile Header -->
    <section class="relative py-20 bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            @php
                $imageIndex = ($dj->id % 3) + 1;
                $defaultImage = 'images/talent-img-00' . $imageIndex . '.png';
            @endphp
            <img src="{{ $dj->profile_image ? asset('storage/' . $dj->profile_image) : asset($defaultImage) }}" alt="{{ $dj->stage_name }}" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-8">
                <div class="w-full lg:w-1/3">
                    @php
                        $imageIndex = ($dj->id % 3) + 1;
                        $defaultImage = 'images/talent-img-00' . $imageIndex . '.png';
                    @endphp
                    <img src="{{ $dj->profile_image ? asset('storage/' . $dj->profile_image) : asset($defaultImage) }}" alt="{{ $dj->stage_name }}" class="w-full max-w-md rounded-lg shadow-2xl">
                </div>
                <div class="w-full lg:w-2/3 text-white">
                    <h1 class="text-4xl md:text-6xl font-bold text-[#FFD900] mb-4">{{ $dj->stage_name }}</h1>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center">
                            <img src="{{ asset('images/location-icon.png') }}" class="w-5 h-5 mr-2" alt="Location">
                            <span class="text-lg">{{ $dj->city }}, {{ $dj->state }}</span>
                        </div>
                        @if($dj->rating > 0)
                        <div class="flex items-center">
                            <span class="text-[#FFD900] text-lg font-bold">{{ number_format($dj->rating, 1) }}</span>
                            <span class="text-gray-400 ml-1">({{ $dj->total_reviews }} reviews)</span>
                        </div>
                        @endif
                    </div>
                    <p class="text-gray-300 text-lg mb-6">{{ $dj->bio ?? 'Professional DJ/MC with years of experience.' }}</p>
                    <div class="flex flex-wrap gap-3 mb-6">
                        @if($dj->specialties)
                            @foreach($dj->specialties as $specialty)
                                <span class="bg-[#FFD900] text-[#333333] px-4 py-2 rounded font-semibold">{{ $specialty }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('bookings.create', ['dj_id' => $dj->id]) }}" class="btn primary-button">Book Now</a>
                        @else
                            <a href="{{ route('login') }}" class="btn primary-button">Login to Book</a>
                        @endauth
                        @if($dj->website)
                            <a href="{{ $dj->website }}" target="_blank" class="btn secondary-button">Visit Website</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DJ Details -->
    <section class="py-20 bg-[#161616]">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-[#1C1C1C] p-8 rounded-lg mb-8">
                        <h2 class="text-2xl font-bold text-white mb-4">About</h2>
                        <p class="text-gray-300 leading-relaxed">{{ $dj->bio ?? 'No bio available.' }}</p>
                    </div>

                    @if($dj->genres)
                    <div class="bg-[#1C1C1C] p-8 rounded-lg mb-8">
                        <h2 class="text-2xl font-bold text-white mb-4">Genres</h2>
                        <div class="flex flex-wrap gap-3">
                            @foreach($dj->genres as $genre)
                                <span class="bg-[#282828] text-white px-4 py-2 rounded">{{ $genre }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($dj->equipment)
                    <div class="bg-[#1C1C1C] p-8 rounded-lg mb-8">
                        <h2 class="text-2xl font-bold text-white mb-4">Equipment</h2>
                        <p class="text-gray-300">{{ $dj->equipment }}</p>
                    </div>
                    @endif

                    <!-- Reviews Section -->
                    @if($dj->reviews && $dj->reviews->count() > 0)
                    <div class="bg-[#1C1C1C] p-8 rounded-lg">
                        <h2 class="text-2xl font-bold text-white mb-6">Reviews ({{ $dj->total_reviews }})</h2>
                        <div class="space-y-6">
                            @foreach($dj->reviews->take(5) as $review)
                            <div class="border-b border-[#353535] pb-6 last:border-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h4 class="text-white font-semibold">{{ $review->user->name }}</h4>
                                        <p class="text-gray-400 text-sm">{{ $review->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-{{ $i <= $review->rating ? '#FFD900' : '#353535' }} text-xl">★</span>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-300">{{ $review->comment }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-[#1C1C1C] p-8 rounded-lg sticky top-8">
                        <h3 class="text-xl font-bold text-white mb-6">Booking Information</h3>
                        
                        <div class="space-y-4 mb-6">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Hourly Rate</p>
                                <p class="text-[#FFD900] text-2xl font-bold">${{ number_format($dj->hourly_rate) }}/hr</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Experience</p>
                                <p class="text-white text-lg">{{ $dj->experience_years }}+ Years</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Availability</p>
                                <p class="text-white text-lg">{{ $dj->is_available ? 'Available' : 'Unavailable' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Status</p>
                                <p class="text-white text-lg">{{ $dj->is_verified ? 'Verified' : 'Pending Verification' }}</p>
                            </div>
                        </div>

                        @if($dj->phone)
                        <div class="mb-6">
                            <p class="text-gray-400 text-sm mb-1">Contact</p>
                            <p class="text-white">{{ $dj->phone }}</p>
                        </div>
                        @endif

                        @auth
                            <a href="{{ route('bookings.create', ['dj_id' => $dj->id]) }}" class="btn primary-button w-full text-center block">Book This DJ</a>
                        @else
                            <a href="{{ route('login') }}" class="btn primary-button w-full text-center block">Login to Book</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
