@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-12 px-6">
    <div class="container mx-auto max-w-6xl">
        <!-- Header -->
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                Shopping <span class="text-[#FFD900]">Cart</span>
            </h1>
            <p class="text-gray-400">Review your items before checkout</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400" data-aos="fade-down">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400" data-aos="fade-down">
                {{ session('error') }}
            </div>
        @endif

        @if(count($products) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($products as $item)
                <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row gap-6">
                        <img src="{{ $item['variation'] ? $item['variation']->image_url : $item['product']->image_url }}" alt="{{ $item['product']->name }}" 
                             class="w-32 h-32 object-cover rounded-lg">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white mb-2">{{ $item['product']->name }}</h3>
                            @if($item['variation'])
                                <div class="mb-2">
                                    @foreach($item['variation']->attributes as $attr)
                                        <span class="inline-block px-2 py-1 bg-[#161616] text-gray-300 text-xs rounded mr-2">
                                            {{ ucfirst($attr->attribute_name) }}: {{ $attr->attribute_value }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <p class="text-gray-400 mb-4">{{ $item['product']->productCategory->full_name ?? 'General' }}</p>
                            <div class="flex items-center gap-4 mb-4">
                                <form action="{{ route('cart.update', $item['cart_key']) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <label class="text-white font-semibold">Qty:</label>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                           min="1" 
                                           max="{{ $item['variation'] ? $item['variation']->stock : $item['product']->stock }}" 
                                           class="w-20 bg-[#161616] border border-[#282828] text-white p-2 rounded-lg focus:border-[#FFD900] focus:outline-none text-center"
                                           onchange="this.form.submit()">
                                </form>
                                <form action="{{ route('cart.remove', $item['cart_key']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm" onclick="return confirm('Remove this item from cart?')">
                                        Remove
                                    </button>
                                </form>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-[#FFD900] text-xl font-bold">${{ number_format($item['subtotal'], 2) }}</p>
                                <p class="text-gray-400 text-sm">${{ number_format($item['price'], 2) }} each</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Clear Cart -->
                <div class="text-right">
                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-400 text-sm" onclick="return confirm('Clear all items from cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6 sticky top-6" data-aos="fade-left">
                    <h2 class="text-xl font-bold text-white mb-4">Order Summary</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="text-white font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tax (8%)</span>
                            <span class="text-white font-semibold">${{ number_format($total * 0.08, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Shipping</span>
                            <span class="text-white font-semibold">
                                @if($total >= 75)
                                    <span class="text-green-400">FREE</span>
                                @else
                                    $10.00
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-[#282828]">
                            <span class="text-white font-bold text-lg">Total</span>
                            <span class="text-[#FFD900] font-bold text-xl">
                                ${{ number_format($total + ($total * 0.08) + ($total >= 75 ? 0 : 10), 2) }}
                            </span>
                        </div>
                    </div>

                    @auth
                    <a href="{{ route('checkout.index') }}" class="btn primary-button w-full text-center py-4">
                        Proceed to Checkout
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn primary-button w-full text-center py-4">
                        Login to Checkout
                    </a>
                    @endauth

                    <a href="{{ route('merch') }}" class="block text-center text-gray-400 hover:text-[#FFD900] mt-4 text-sm">
                        Continue Shopping →
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-12 text-center" data-aos="fade-up">
            <svg class="w-24 h-24 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-white mb-2">Your cart is empty</h3>
            <p class="text-gray-400 mb-6">Start adding products to your cart!</p>
            <a href="{{ route('merch') }}" class="btn primary-button inline-block">
                Browse Products
            </a>
        </div>
        @endif
    </div>
</main>
@endsection
