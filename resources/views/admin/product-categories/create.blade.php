@extends('layouts.admin.master')

@section('title', 'Create Category')
@section('page-title', 'Create Category')
@section('page-description', 'Add a new product category or subcategory')

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
        <form action="{{ route('admin.product-categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Category Information</h3>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Category Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Parent Category</label>
                        <select name="parent_id" 
                                class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            <option value="">None (Main Category)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', request('parent_id')) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-gray-400 text-xs mt-1">Leave empty to create a main category, or select a parent to create a subcategory</p>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Category Image</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#FFD900] file:text-[#333333] hover:file:bg-[#FFA500] cursor-pointer">
                        <p class="text-gray-400 text-xs mt-2">Optional: Upload a category image</p>
                    </div>
                </div>

                <!-- Settings -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Settings</h3>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        <p class="text-gray-400 text-xs mt-1">Lower numbers appear first</p>
                    </div>

                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#FFD900] bg-[#161616] border-[#282828] rounded focus:ring-[#FFD900] focus:ring-2">
                            <span class="ml-3 text-white font-semibold">Active (Visible to customers)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-[#282828] flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('admin.product-categories.index') }}" class="px-6 py-3 bg-[#282828] text-white rounded-lg hover:bg-[#353535] transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-[#FFD900] text-[#333333] rounded-lg hover:bg-[#FFA500] font-bold transition-colors">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
