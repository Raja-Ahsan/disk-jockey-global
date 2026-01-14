@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden bg-[#161616]">
    <!-- Inner Banner -->
    <section class="relative py-28 flex items-center bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="How It Works Background" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center">
            <h1 class="text-[40px] md:text-[64px] font-bold text-white mb-4" data-aos="fade-down">
                How It <span class="text-(--primary-color)">Works</span>
            </h1>
            <p class="text-(--text-white) text-[18px] md:text-[20px] max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="100">
                A simple, 3-step process to bring world-class entertainment to your next event.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20">
        <div class="container mx-auto px-6 lg:px-16">
            
            <!-- Step 1 -->
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-24 mb-24">
                <div class="w-full lg:w-1/2" data-aos="fade-right">
                    <div class="work-img-wrapper mb-0!">
                        <img src="{{ asset('images/work-img-1.png') }}" alt="Search and Discover" class="work-img w-full">
                    </div>
                </div>
                <div class="w-full lg:w-1/2" data-aos="fade-left">
                    <div class="flex items-center mb-6">
                        <span class="w-12 h-12 bg-(--primary-color) text-black rounded-full flex items-center justify-center font-bold text-xl mr-4">01</span>
                        <span class="text-(--primary-color) tracking-[0.2em] font-normal uppercase">Discover</span>
                    </div>
                    <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-6 leading-tight">
                        Browse verified <span class="text-(--primary-color)">DJs & MCs</span>
                    </h2>
                    <p class="text-(--text-white) font-normal text-[18px] leading-relaxed mb-6">
                        Explore our curated marketplace of professional talent. Every performer on Disk Jockey Global is verified for quality and reliability, ensuring you only see the best in the industry.
                    </p>
                    <ul class="space-y-3 text-(--text-white) font-normal">
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Filter by genre and style</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Search by event location</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Listen to audio samples and mixes</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-24 mb-24">
                <div class="w-full lg:w-1/2" data-aos="fade-left">
                    <div class="work-img-wrapper mb-0!">
                        <img src="{{ asset('images/work-img-2.png') }}" alt="Check Details" class="work-img w-full">
                    </div>
                </div>
                <div class="w-full lg:w-1/2" data-aos="fade-right">
                    <div class="flex items-center mb-6">
                        <span class="w-12 h-12 bg-(--primary-color) text-black rounded-full flex items-center justify-center font-bold text-xl mr-4">02</span>
                        <span class="text-(--primary-color) tracking-[0.2em] font-normal uppercase">Review</span>
                    </div>
                    <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-6 leading-tight">
                        View profiles, <span class="text-(--primary-color)">Pricing</span> & Availability
                    </h2>
                    <p class="text-(--text-white) font-normal text-[18px] leading-relaxed mb-6">
                        Get full transparency on every performer. Our detailed profiles give you all the information you need to make an informed decision without the back-and-forth typical of traditional agencies.
                    </p>
                    <ul class="space-y-4 text-(--text-white) font-normal">
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Instant pricing breakdowns</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Real-time availability calendars</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Verified client reviews</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-24">
                <div class="w-full lg:w-1/2" data-aos="fade-right">
                    <div class="work-img-wrapper mb-0!">
                        <img src="{{ asset('images/work-img-3.png') }}" alt="Secure Booking" class="work-img w-full">
                    </div>
                </div>
                <div class="w-full lg:w-1/2" data-aos="fade-left">
                    <div class="flex items-center mb-6">
                        <span class="w-12 h-12 bg-(--primary-color) text-black rounded-full flex items-center justify-center font-bold text-xl mr-4">03</span>
                        <span class="text-(--primary-color) tracking-[0.2em] font-normal uppercase">Secure</span>
                    </div>
                    <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-6 leading-tight">
                        Book and pay <span class="text-(--primary-color)">Securely</span>
                    </h2>
                    <p class="text-(--text-white) font-normal text-[18px] leading-relaxed mb-6">
                        Lock in your entertainment with confidence. Our secure platform handles the contract and payment processing, so you can focus on building your event while we handle the logistics.
                    </p>
                    <ul class="space-y-4 text-(--text-white) font-normal">
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Locked-in booking dates</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Safe and encrypted payments</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-(--primary-color) mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Dedicated support through the event</li>
                    </ul>
                </div>
            </div>

        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-[#1F1F1F] text-center mt-20">
        <div class="container mx-auto px-6 lg:px-16" data-aos="zoom-in">
            <h2 class="text-[30px] md:text-[48px] font-bold text-white mb-10 leading-tight">
                Experience the <span class="text-(--primary-color)">Evolution</span><br>
                of Entertainment Booking
            </h2>
            <a href="#" class="btn primary-button px-10! py-5! text-[18px] uppercase font-bold tracking-widest">
                Start Exploring Now
            </a>
        </div>
    </section>
</main>
@endsection
