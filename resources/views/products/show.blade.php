@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden bg-[#161616] py-12">
    <div class="container mx-auto px-6 lg:px-16">
        <!-- Breadcrumb -->
        <nav class="mb-8 text-gray-400 text-sm">
            <a href="{{ route('home') }}" class="hover:text-[#FFD900]">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('merch') }}" class="hover:text-[#FFD900]">Merch</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Product Image -->
            <div class="product-img-wrapper">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-lg">
                @if($product->sale_price)
                    <span class="absolute top-4 left-4 bg-red-500 text-white px-4 py-2 rounded font-bold">Sale</span>
                @endif
            </div>

            <!-- Product Details -->
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $product->name }}</h1>
                <div class="flex items-center gap-4 mb-6">
                    @if($product->sale_price)
                        <span class="text-[#FFD900] text-4xl font-bold">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-gray-500 text-2xl line-through">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-[#FFD900] text-4xl font-bold">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->category)
                <p class="text-gray-400 mb-4">
                    <span class="text-[#FFD900]">Category:</span> {{ $product->category }}
                </p>
                @endif

                @if($product->short_description)
                <p class="text-gray-300 text-lg mb-6">{{ $product->short_description }}</p>
                @endif

                @if($product->description)
                <div class="mb-6">
                    <h3 class="text-white font-bold mb-3">Description</h3>
                    <div class="text-gray-300 whitespace-pre-wrap">{{ $product->description }}</div>
                </div>
                @endif

                <!-- Stock Status -->
                <div class="mb-6">
                    @if($product->stock > 0)
                        @if($product->stock <= 5)
                            <p class="text-yellow-400 font-semibold">Only {{ $product->stock }} left in stock!</p>
                        @else
                            <p class="text-green-400 font-semibold">In Stock ({{ $product->stock }} available)</p>
                        @endif
                    @else
                        <p class="text-red-400 font-semibold">Out of Stock</p>
                    @endif
                </div>

                <!-- Add to Cart Form -->
                @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex items-center gap-4">
                        <label class="text-white font-semibold">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                               class="w-20 bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded-lg focus:border-[#FFD900] focus:outline-none text-center">
                    </div>
                    <button type="submit" class="w-full btn primary-button py-4 text-lg">
                        Add to Cart
                    </button>
                </form>
                @else
                <button disabled class="w-full bg-gray-500 text-gray-300 border border-gray-600 cursor-not-allowed py-4 text-lg font-bold rounded-lg">
                    Out of Stock
                </button>
                @endif

                <!-- Shipping Info -->
                <div class="mt-8 p-4 bg-[#1F1F1F] border border-[#282828] rounded-lg">
                    <p class="text-gray-300 text-sm">
                        <svg class="w-5 h-5 inline mr-2 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Free shipping on orders over $75
                    </p>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="border-t border-[#282828] pt-16">
            <h2 class="text-3xl font-bold text-white mb-8 text-center">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($relatedProducts as $relatedProduct)
                <div class="product-card group">
                    <a href="{{ route('products.show', $relatedProduct->id) }}">
                        <div class="product-img-wrapper">
                            <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" class="product-img">
                        </div>
                        <span class="product-category">{{ $relatedProduct->category ?? 'General' }}</span>
                        <h3 class="product-name">{{ $relatedProduct->name }}</h3>
                        <p class="product-price">${{ number_format($relatedProduct->current_price, 2) }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</main>
@endsection
