@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden bg-[#161616]">
    <!-- Inner Banner -->
    <section class="relative py-28 flex items-center bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="Contact Background" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center">
            <h1 class="text-[40px] md:text-[64px] font-bold text-white mb-4" data-aos="fade-down">
                Contact <span class="text-(--primary-color)">Us</span>
            </h1>
            <p class="text-(--text-white) text-[18px] md:text-[20px] max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="100">
                Have questions or need assistance? Our team is here to help you create the perfect event.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-24">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="flex flex-col lg:flex-row gap-16">
                
                <!-- Contact Info -->
                <div class="w-full lg:w-1/3" data-aos="fade-right">
                    <h2 class="text-[32px] md:text-[40px] font-bold text-white mb-8">Get In <span class="text-(--primary-color)">Touch</span></h2>
                    <p class="text-(--text-white) text-[18px] mb-12 font-normal leading-relaxed">
                        Whether you're a talent looking to join or an organizer planning a gala, we'd love to hear from you.
                    </p>

                    <div class="space-y-12">
                        <!-- Address -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-[#1F1F1F] border border-[#282828] flex items-center justify-center rounded-lg mr-5 shrink-0">
                                <svg class="w-6 h-6 text-(--primary-color)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg mb-1">Our Location</h4>
                                <p class="text-(--text-white) font-normal">123 Rhythm Avenue, Suite 100<br>Miami, FL 33101</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-[#1F1F1F] border border-[#282828] flex items-center justify-center rounded-lg mr-5 shrink-0">
                                <svg class="w-6 h-6 text-(--primary-color)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg mb-1">Email Us</h4>
                                <p class="text-(--text-white) font-normal">info@diskjockeyglobal.com<br>support@diskjockeyglobal.com</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-[#1F1F1F] border border-[#282828] flex items-center justify-center rounded-lg mr-5 shrink-0">
                                <svg class="w-6 h-6 text-(--primary-color)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 004.516 4.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg mb-1">Call Us</h4>
                                <p class="text-(--text-white) font-normal">+1 (800) 123-4567<br>Mon - Fri, 9am - 6pm EST</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="w-full lg:w-2/3 bg-[#1F1F1F] border border-[#282828] p-8 md:p-12" data-aos="fade-left">
                    <h3 class="text-2xl font-bold text-white mb-8">Send Us a Message</h3>
                    <form action="#" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-white font-semibold mb-3">Full Name</label>
                                <input type="text" placeholder="Your Name" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-3">Email Address</label>
                                <input type="email" placeholder="Your Email" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-3">Subject</label>
                            <select class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all">
                                <option value="">Select a Subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="talent">Talent Opportunity</option>
                                <option value="booking">Booking Support</option>
                                <option value="merch">Merchandise</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-3">Message</label>
                            <textarea rows="6" placeholder="How can we help you?" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]"></textarea>
                        </div>
                        <button type="submit" class="btn primary-button w-full py-5 text-[18px] uppercase tracking-widest font-bold">Send Message</button>
                    </form>
                </div>

            </div>
        </div>
    </section>


</main>
@endsection
