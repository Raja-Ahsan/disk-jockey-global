@extends('layouts.web.master')

@section('content')
   <main class="overflow-hidden">
      <!-- Hero Section -->
<section class="relative min-h-[90vh] flex items-center bg-[#000000] overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero-bg-image.png') }}" alt="Hero Background" class="w-full h-full object-cover opacity-60">
        <!-- <div class="absolute inset-0 bg-linear-to-r from-black via-black/40 to-transparent"></div> -->
    </div>

    <div class="container mx-auto px-6 lg:px-16 relative z-10 py-20">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-16">
            
            <!-- Left Content Area -->
            <div class="w-full lg:w-1/2 text-left">
                <span class="book-professional" data-aos="fade-up">
                    Book Professional
                </span>
                
                <h1 class="djs-mcs" data-aos="fade-up" data-aos-delay="100">
                    DJs & MCs
                </h1>
                
                <h2 class="text-[20px] md:text-[40px] font-extralight text-white mb-6 leading-tight" data-aos="fade-up" data-aos-delay="200">
                    Anytime, Anywhere
                </h2>
                
                <p class="text-[#777777] font-normal text-[20px] mb-10 max-w-lg leading-relaxed" data-aos="fade-up" data-aos-delay="300">
                    DJ Global connects verified DJs with events in America.
                </p>
                
                <div data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('browse') }}" class="btn primary-button inline-block text-center ">
                        Book a DJ or MC
                    </a>
                </div>
            </div>

            <!-- Right Content Area (Form Card) -->
            <div class="w-full lg:w-[640px]" data-aos="fade-left" data-aos-delay="200">
                <div class="right-card">

                    <form action="{{ route('search') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Genre Dropdown -->
                            <div class="relative group">
                                <select name="genre" class="hero-dropdown">
                                    <option value="" disabled selected class="text-white">Genre</option>
                                    <option value="Hip Hop" class="text-white">Hip Hop</option>
                                    <option value="EDM" class="text-white">EDM</option>
                                    <option value="Rock" class="text-white">Rock</option>
                                    <option value="Pop" class="text-white">Pop</option>
                                    <option value="R&B" class="text-white">R&B</option>
                                    <option value="Country" class="text-white">Country</option>
                                </select>
                                <div class="hero-dropdown-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            
                            <!-- Price Range Dropdown -->
                            <div class="relative group">
                                <select name="price_range" class="hero-dropdown">
                                    <option value="" disabled selected class="text-white">Price Range</option>
                                    <option value="100-500" class="text-white">$100 - $500</option>
                                    <option value="500-1000" class="text-white">$500 - $1000</option>
                                    <option value="1000-2000" class="text-white">$1000 - $2000</option>
                                    <option value="2000+" class="text-white">$2000+</option>
                                </select>
                                <div class="hero-dropdown-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- City Input -->
                            <div class="relative group">
                                <input type="text" name="city" placeholder="City" class="w-full bg-[#353535] text-white p-5 focus:outline-none focus:border-[#FFD900] transition-all placeholder:text-[var(--text-white)]">
                                <div class="hero-dropdown-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            
                            <!-- State Input -->
                            <div class="relative group">
                                <input type="text" name="state" placeholder="State" class="w-full bg-[#353535] text-white p-5 focus:outline-none focus:border-[#FFD900] transition-all placeholder:text-[var(--text-white)]">
                                <div class="hero-dropdown-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Zipcode Input -->
                        <div class="relative group">
                            <input type="text" name="zipcode" placeholder="Zipcode" class="w-full bg-[#353535] text-white p-5  focus:outline-none focus:border-[#FFD900] transition-all placeholder:text-[var(--text-white)]">
                        </div>

                        <button type="submit" class="w-full btn primary-button py-5!  uppercase tracking-wider">
                            Book DJ / MC
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- talent-section -->
<section class="py-20 bg-[#161616]">
    <div class="container mx-auto px-6 lg:px-16">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6" data-aos="fade-up">
            <div>
                <span class="talent-label">DJ's / MC's</span>
                <h2 class="talent-title mb-2!">Top <span class="text-[#FFD900]">Talents</span> Nearby</h2>
                <p class="text-[var(--text-white)] text-[18px] font-normal">Browse verified entertainment professionals across the United States</p>
            </div>
            <a href="{{ route('browse') }}" class="btn primary-button py-3! px-8! ">View All</a>
        </div>

        <!-- Talents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($djs as $index => $dj)
            <div class="talent-card group" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
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
                        @if($dj->specialties)
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
                <p class="text-white text-lg">No DJs available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- how it work -->
<section class="">

    <div class="container mx-auto px-6 lg:px-16">
        
        <span class="work-label" data-aos="fade-down">How It Works</span>
        <h2 class="work-title" data-aos="zoom-in" data-aos-delay="100">
            Simple. <span class="font-bold">Transparent.</span> <span class="text-[#FFD900] font-bold">Efficient.</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-16">
            
            <!-- Step 1 -->
            <div class="group" data-aos="fade-right" data-aos-delay="200">
                <div class="work-img-wrapper">
                    <img src="{{ asset('images/work-img-1.png') }}" alt="Browse verified DJs / MCs" class="work-img">
                </div>
                <div class="step-badge">Step #1</div>
                <p class="step-text">Browse verified DJs / MCs</p>
            </div>

            <!-- Step 2 -->
            <div class="group" data-aos="zoom-in" data-aos-delay="300">
                <div class="work-img-wrapper">
                    <img src="{{ asset('images/work-img-2.png') }}" alt="View profiles, pricing & availability" class="work-img">
                </div>
                <div class="step-badge-gray">Step #2</div>
                <p class="step-text">View profiles, pricing & availability</p>
            </div>

            <!-- Step 3 -->
            <div class="group" data-aos="fade-left" data-aos-delay="400">
                <div class="work-img-wrapper">
                    <img src="{{ asset('images/work-img-3.png') }}" alt="Book and pay securely through the platform" class="work-img">
                </div>
                <div class="step-badge-gray">Step #3</div>
                <p class="step-text">Book and pay securely through the platform</p>
            </div>

        </div>

        <div class="flex justify-center" data-aos="fade-up" data-aos-delay="500">
            <a href="#" class="btn primary-button ">Start Booking</a>
        </div>

    </div>
</section>

<!-- disk jocky -->
<section class="dj-section overflow-visible">
    <div class="container mx-auto px-6 lg:px-16 h-full">
        <div class="flex flex-col lg:flex-row items-center justify-between h-full">
            
            <!-- Left Side: Images -->
            <div class="w-full lg:w-[45%] order-2 lg:order-1 self-end" data-aos="fade-right" data-aos-duration="1200">
                <div class="dj-img-container">
                    <img src="{{ asset('images/dj-jockey-gif.gif') }}" alt="Effect Overlay" class="dj-gif-overlay">
                    <img src="{{ asset('images/dj-image.png') }}" alt="Professional DJ" class="dj-main-img">
                </div>
            </div>

            <!-- Right Side: Content -->
            <div class="w-full lg:w-1/2 order-1 lg:order-2 py-12 lg:py-0">
                <div class="text-left">
                    <span class="dj-label" data-aos="fade-left">For DJs / MCs</span>
                    <h2 class="dj-title" data-aos="fade-left" data-aos-delay="100">
                        Grow Your Career <span class="font-bold">With</span><br>
                        <span class="text-(--primary-color) font-bold">Disk Jockey Global</span>
                    </h2>
                    <p class="dj-desc" data-aos="fade-left" data-aos-delay="200">
                        We empower DJs and MCs with a space to showcase talent, manage bookings, and connect with clients — without social media noise.
                    </p>
                    <div data-aos="fade-left" data-aos-delay="300">
                        <a href="#" class="btn primary-button inline-block text-center">
                            Become a DJ / MC
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


 
<!-- Gallery  section-->
<section class="py-20 bg-[#161616]">
    <div class="container mx-auto">
        
        <span class="gallery-label" data-aos="fade-down">Gallery</span>
        <h2 class="gallery-title" data-aos="zoom-in" data-aos-delay="100">
            <span class="text-[var(--primary-color)] font-bold text-[30px] md:text-[48px]">Moments</span><br>
            Powered <span class="font-bold ">by Music</span>
        </h2>

        <!-- Gallery Grid -->
        <div class="gallery-grid">
            
            <!-- Item 1 -->
            <div class="gallery-item tilt-left-1" data-aos="fade-right" data-aos-delay="200">
                <img src="{{ asset('images/galerry-img-001.png') }}" alt="Gallery Image 1" class="gallery-img">
            </div>

            <!-- Item 2 -->
            <div class="gallery-item tilt-right-1" data-aos="fade-right" data-aos-delay="300">
                <img src="{{ asset('images/galerry-img-002.png') }}" alt="Gallery Image 2" class="gallery-img">
            </div>

            <!-- Item 3 -->
            <div class="gallery-item tilt-left-2" data-aos="fade-up" data-aos-delay="400">
                <img src="{{ asset('images/galerry-img-003.png') }}" alt="Gallery Image 3" class="gallery-img">
            </div>

            <!-- Item 4 -->
            <div class="gallery-item tilt-right-2" data-aos="fade-left" data-aos-delay="500">
                <img src="{{ asset('images/galerry-img-004.png') }}" alt="Gallery Image 4" class="gallery-img">
            </div>

            <!-- Item 5 -->
            <div class="gallery-item tilt-left-3" data-aos="fade-left" data-aos-delay="600">
                <img src="{{ asset('images/galerry-img-005.png') }}" alt="Gallery Image 5" class="gallery-img">
            </div>

        </div>

        <div class="flex justify-center" data-aos="fade-up" data-aos-delay="700">
            <a href="#" class="btn primary-button">View Gallery</a>
        </div>

    </div>
</section>

<!-- Merch section -->
<section class="py-20 bg-[#161616]">
    <div class="container mx-auto px-6 lg:px-16">
        
        <span class="merch-label" data-aos="fade-down">Merch</span>
        <h2 class="merch-title text-[30px] md:text-[48px]" data-aos="zoom-in" data-aos-delay="100">
            Shop Exclusive <span class="font-bold">DJ &</span><br>
            <span class="font-bold">MC</span> <span class="text-[var(--primary-color)] font-bold">Merchandise</span>
        </h2>
        <p class="merch-desc" data-aos="fade-up" data-aos-delay="200">Our most-loved designs that believers can't stop wearing.</p>

        <!-- Products Grid -->
        @php
            $merchProducts = \App\Models\Product::active()
                ->with('variations', 'productCategory')
                ->get()
                ->filter(function($product) {
                    return $product->has_stock;
                })
                ->sortByDesc('created_at')
                ->take(4);
        @endphp
        
        @if($merchProducts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            @foreach($merchProducts as $index => $product)
            <div class="product-card group" data-aos="fade-up" data-aos-delay="{{ 300 + ($index * 100) }}">
                <a href="{{ route('products.show', $product->id) }}">
                    <div class="product-img-wrapper relative">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                        @if($product->sale_price || ($product->isVariable() && $product->variations->where('sale_price', '!=', null)->count() > 0))
                            <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">Sale</span>
                        @endif
                        @if($product->isVariable())
                            <span class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold">Variable</span>
                        @endif
                    </div>
                    <span class="product-category">{{ $product->productCategory->full_name ?? 'General' }}</span>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <div class="flex items-center gap-2">
                        @if($product->isVariable())
                            <p class="product-price text-[#FFD900]">From ${{ number_format($product->min_price, 2) }}</p>
                        @else
                            @if($product->sale_price)
                                <p class="product-price text-[#FFD900]">${{ number_format($product->sale_price, 2) }}</p>
                                <p class="text-gray-500 line-through text-sm">${{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="product-price">${{ number_format($product->price, 2) }}</p>
                            @endif
                        @endif
                    </div>
                    @php
                        $hasStock = $product->has_stock;
                        $totalStock = $product->total_stock;
                    @endphp
                    @if($hasStock)
                        @if($totalStock <= 5)
                            <p class="text-yellow-400 text-xs mt-1">Only {{ $totalStock }} left!</p>
                        @else
                            <p class="text-green-400 text-xs mt-1">In Stock</p>
                        @endif
                    @else
                        <p class="text-red-400 text-xs mt-1">Out of Stock</p>
                    @endif
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-400">No products available yet. Check back soon!</p>
        </div>
        @endif

        <div class="flex justify-center" data-aos="zoom-in" data-aos-delay="700">
            <a href="{{ route('merch') }}" class="btn primary-button">View Full Collection</a>
        </div>

    </div>
</section>

   </main>
@endsection