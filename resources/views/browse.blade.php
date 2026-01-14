@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden bg-[#161616]">
    <!-- Inner Banner -->
    <section class="relative py-28 flex items-center bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="Browse Background" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center">
            <h1 class="text-[40px] md:text-[64px] font-bold text-white mb-4" data-aos="fade-down">
                Browse <span class="text-[#FFD900]">DJs & MCs</span>
            </h1>
            <p class="text-white text-[18px] md:text-[20px] max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="100">
                Discover the best entertainment talent across the United States.
            </p>
        </div>
    </section>

    <!-- Talent Grid -->
    <section class="py-20">
        <div class="container mx-auto px-6 lg:px-16">
            
            <!-- Results Count -->
            @if(isset($djs) && $djs->total() > 0)
            <div class="mb-8">
                <p class="text-gray-400">
                    Showing {{ $djs->firstItem() }} - {{ $djs->lastItem() }} of {{ $djs->total() }} DJs
                </p>
            </div>
            @endif

            <!-- Grid (3 columns on desktop, 1 on mobile) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-20">
                @forelse($djs as $index => $dj)
                <div class="talent-card group" data-aos="fade-up" data-aos-delay="{{ ($index % 6) * 100 }}">
                    <div class="talent-card-img-wrapper">
                        @php
                            $imageIndex = ($dj->id % 3) + 1;
                            $defaultImage = 'images/talent-img-00' . $imageIndex . '.png';
                        @endphp
                        <img src="{{ $dj->profile_image ? asset('storage/' . $dj->profile_image) : asset($defaultImage) }}" alt="{{ $dj->stage_name }}" class="talent-card-img">
                    </div>
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="talent-title-h3">{{ $dj->stage_name }}</h3>
                        <div class="location-text">
                            <img src="{{ asset('images/location-icon.png') }}" class="w-4 h-4 mr-1" alt="Location">
                            {{ $dj->city }}, {{ $dj->state }}
                        </div>
                    </div>
                    <p class="professional">
                        @if($dj->genres && count($dj->genres) > 0)
                            {{ implode(', ', array_slice($dj->genres, 0, 2)) }} DJ
                        @else
                            Professional DJ
                        @endif
                    </p>
                    
                    <div class="mb-4">
                        <p class="specialty-label">Specializes in:</p>
                        <div class="flex flex-wrap">
                            @if($dj->specialties && count($dj->specialties) > 0)
                                @foreach(array_slice($dj->specialties, 0, 3) as $specialty)
                                    <span class="specialty-badge">{{ $specialty }}</span>
                                @endforeach
                            @else
                                <span class="specialty-badge">Various Events</span>
                            @endif
                        </div>
                    </div>

                    <p class="experience-label">Experience: {{ $dj->experience_years ?? 0 }}+ Years</p>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div>
                            <p class="talent-meta-label">Pricing</p>
                            <p class="talent-meta-value">
                                @auth
                                    ${{ number_format($dj->hourly_rate ?? 0) }}/hr
                                @else
                                    login to view
                                @endauth
                            </p>
                        </div>
                        <div>
                            <p class="talent-meta-label">Availability</p>
                            <p class="talent-meta-value">
                                @auth
                                    {{ $dj->is_available ? 'Available' : 'Unavailable' }}
                                @else
                                    login to view
                                @endauth
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('dj.show', $dj->id) }}" class="btn primary-button text-center">View Profile</a>
                        @auth
                            <a href="{{ route('bookings.create', ['dj_id' => $dj->id]) }}" class="btn secondary-button text-center flex items-center justify-center">
                                Book Now
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn secondary-button text-center flex items-center justify-center">
                                <img src="{{ asset('images/login-icon.png') }}" class="w-3 h-3 mr-2" alt="Login">
                                Login to Book
                            </a>
                        @endauth
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-white text-lg mb-4">No DJs available at the moment.</p>
                    <a href="{{ route('home') }}" class="btn primary-button">Go Back Home</a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(isset($djs) && $djs->hasPages())
            <div class="flex justify-center items-center flex-wrap gap-3" data-aos="fade-up">
                {{-- Previous Page Link --}}
                @if ($djs->onFirstPage())
                    <span class="px-6 py-3 bg-[#1F1F1F] border border-[#282828] text-gray-500 cursor-not-allowed font-bold rounded">Previous</span>
                @else
                    <a href="{{ $djs->previousPageUrl() }}" class="px-6 py-3 bg-[#1F1F1F] border border-[#282828] text-white hover:bg-[#FFD900] hover:text-black transition-all font-bold rounded">Previous</a>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $djs->currentPage();
                    $lastPage = $djs->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                @endphp

                {{-- First page if not in range --}}
                @if($startPage > 1)
                    <a href="{{ $djs->url(1) }}" class="w-12 h-12 flex items-center justify-center bg-[#1F1F1F] border border-[#282828] text-white hover:bg-[#FFD900] hover:text-black transition-all font-bold rounded">1</a>
                    @if($startPage > 2)
                        <span class="text-gray-500">...</span>
                    @endif
                @endif

                {{-- Page numbers around current page --}}
                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page == $currentPage)
                        <span class="w-12 h-12 flex items-center justify-center bg-[#FFD900] text-black font-bold rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $djs->url($page) }}" class="w-12 h-12 flex items-center justify-center bg-[#1F1F1F] border border-[#282828] text-white hover:bg-[#FFD900] hover:text-black transition-all font-bold rounded">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Last page if not in range --}}
                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                        <span class="text-gray-500">...</span>
                    @endif
                    <a href="{{ $djs->url($lastPage) }}" class="w-12 h-12 flex items-center justify-center bg-[#1F1F1F] border border-[#282828] text-white hover:bg-[#FFD900] hover:text-black transition-all font-bold rounded">{{ $lastPage }}</a>
                @endif

                {{-- Next Page Link --}}
                @if ($djs->hasMorePages())
                    <a href="{{ $djs->nextPageUrl() }}" class="px-6 py-3 bg-[#1F1F1F] border border-[#282828] text-white hover:bg-[#FFD900] hover:text-black transition-all font-bold rounded">Next</a>
                @else
                    <span class="px-6 py-3 bg-[#1F1F1F] border border-[#282828] text-gray-500 cursor-not-allowed font-bold rounded">Next</span>
                @endif
            </div>
            @endif

        </div>
    </section>
</main>
@endsection
