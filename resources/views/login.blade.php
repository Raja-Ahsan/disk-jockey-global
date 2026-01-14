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
                Welcome <span class="text-(--primary-color)">Back</span> to the Beat
            </h1>
            <p class="text-(--text-white) text-lg md:text-xl font-normal mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Log in to manage your bookings, explore new talent, or update your professional profile.
            </p>
            
            <div class="hidden md:flex flex-col gap-6">
                <div class="bg-[#1F1F1F] p-6 border border-[#282828] rounded-xl text-center lg:text-left">
                    <h4 class="text-(--primary-color) font-bold text-2xl mb-2">500+</h4>
                    <p class="text-white text-sm font-medium">Verified Artists</p>
                </div>
                <div class="bg-[#1F1F1F] p-6 border border-[#282828] rounded-xl text-center lg:text-left">
                    <h4 class="text-(--primary-color) font-bold text-2xl mb-2">12k+</h4>
                    <p class="text-white text-sm font-medium">Successful Events</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Card -->
        <div class="w-full lg:w-5/12 ml-auto" data-aos="fade-left">
            <div class="bg-[#1F1F1F] border border-[#282828] p-8 md:p-12 relative overflow-hidden shadow-2xl">
                <!-- Highlight Line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-(--primary-color)"></div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-white mb-2">Login</h2>
                    <p class="text-(--text-white) font-normal">Welcome back! Please enter your details.</p>
                </div>

                <form action="#" class="space-y-6">
                    <div>
                        <label class="block text-white font-semibold mb-3">Email Address</label>
                        <input type="email" placeholder="john@example.com" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                    </div>

                    <div>
                        <div class="flex justify-between mb-3">
                            <label class="text-white font-semibold">Password</label>
                            <a href="{{ url('/forgot-password') }}" class="text-(--primary-color) text-sm hover:underline">Forgot password?</a>
                        </div>
                        <input type="password" placeholder="••••••••" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="w-4 h-4 accent-(--primary-color) bg-[#161616] border-[#282828]">
                        <label for="remember" class="ml-3 text-white text-sm cursor-pointer">Remember me </label>
                    </div>

                    <button type="submit" class="btn primary-button w-full py-5 text-[18px] uppercase tracking-widest font-bold">Sign In</button>

                    <div class="text-center mt-8">
                        <p class="text-(--text-white) font-normal">
                            Don't have an account? <a href="{{ url('/signup') }}" class="text-(--primary-color) font-bold hover:underline">Sign up for free</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection
