@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden">
    <!-- Inner Banner -->
    <section class="relative py-28 flex items-center bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="About Us Background" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center">
            <h1 class="text-[40px] md:text-[64px] font-bold text-white mb-4" data-aos="fade-down">
                About <span class="text-(--primary-color)">Us</span>
            </h1>
            <p class="text-(--text-white) text-[18px] md:text-[20px] max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="100">
                The premier platform connecting elite DJ and MC talent with events across the United States.
            </p>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-20 bg-[#161616]">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                <div class="w-full lg:w-1/2" data-aos="fade-right">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-(--primary-color) opacity-20 blur-2xl group-hover:opacity-30 transition-all"></div>
                        <img src="{{ asset('images/talent-img-001.png') }}" alt="Our Story" class="relative rounded-none w-full object-cover shadow-2xl">
                    </div>
                </div>
                <div class="w-full lg:w-1/2" data-aos="fade-left">
                    <span class="text-(--primary-color) text-[18px] md:text-[20px] tracking-[0.2em] font-normal uppercase mb-4 block">Our Story</span>
                    <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-6 leading-tight">
                        Revolutionizing the <span class="text-(--primary-color)">Entertainment</span> Booking Experience
                    </h2>
                    <p class="text-(--text-white) font-normal text-[18px] mb-6 leading-relaxed">
                        Disk Jockey Global was founded on a simple premise: talent search and event booking should be seamless, transparent, and professional. We saw a gap in the market where high-quality DJs and MCs were often obscured by social media noise or complicated agency structures.
                    </p>
                    <p class="text-(--text-white) font-normal text-[18px] leading-relaxed">
                        By creating a centralized, verified marketplace, we empower performers to showcase their distinct styles while providing event organizers with a trusted environment to find their perfect rhythmic match.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 bg-[#1F1F1F]">
        <div class="container mx-auto px-6 lg:px-16 text-center mb-16">
            <span class="text-(--primary-color) text-[18px] md:text-[20px] tracking-[0.2em] font-normal uppercase mb-4 block" data-aos="fade-up">Our Values</span>
            <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-4" data-aos="fade-up" data-aos-delay="100">Guided by <span class="text-(--primary-color)">Excellence</span></h2>
        </div>
        
        <div class="container mx-auto px-6 lg:px-16 grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Mission -->
            <div class="p-10 bg-[#262626] border-l-4 border-(--primary-color)" data-aos="fade-right">
                <h3 class="text-[28px] font-bold text-white mb-4">Our Mission</h3>
                <p class="text-(--text-white) font-normal text-[18px] leading-relaxed">
                    To bridge the gap between world-class entertainment talent and event organizers through a platform built on trust, quality, and technical excellence. We strive to elevate the profile of the DJ and MC professions by providing professional tools for growth.
                </p>
            </div>
            
            <!-- Vision -->
            <div class="p-10 bg-[#262626] border-l-4 border-(--primary-color)" data-aos="fade-left">
                <h3 class="text-[28px] font-bold text-white mb-4">Our Vision</h3>
                <p class="text-(--text-white) font-normal text-[18px] leading-relaxed">
                    To become the global standard for performance booking, where every event — from intimate weddings to massive corporate festivals — can easily access and book the industry's most exceptional sound curators.
                </p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#161616] text-center">
        <div class="container mx-auto px-6 lg:px-16" data-aos="zoom-in">
            <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-8 leading-tight">
                Ready to Experience <span class="text-(--primary-color)">Better</span> Booking?
            </h2>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="#" class="btn primary-button px-10!">Join as Talent</a>
                <a href="#" class="btn secondary-button px-10! border-white! text-white!">Book a Performer</a>
            </div>
        </div>
    </section>
</main>
@endsection
