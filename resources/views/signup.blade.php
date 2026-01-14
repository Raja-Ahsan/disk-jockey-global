@extends('layouts.web.master')

@section('content')
<main class="min-h-screen flex items-center justify-center bg-[#161616] py-20 px-6 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-(--primary-color) opacity-5 blur-[150px] rounded-full"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-(--primary-color) opacity-5 blur-[150px] rounded-full"></div>

    <div class="container mx-auto max-w-6xl flex flex-col lg:flex-row items-center gap-16 relative z-10">
        
        <!-- Left Side: Content -->
        <div class="w-full lg:w-1/2 text-center lg:text-left" data-aos="fade-right">
            <h1 class="text-[40px] md:text-[60px] font-bold text-white mb-6 leading-tight">
                Join the <span class="text-(--primary-color)">Global</span><br>
                Rhythm Community
            </h1>
            <p class="text-(--text-white) text-lg md:text-xl font-normal mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Connect with world-class talent or showcase your skills to organizers across the globe. Start your journey today.
            </p>
            
            <div class="space-y-6 hidden md:block">
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="w-10 h-10 rounded-full bg-[#1F1F1F] flex items-center justify-center mr-4 border border-[#282828]">
                        <svg class="w-5 h-5 text-(--primary-color)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-white font-medium text-lg">Verified Professional Talent Only</span>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="w-10 h-10 rounded-full bg-[#1F1F1F] flex items-center justify-center mr-4 border border-[#282828]">
                        <svg class="w-5 h-5 text-(--primary-color)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-white font-medium text-lg">Secure & Instant Booking System</span>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="w-10 h-10 rounded-full bg-[#1F1F1F] flex items-center justify-center mr-4 border border-[#282828]">
                        <svg class="w-5 h-5 text-(--primary-color)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-white font-medium text-lg">Global Marketplace Exposure</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Form Card -->
        <div class="w-full lg:w-1/2" data-aos="fade-left">
            <div class="bg-[#1F1F1F] border border-[#282828] p-8 md:p-12 relative overflow-hidden shadow-2xl">
                <!-- Highlight Line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-(--primary-color)"></div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-white mb-2">Create Account</h2>
                    <p class="text-(--text-white) font-normal">Please enter your details to get started.</p>
                </div>

                <form action="#" class="space-y-6">
                

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white font-semibold mb-3">First Name</label>
                            <input type="text" placeholder="John" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-3">Last Name</label>
                            <input type="text" placeholder="Doe" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-3">Email Address</label>
                        <input type="email" placeholder="john@example.com" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-3">Password</label>
                        <input type="password" placeholder="••••••••" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                    </div>

                    <p class="text-(--text-white) text-sm font-normal">
                        By signing up, you agree to our <a href="#" class="text-(--primary-color) hover:underline">Terms of Service</a> and <a href="#" class="text-(--primary-color) hover:underline">Privacy Policy</a>.
                    </p>

                    <button type="submit" class="btn primary-button w-full py-5 text-[18px] uppercase tracking-widest font-bold">Register Now</button>

                    <div class="text-center mt-8">
                        <p class="text-(--text-white) font-normal">
                            Already have an account? <a href="{{ url('/login') }}" class="text-(--primary-color) font-bold hover:underline">Login here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection
