@extends('layouts.admin.master')

@section('title', 'Create Product')
@section('page-title', 'Create Product')
@section('page-description', 'Add a new product to your catalog')

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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Basic Information</h3>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Short Description</label>
                        <textarea name="short_description" rows="2"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('short_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Description</label>
                        <textarea name="description" rows="5"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Product Image</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#FFD900] file:text-[#333333] hover:file:bg-[#FFA500] cursor-pointer">
                        <p class="text-gray-400 text-xs mt-2">Recommended: 800x800px. Max file size: 2MB</p>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Pricing & Inventory</h3>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Regular Price ($) *</label>
                        <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Sale Price ($)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}" min="0" step="0.01"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        <p class="text-gray-400 text-xs mt-1">Leave empty if no sale price</p>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Category</label>
                        <select name="category_id" 
                                class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->full_name }}
                                </option>
                                @foreach($category->children as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('category_id') == $subcategory->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;└─ {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        <p class="text-gray-400 text-xs mt-1">
                            <a href="{{ route('admin.product-categories.create') }}" target="_blank" class="text-[#FFD900] hover:underline">Create new category</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Options -->
            <div class="mt-6 pt-6 border-t border-[#282828]">
                <h3 class="text-xl font-bold text-white mb-4">Status Options</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-[#FFD900] bg-[#161616] border-[#282828] rounded focus:ring-[#FFD900] focus:ring-2">
                        <span class="ml-3 text-white font-semibold">Active (Visible to customers)</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}
                               class="w-5 h-5 text-[#FFD900] bg-[#161616] border-[#282828] rounded focus:ring-[#FFD900] focus:ring-2">
                        <span class="ml-3 text-white font-semibold">Featured Product</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-[#282828] flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-3 bg-[#282828] text-white rounded-lg hover:bg-[#353535] transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-[#FFD900] text-[#333333] rounded-lg hover:bg-[#FFA500] font-bold transition-colors">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
