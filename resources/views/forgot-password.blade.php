@extends('layouts.web.master')

@section('content')
<main class="min-h-screen flex items-center justify-center bg-[#161616] py-20 px-6 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-(--primary-color) opacity-5 blur-[150px] rounded-full"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-(--primary-color) opacity-5 blur-[150px] rounded-full"></div>

    <div class="container mx-auto max-w-lg relative z-10" data-aos="zoom-in">
        
        <!-- Action Card -->
        <div class="bg-[#1F1F1F] border border-[#282828] p-8 md:p-12 relative overflow-hidden shadow-2xl">
            <!-- Highlight Line -->
            <div class="absolute top-0 left-0 w-full h-1 bg-(--primary-color)"></div>

            <div class="mb-10 text-center">
               
                <h2 class="text-3xl font-bold text-white mb-2">Forgot Password?</h2>
                <p class="text-(--text-white) font-normal">No worries! Enter your email and we'll send you reset instructions.</p>
            </div>

            <form action="#" class="space-y-6">
                <div>
                    <label class="block text-white font-semibold mb-3">Email Address</label>
                    <input type="email" placeholder="john@example.com" class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-(--primary-color) outline-none transition-all placeholder:text-[#555]">
                </div>

                <button type="submit" class="btn primary-button w-full py-5 text-[18px] uppercase tracking-widest font-bold">Send Reset Link</button>

                <div class="text-center mt-8 pt-6 border-t border-[#282828]">
                    <a href="{{ url('/login') }}" class="text-(--text-white) hover:text-(--primary-color) transition-colors font-medium flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>

    </div>
</main>
@endsection
