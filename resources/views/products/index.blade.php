@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden bg-[#161616]">
    <!-- Inner Banner -->
    <section class="relative py-28 flex items-center bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="Merch Background" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center">
            <h1 class="text-[40px] md:text-[64px] font-bold text-white mb-4" data-aos="fade-down">
                Official <span class="text-[#FFD900]">Merch</span>
            </h1>
            <p class="text-gray-300 text-[18px] md:text-[20px] max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="100">
                Premium apparel and accessories designed for the modern DJ and MC.
            </p>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-20">
        <div class="container mx-auto px-6 lg:px-16">
            <!-- Filters -->
            <div class="mb-8">
                <form method="GET" action="{{ route('merch') }}" class="flex flex-col md:flex-row gap-4">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search products..."
                           class="flex-1 bg-[#1F1F1F] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    
                    <select name="category" class="bg-[#1F1F1F] border border-[#282828] text-white p-4 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @foreach($category->children as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ request('category') == $subcategory->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;{{ $subcategory->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    
                    <button type="submit" class="btn primary-button whitespace-nowrap">Filter</button>
                    
                    @if(request()->hasAny(['search', 'category']))
                        <a href="{{ route('merch') }}" class="btn secondary-button whitespace-nowrap">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($products as $product)
                <div class="product-card group" data-aos="fade-up">
                    <a href="{{ route('products.show', $product->id) }}">
                        <div class="product-img-wrapper relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                            @if($product->sale_price)
                                <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">Sale</span>
                            @endif
                            @php
                                $hasStock = $product->has_stock;
                                $totalStock = $product->total_stock;
                            @endphp
                            @if($hasStock && $totalStock <= 5)
                                <span class="absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold">Low Stock</span>
                            @endif
                            @if(!$hasStock)
                                <span class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <span class="bg-red-500 text-white px-4 py-2 rounded font-bold">Out of Stock</span>
                                </span>
                            @endif
                        </div>
                    </a>
                    <span class="product-category">{{ $product->productCategory->full_name ?? 'Uncategorized' }}</span>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <div class="flex items-center gap-2">
                        @if($product->isVariable())
                            <p class="product-price text-[#FFD900]">From ${{ number_format($product->min_price, 2) }}</p>
                        @else
                            @if($product->sale_price)
                                <p class="product-price text-[#FFD900]">${{ number_format($product->sale_price, 2) }}</p>
                                <p class="text-gray-500 line-through text-sm">${{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="product-price">${{ number_format($product->price, 2) }}</p>
                            @endif
                        @endif
                    </div>
                    @if($product->has_stock)
                        <a href="{{ route('products.show', $product->id) }}" class="mt-4 block w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-[#FFD900] hover:text-black hover:border-[#FFD900] transition-all font-bold uppercase text-xs tracking-widest text-center">
                            {{ $product->isVariable() ? 'View Options' : 'Add to Cart' }}
                        </a>
                    @else
                        <button disabled class="mt-4 w-full py-3 bg-gray-500 text-gray-300 border border-gray-600 cursor-not-allowed font-bold uppercase text-xs tracking-widest">
                            Out of Stock
                        </button>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-20">
                <svg class="w-24 h-24 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="text-2xl font-bold text-white mb-2">No products found</h3>
                <p class="text-gray-400">Try adjusting your search or filters</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Collection Note -->
    <section class="py-20 bg-[#1F1F1F] text-center border-t border-[#282828]">
        <div class="container mx-auto px-6 lg:px-16" data-aos="zoom-in">
            <h2 class="text-[30px] md:text-[40px] font-bold text-white mb-6">Free Shipping on Orders Over $75</h2>
            <p class="text-gray-300 font-normal text-lg mb-8">Wear the rhythm. Represent the global community of verified DJs and MCs.</p>
            <a href="{{ route('contact') }}" class="btn primary-button px-10 py-4">Contact Support</a>
        </div>
    </section>
</main>
@endsection
