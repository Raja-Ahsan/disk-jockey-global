@extends('layouts.web.master')

@section('content')
<main class="min-h-screen flex items-center justify-center bg-[#161616] py-20 px-6 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-[#FFD900] opacity-5 blur-[150px] rounded-full"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#FFD900] opacity-5 blur-[150px] rounded-full"></div>

    <div class="container mx-auto max-w-6xl flex flex-col lg:flex-row items-center gap-16 relative z-10">
        
        <!-- Left Side: Content -->
        <div class="w-full lg:w-1/2 text-center lg:text-left" data-aos="fade-right">
            <h1 class="text-[40px] md:text-[60px] font-bold text-white mb-6 leading-tight">
                Join the <span class="text-[#FFD900]">Global</span><br>
                Rhythm Community
            </h1>
            <p class="text-white text-lg md:text-xl font-normal mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Connect with world-class talent or showcase your skills to organizers across the globe. Start your journey today.
            </p>
            
            <div class="space-y-6 hidden md:block">
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="w-10 h-10 rounded-full bg-[#1F1F1F] flex items-center justify-center mr-4 border border-[#282828]">
                        <svg class="w-5 h-5 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-white font-medium text-lg">Verified Professional Talent Only</span>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="w-10 h-10 rounded-full bg-[#1F1F1F] flex items-center justify-center mr-4 border border-[#282828]">
                        <svg class="w-5 h-5 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-white font-medium text-lg">Secure & Instant Booking System</span>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <div class="w-10 h-10 rounded-full bg-[#1F1F1F] flex items-center justify-center mr-4 border border-[#282828]">
                        <svg class="w-5 h-5 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-white font-medium text-lg">Global Marketplace Exposure</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Form Card -->
        <div class="w-full lg:w-1/2" data-aos="fade-left">
            <div class="bg-[#1F1F1F] border border-[#282828] p-8 md:p-12 relative overflow-hidden shadow-2xl">
                <!-- Highlight Line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-[#FFD900]"></div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-white mb-2">Create Account</h2>
                    <p class="text-white font-normal">Please enter your details to get started.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-red-500 font-semibold">Registration Failed</p>
                        </div>
                        <ul class="list-disc list-inside text-red-400 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-white font-semibold mb-3">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-3">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-3">I want to</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="user" {{ old('role') === 'user' ? 'checked' : '' }} required class="peer sr-only">
                                <div class="bg-[#161616] border-2 border-[#282828] p-4 rounded-lg text-center transition-all peer-checked:border-[#FFD900] peer-checked:bg-[#FFD900]/10 hover:border-[#FFD900]/50">
                                    <div class="text-white font-semibold mb-1">Book Talent</div>
                                    <div class="text-gray-400 text-sm">Find & book DJs/MCs</div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="role" value="dj" {{ old('role') === 'dj' ? 'checked' : '' }} required class="peer sr-only">
                                <div class="bg-[#161616] border-2 border-[#282828] p-4 rounded-lg text-center transition-all peer-checked:border-[#FFD900] peer-checked:bg-[#FFD900]/10 hover:border-[#FFD900]/50">
                                    <div class="text-white font-semibold mb-1">Join as DJ/MC</div>
                                    <div class="text-gray-400 text-sm">Showcase your talent</div>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-3">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-3">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                    </div>

                    <p class="text-white text-sm font-normal">
                        By signing up, you agree to our <a href="#" class="text-[#FFD900] hover:underline">Terms of Service</a> and <a href="#" class="text-[#FFD900] hover:underline">Privacy Policy</a>.
                    </p>

                    <button type="submit" class="btn primary-button w-full py-5 text-[18px] uppercase tracking-widest font-bold">Register Now</button>

                    <div class="text-center mt-8">
                        <p class="text-white font-normal">
                            Already have an account? <a href="{{ route('login') }}" class="text-[#FFD900] font-bold hover:underline">Login here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection
