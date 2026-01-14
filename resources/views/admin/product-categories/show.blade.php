@extends('layouts.admin.master')

@section('title', 'Category Details')
@section('page-title', 'Category Details')
@section('page-description', 'View category information and products')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">{{ $category->name }}</h2>
            @if($category->parent)
                <p class="text-gray-400 text-sm">Subcategory of: {{ $category->parent->name }}</p>
            @else
                <p class="text-gray-400 text-sm">Main Category</p>
            @endif
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.product-categories.edit', $category->id) }}" class="btn primary-button">
                Edit Category
            </a>
            <a href="{{ route('admin.product-categories.index') }}" class="btn secondary-button">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Image & Info -->
        <div class="lg:col-span-1">
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                @if($category->image)
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full rounded-lg mb-4">
                @else
                    <div class="w-full h-48 bg-[#282828] rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                @endif
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Status</p>
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $category->is_active ? 'bg-green-500' : 'bg-gray-500' }} text-white">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Sort Order</p>
                        <p class="text-white font-semibold">{{ $category->sort_order }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Products</p>
                        <p class="text-white font-semibold">{{ $category->products->count() }}</p>
                    </div>
                    @if($category->children->count() > 0)
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Subcategories</p>
                        <p class="text-white font-semibold">{{ $category->children->count() }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Category Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            @if($category->description)
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Description</h3>
                <p class="text-gray-300 whitespace-pre-wrap">{{ $category->description }}</p>
            </div>
            @endif

            <!-- Subcategories -->
            @if($category->children->count() > 0)
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Subcategories ({{ $category->children->count() }})</h3>
                <div class="space-y-2">
                    @foreach($category->children as $subcategory)
                    <div class="flex items-center justify-between p-3 bg-[#161616] rounded-lg">
                        <div>
                            <h4 class="text-white font-semibold">{{ $subcategory->name }}</h4>
                            <p class="text-gray-400 text-xs">{{ $subcategory->products->count() }} products</p>
                        </div>
                        <a href="{{ route('admin.product-categories.show', $subcategory->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Products in Category -->
            @if($category->products->count() > 0)
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Products in this Category ({{ $category->products->count() }})</h3>
                <div class="space-y-2">
                    @foreach($category->products->take(10) as $product)
                    <div class="flex items-center justify-between p-3 bg-[#161616] rounded-lg">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                            <div>
                                <h4 class="text-white font-semibold">{{ $product->name }}</h4>
                                <p class="text-gray-400 text-xs">${{ number_format($product->current_price, 2) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                    </div>
                    @endforeach
                    @if($category->products->count() > 10)
                    <p class="text-gray-400 text-sm text-center mt-4">And {{ $category->products->count() - 10 }} more products...</p>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6 text-center">
                <p class="text-gray-400">No products in this category yet</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
