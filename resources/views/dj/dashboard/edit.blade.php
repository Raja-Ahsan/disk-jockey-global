@extends('layouts.dj.master')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')
@section('page-description', 'Update your DJ profile information')

@section('content')
<div class="max-w-4xl">
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
            <h3 class="font-bold mb-2">Please fix the following errors:</h3>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Form -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6 sm:p-8">
        <form action="{{ route('dj.dashboard.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Basic Information</h3>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Stage Name *</label>
                        <input type="text" name="stage_name" value="{{ old('stage_name', $dj->stage_name) }}" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Bio</label>
                        <textarea name="bio" rows="4"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">{{ old('bio', $dj->bio) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Profile Image</label>
                        @if($dj->profile_image)
                            <img src="{{ asset('storage/' . $dj->profile_image) }}" alt="{{ $dj->stage_name }}" class="w-24 h-24 rounded-lg object-cover mb-3">
                        @else
                            <img src="{{ asset('images/talent-img-00' . (($dj->id % 3) + 1) . '.png') }}" alt="{{ $dj->stage_name }}" class="w-24 h-24 rounded-lg object-cover mb-3">
                        @endif
                        <input type="file" name="profile_image" accept="image/*"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#FFD900] file:text-[#333333] hover:file:bg-[#FFA500] cursor-pointer">
                        <p class="text-gray-400 text-xs mt-2">Max file size: 2MB</p>
                    </div>
                </div>

                <!-- Location & Rates -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Location & Pricing</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">City *</label>
                            <input type="text" name="city" value="{{ old('city', $dj->city) }}" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">State *</label>
                            <input type="text" name="state" value="{{ old('state', $dj->state) }}" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Zipcode *</label>
                        <input type="text" name="zipcode" value="{{ old('zipcode', $dj->zipcode) }}" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Hourly Rate ($) *</label>
                        <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $dj->hourly_rate) }}" min="0" step="0.01" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Experience (Years) *</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', $dj->experience_years) }}" min="0" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-6 pt-6 border-t border-[#282828]">
                <h3 class="text-xl font-bold text-white mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white font-semibold mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $dj->phone) }}"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Website</label>
                        <input type="url" name="website" value="{{ old('website', $dj->website) }}"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="mt-6 pt-6 border-t border-[#282828]">
                <h3 class="text-xl font-bold text-white mb-4">Categories</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($categories as $category)
                        <label class="flex items-center p-3 bg-[#161616] border border-[#282828] rounded-lg cursor-pointer hover:border-[#FFD900] transition-all">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                   {{ in_array($category->id, $dj->categories->pluck('id')->toArray()) ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#FFD900] bg-[#161616] border-[#282828] rounded focus:ring-[#FFD900] focus:ring-2">
                            <span class="ml-2 text-white">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Genres & Specialties -->
            <div class="mt-6 pt-6 border-t border-[#282828]">
                <h3 class="text-xl font-bold text-white mb-4">Genres & Specialties</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white font-semibold mb-2">Genres (comma-separated)</label>
                        <input type="text" name="genres" 
                               value="{{ old('genres', is_array($dj->genres) ? implode(', ', $dj->genres) : '') }}"
                               placeholder="e.g., Hip-Hop, EDM, Pop, Rock"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-white font-semibold mb-2">Specialties (comma-separated)</label>
                        <input type="text" name="specialties" 
                               value="{{ old('specialties', is_array($dj->specialties) ? implode(', ', $dj->specialties) : '') }}"
                               placeholder="e.g., Wedding DJ, Club DJ, MC"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Equipment & Availability -->
            <div class="mt-6 pt-6 border-t border-[#282828]">
                <h3 class="text-xl font-bold text-white mb-4">Additional Information</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-white font-semibold mb-2">Equipment</label>
                        <textarea name="equipment" rows="3"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none transition-all">{{ old('equipment', $dj->equipment) }}</textarea>
                    </div>
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_available" value="1" 
                                   {{ old('is_available', $dj->is_available) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#FFD900] bg-[#161616] border-[#282828] rounded focus:ring-[#FFD900] focus:ring-2">
                            <span class="ml-3 text-white font-semibold">Available for bookings</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 pt-6 border-t border-[#282828] flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('dj.dashboard.profile') }}" class="px-6 py-3 bg-[#282828] text-white rounded-lg hover:bg-[#353535] transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-[#FFD900] text-[#333333] rounded-lg hover:bg-[#FFA500] font-bold transition-colors">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
