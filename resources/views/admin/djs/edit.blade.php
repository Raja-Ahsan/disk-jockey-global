@extends('layouts.admin.master')

@section('title', 'Edit DJ')
@section('page-title', 'Edit DJ')
@section('page-description', 'Update DJ profile information')

@section('content')
<div class="space-y-6">
    @if($errors->any())
        <div class="bg-red-500/20 border border-red-500 rounded-xl p-4">
            <h3 class="text-red-400 font-bold mb-2">Please fix the following errors:</h3>
            <ul class="list-disc list-inside text-red-400">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-8">
        <form action="{{ route('admin.djs.update', $dj->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Basic Information</h3>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Stage Name *</label>
                        <input type="text" name="stage_name" value="{{ old('stage_name', $dj->stage_name) }}" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Bio</label>
                        <textarea name="bio" rows="4"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('bio', $dj->bio) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Profile Image</label>
                        @if($dj->profile_image)
                            <img src="{{ asset('storage/' . $dj->profile_image) }}" alt="{{ $dj->stage_name }}" class="w-32 h-32 rounded-lg object-cover mb-3">
                        @endif
                        <input type="file" name="profile_image" accept="image/*"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>
                </div>

                <!-- Location & Rates -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Location & Pricing</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">City *</label>
                            <input type="text" name="city" value="{{ old('city', $dj->city) }}" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-white font-semibold mb-2">State *</label>
                            <input type="text" name="state" value="{{ old('state', $dj->state) }}" required
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Zipcode *</label>
                        <input type="text" name="zipcode" value="{{ old('zipcode', $dj->zipcode) }}" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Hourly Rate ($) *</label>
                        <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $dj->hourly_rate) }}" min="0" step="0.01" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Experience (Years) *</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', $dj->experience_years) }}" min="0" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="mt-6">
                <h3 class="text-xl font-bold text-white mb-4">Categories</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($categories as $category)
                        <label class="flex items-center gap-2 p-3 bg-[#282828] rounded-lg hover:bg-[#353535] cursor-pointer">
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
                <label class="block text-white font-semibold mb-2">Genres (comma-separated)</label>
                <input type="text" name="genres" 
                       value="{{ old('genres', is_array($dj->genres) ? implode(', ', $dj->genres) : '') }}"
                       placeholder="e.g., Hip Hop, EDM, Pop, Rock"
                       class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <p class="text-gray-400 text-sm mt-1">Separate multiple genres with commas</p>
            </div>

            <!-- Specialties -->
            <div class="mt-6">
                <label class="block text-white font-semibold mb-2">Specialties (comma-separated)</label>
                <input type="text" name="specialties" 
                       value="{{ old('specialties', is_array($dj->specialties) ? implode(', ', $dj->specialties) : '') }}"
                       placeholder="e.g., Wedding, Corporate, Nightlife"
                       class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <p class="text-gray-400 text-sm mt-1">Separate multiple specialties with commas</p>
            </div>

            <!-- Status -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="flex items-center gap-3 p-4 bg-[#282828] rounded-lg cursor-pointer">
                        <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', $dj->is_verified) ? 'checked' : '' }}
                               class="rounded border-[#353535] text-[#FFD900] focus:ring-[#FFD900]">
                        <div>
                            <span class="text-white font-semibold">Verified</span>
                            <p class="text-gray-400 text-sm">Mark this DJ as verified</p>
                        </div>
                    </label>
                </div>
                <div>
                    <label class="flex items-center gap-3 p-4 bg-[#282828] rounded-lg cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $dj->is_available) ? 'checked' : '' }}
                               class="rounded border-[#353535] text-[#FFD900] focus:ring-[#FFD900]">
                        <div>
                            <span class="text-white font-semibold">Available</span>
                            <p class="text-gray-400 text-sm">DJ is available for bookings</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Equipment -->
            <div class="mt-6">
                <label class="block text-white font-semibold mb-2">Equipment</label>
                <textarea name="equipment" rows="3"
                          class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('equipment', $dj->equipment) }}</textarea>
            </div>

            <!-- Submit -->
            <div class="mt-8 flex gap-4">
                <button type="submit" class="btn primary-button">Update DJ Profile</button>
                <a href="{{ route('admin.djs.show', $dj->id) }}" class="btn secondary-button">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
