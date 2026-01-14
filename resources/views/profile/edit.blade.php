@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-12 px-6">
    <div class="container mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                Edit <span class="text-[#FFD900]">Profile</span>
            </h1>
            <p class="text-gray-400">Update your account information</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400" data-aos="fade-down">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Form -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-8" data-aos="fade-up">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-white font-semibold mb-3">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required
                           class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-white font-semibold mb-3">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required
                           class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-white font-semibold mb-3">Phone Number</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $user->phone) }}" 
                           class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                </div>

                <!-- Address -->
                <div class="mb-6">
                    <label for="address" class="block text-white font-semibold mb-3">Address</label>
                    <textarea id="address" 
                              name="address" 
                              rows="3"
                              class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">{{ old('address', $user->address) }}</textarea>
                </div>

                <!-- Password Section -->
                <div class="border-t border-[#282828] pt-6 mt-6">
                    <h3 class="text-xl font-bold text-white mb-4">Change Password</h3>
                    <p class="text-gray-400 text-sm mb-6">Leave blank if you don't want to change your password</p>

                    <!-- Current Password -->
                    <div class="mb-6">
                        <label for="current_password" class="block text-white font-semibold mb-3">Current Password</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>

                    <!-- New Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-white font-semibold mb-3">New Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-white font-semibold mb-3">Confirm New Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="submit" class="btn primary-button flex-1">
                        Update Profile
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn secondary-button text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
