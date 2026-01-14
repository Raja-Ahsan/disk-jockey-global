@extends('layouts.admin.master')

@section('title', 'Product Details')
@section('page-title', 'Product Details')
@section('page-description', 'View product information')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">{{ $product->name }}</h2>
            <p class="text-gray-400 text-sm">SKU: {{ $product->sku ?? 'N/A' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn primary-button">
                Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn secondary-button">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Image -->
        <div class="lg:col-span-1">
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-lg">
            </div>
        </div>

        <!-- Product Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Product Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Name</p>
                        <p class="text-white font-semibold">{{ $product->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Category</p>
                        <p class="text-white font-semibold">{{ $product->category ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">SKU</p>
                        <p class="text-white font-semibold">{{ $product->sku ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Stock</p>
                        <p class="text-white font-semibold {{ $product->stock <= 5 ? 'text-yellow-400' : 'text-green-400' }}">
                            {{ $product->stock }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Price</p>
                        @if($product->sale_price)
                            <div class="flex items-center gap-2">
                                <p class="text-[#FFD900] font-bold">${{ number_format($product->sale_price, 2) }}</p>
                                <p class="text-gray-500 line-through">${{ number_format($product->price, 2) }}</p>
                            </div>
                        @else
                            <p class="text-[#FFD900] font-bold">${{ number_format($product->price, 2) }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Status</p>
                        <div class="flex gap-2">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $product->is_active ? 'bg-green-500' : 'bg-gray-500' }} text-white">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($product->featured)
                                <span class="px-2 py-1 rounded text-xs font-bold bg-[#FFD900] text-[#333333]">Featured</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($product->description)
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Description</h3>
                <p class="text-gray-300 whitespace-pre-wrap">{{ $product->description }}</p>
            </div>
            @endif

            <!-- Order Items (if any) -->
            @if($product->orderItems->count() > 0)
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Orders ({{ $product->orderItems->count() }})</h3>
                <p class="text-gray-400 text-sm">This product has been ordered {{ $product->orderItems->sum('quantity') }} time(s)</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
