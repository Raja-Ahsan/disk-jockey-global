@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-12 px-6">
    <div class="container mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                Edit <span class="text-[#FFD900]">DJ Profile</span>
            </h1>
            <p class="text-gray-400">Update your DJ profile information</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400" data-aos="fade-down">
                <h3 class="font-bold mb-2">Please fix the following errors:</h3>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400" data-aos="fade-down">
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Form -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-8" data-aos="fade-up">
            <form action="{{ route('dj.update', $dj->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-white mb-4">Basic Information</h3>
                        
                        <div>
                            <label class="block text-white font-semibold mb-3">Stage Name *</label>
                            <input type="text" name="stage_name" value="{{ old('stage_name', $dj->stage_name) }}" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">Bio</label>
                            <textarea name="bio" rows="4"
                                      class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">{{ old('bio', $dj->bio) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">Profile Image</label>
                            @if($dj->profile_image)
                                <img src="{{ asset('storage/' . $dj->profile_image) }}" alt="{{ $dj->stage_name }}" class="w-32 h-32 rounded-lg object-cover mb-3">
                            @else
                                <img src="{{ asset('images/talent-img-00' . (($dj->id % 3) + 1) . '.png') }}" alt="{{ $dj->stage_name }}" class="w-32 h-32 rounded-lg object-cover mb-3">
                            @endif
                            <input type="file" name="profile_image" accept="image/*"
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                            <p class="text-gray-400 text-sm mt-2">Recommended size: 500x500px. Max file size: 2MB</p>
                        </div>
                    </div>

                    <!-- Location & Rates -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-bold text-white mb-4">Location & Pricing</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-white font-semibold mb-3">City *</label>
                                <input type="text" name="city" value="{{ old('city', $dj->city) }}" required
                                       class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-3">State *</label>
                                <input type="text" name="state" value="{{ old('state', $dj->state) }}" required
                                       class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">Zipcode *</label>
                            <input type="text" name="zipcode" value="{{ old('zipcode', $dj->zipcode) }}" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">Hourly Rate ($) *</label>
                            <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $dj->hourly_rate) }}" min="0" step="0.01" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">Experience (Years) *</label>
                            <input type="number" name="experience_years" value="{{ old('experience_years', $dj->experience_years) }}" min="0" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white font-semibold mb-3">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $dj->phone) }}"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-3">Website URL</label>
                        <input type="url" name="website" value="{{ old('website', $dj->website) }}"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Categories -->
                <div class="mt-6">
                    <h3 class="text-xl font-bold text-white mb-4">Categories</h3>
                    <p class="text-gray-400 text-sm mb-4">Select the categories you specialize in</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2 p-3 bg-[#282828] rounded-lg hover:bg-[#353535] cursor-pointer transition-colors">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       {{ $dj->categories->contains($category->id) ? 'checked' : '' }}
                                       class="rounded border-[#353535] text-[#FFD900] focus:ring-[#FFD900]">
                                <span class="text-white">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Genres -->
                <div class="mt-6">
                    <label class="block text-white font-semibold mb-3">Genres (comma-separated)</label>
                    <input type="text" name="genres" 
                           value="{{ old('genres', is_array($dj->genres) ? implode(', ', $dj->genres) : '') }}"
                           placeholder="e.g., Hip Hop, EDM, Pop, Rock"
                           class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    <p class="text-gray-400 text-sm mt-2">Separate multiple genres with commas</p>
                </div>

                <!-- Specialties -->
                <div class="mt-6">
                    <label class="block text-white font-semibold mb-3">Specialties (comma-separated)</label>
                    <input type="text" name="specialties" 
                           value="{{ old('specialties', is_array($dj->specialties) ? implode(', ', $dj->specialties) : '') }}"
                           placeholder="e.g., Wedding, Corporate, Nightlife"
                           class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    <p class="text-gray-400 text-sm mt-2">Separate multiple specialties with commas</p>
                </div>

                <!-- Equipment -->
                <div class="mt-6">
                    <label class="block text-white font-semibold mb-3">Equipment</label>
                    <textarea name="equipment" rows="3"
                              class="w-full bg-[#161616] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">{{ old('equipment', $dj->equipment) }}</textarea>
                    <p class="text-gray-400 text-sm mt-2">List your equipment and gear</p>
                </div>

                <!-- Availability -->
                <div class="mt-6">
                    <label class="flex items-center gap-3 p-4 bg-[#282828] rounded-lg cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $dj->is_available) ? 'checked' : '' }}
                               class="rounded border-[#353535] text-[#FFD900] focus:ring-[#FFD900] w-5 h-5">
                        <div>
                            <span class="text-white font-semibold">Available for Bookings</span>
                            <p class="text-gray-400 text-sm">Uncheck if you're currently not taking new bookings</p>
                        </div>
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="submit" class="btn primary-button flex-1">
                        Update Profile
                    </button>
                    <a href="{{ route('dj.show', $dj->id) }}" class="btn secondary-button text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
