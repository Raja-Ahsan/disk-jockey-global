@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden bg-[#161616]">
    <!-- Inner Banner -->
    <section class="relative py-28 flex items-center bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="Gallery Background" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center">
            <h1 class="text-[40px] md:text-[64px] font-bold text-white mb-4" data-aos="fade-down">
                Our <span class="text-(--primary-color)">Gallery</span>
            </h1>
            <p class="text-(--text-white) text-[18px] md:text-[20px] max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="100">
                A visual journey through the most electrifying events powered by Disk Jockey Global.
            </p>
        </div>
    </section>

    <!-- Gallery Categories / Filter placeholder -->
    <section class="py-12 bg-[#1F1F1F] border-b border-[#282828]">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="flex flex-wrap justify-center gap-4 md:gap-8" data-aos="fade-up">
                <button data-filter="all" class="filter-btn active text-(--primary-color) border-b-2 border-(--primary-color) pb-1 font-bold uppercase tracking-widest text-sm transition-all outline-none">All Moments</button>
                <button data-filter="wedding" class="filter-btn text-(--text-white) hover:text-(--primary-color) transition-all pb-1 font-bold uppercase tracking-widest text-sm outline-none">Weddings</button>
                <button data-filter="corporate" class="filter-btn text-(--text-white) hover:text-(--primary-color) transition-all pb-1 font-bold uppercase tracking-widest text-sm outline-none">Corporate</button>
                <button data-filter="nightlife" class="filter-btn text-(--text-white) hover:text-(--primary-color) transition-all pb-1 font-bold uppercase tracking-widest text-sm outline-none">Nightlife</button>
                <button data-filter="private" class="filter-btn text-(--text-white) hover:text-(--primary-color) transition-all pb-1 font-bold uppercase tracking-widest text-sm outline-none">Private Parties</button>
            </div>
        </div>
    </section>

    <!-- Detailed Gallery Grid -->
    <section class="py-20">
        <div class="container mx-auto px-6 lg:px-16">
            <div id="gallery-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-all duration-500">
                
                <!-- Row 1 -->
                <div class="gallery-card filter-item nightlife relative overflow-hidden group cursor-pointer" data-aos="zoom-in">
                    <img src="{{ asset('images/galerry-img-001.png') }}" alt="Event 1" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Nightlife</span>
                        <h3 class="text-white text-2xl font-bold">Midnight Pulse Festival</h3>
                    </div>
                </div>

                <div class="gallery-card filter-item wedding relative overflow-hidden group cursor-pointer" data-aos="zoom-in" data-aos-delay="100">
                    <img src="{{ asset('images/galerry-img-002.png') }}" alt="Event 2" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Wedding</span>
                        <h3 class="text-white text-2xl font-bold">Ocean Breeze Nuptials</h3>
                    </div>
                </div>

                <div class="gallery-card filter-item corporate relative overflow-hidden group cursor-pointer" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('images/galerry-img-003.png') }}" alt="Event 3" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Corporate</span>
                        <h3 class="text-white text-2xl font-bold">Tech Summit Gala</h3>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="gallery-card filter-item private relative overflow-hidden group cursor-pointer" data-aos="zoom-in" data-aos-delay="300">
                    <img src="{{ asset('images/galerry-img-004.png') }}" alt="Event 4" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Private</span>
                        <h3 class="text-white text-2xl font-bold">Neon Anniversary Bash</h3>
                    </div>
                </div>

                <div class="gallery-card filter-item nightlife corporate relative overflow-hidden group cursor-pointer lg:col-span-2" data-aos="zoom-in" data-aos-delay="400">
                    <img src="{{ asset('images/galerry-img-005.png') }}" alt="Event 5" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Nightlife / Corporate</span>
                        <h3 class="text-white text-3xl font-bold">Soundwave Global 2024</h3>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="gallery-card filter-item nightlife private relative overflow-hidden group cursor-pointer" data-aos="zoom-in" data-aos-delay="100">
                    <img src="{{ asset('images/work-img-2.png') }}" alt="Event 6" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Nightlife</span>
                        <h3 class="text-white text-2xl font-bold">Beat Masters Live</h3>
                    </div>
                </div>

                <div class="gallery-card filter-item wedding relative overflow-hidden group cursor-pointer" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('images/work-img-3.png') }}" alt="Event 7" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Wedding</span>
                        <h3 class="text-white text-2xl font-bold">Rhythm & Lights</h3>
                    </div>
                </div>

                <div class="gallery-card filter-item corporate private relative overflow-hidden group group-hover:grayscale-0 cursor-pointer" data-aos="zoom-in" data-aos-delay="300">
                    <img src="{{ asset('images/work-img-1.png') }}" alt="Event 8" class="w-full h-[400px] object-cover grayscale-50 group-hover:grayscale-0 group-hover:scale-110 transition-all duration-700">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                        <span class="text-(--primary-color) uppercase tracking-widest font-bold text-xs mb-2">Corporate</span>
                        <h3 class="text-white text-2xl font-bold">Deep House Sessions</h3>
                    </div>
                </div>

            </div>

            <!-- Page Load More -->
            <div class="mt-20 text-center" data-aos="fade-up">
                <button class="btn secondary-button px-10! border-white! text-white! hover:bg-white! hover:text-black! transition-all font-bold tracking-widest uppercase py-4!">
                    Load More Moments
                </button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#1F1F1F] text-center">
        <div class="container mx-auto px-6 lg:px-16" data-aos="zoom-in">
            <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-8 leading-tight">
                Want Your Event <span class="text-(--primary-color)">Featured?</span>
            </h2>
            <p class="text-(--text-white) font-normal text-lg mb-10 max-w-2xl mx-auto">
                Book our elite performers and let us bring the rhythm that creates unforgettable memories for your next gallery post.
            </p>
            <a href="{{ url('/browse') }}" class="btn primary-button px-10! py-5!">Book Your Event Now</a>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.filter-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterValue = this.getAttribute('data-filter');

            // Update active states
            filterButtons.forEach(btn => {
                btn.classList.remove('text-(--primary-color)', 'border-b-2', 'border-(--primary-color)');
                btn.classList.add('text-(--text-white)');
            });
            this.classList.remove('text-(--text-white)');
            this.classList.add('text-(--primary-color)', 'border-b-2', 'border-(--primary-color)');

            // Show/Hide items with animation
            galleryItems.forEach(item => {
                // First fade out
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    if (filterValue === 'all' || item.classList.contains(filterValue)) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.display = 'none';
                    }
                }, 300);
            });

            // Refresh AOS to detect new positions of elements (like CTA) after filtering
            setTimeout(() => {
                AOS.refresh();
            }, 600);
        });
    });
    
    // Initial setup for items to ensure they respect the transition
    galleryItems.forEach(item => {
        item.style.transition = 'all 0.4s ease-in-out';
    });
});
</script>

@endsection
