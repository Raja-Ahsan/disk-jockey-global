@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-12 px-6">
    <div class="container mx-auto max-w-4xl">
        <!-- Success Header -->
        <div class="text-center mb-12" data-aos="fade-down">
            <div class="inline-block p-4 bg-green-500/20 rounded-full mb-6">
                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Order Confirmed!
            </h1>
            <p class="text-gray-400 text-lg">Thank you for your purchase</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400 text-center" data-aos="fade-down">
                {{ session('success') }}
            </div>
        @endif

        <!-- Order Details -->
        <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-8 mb-6" data-aos="fade-up">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-6 border-b border-[#282828]">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2">Order {{ $order->order_number }}</h2>
                    <p class="text-gray-400">Placed on {{ $order->created_at->format('F d, Y g:i A') }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="px-4 py-2 rounded text-sm font-bold bg-green-500 text-white">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-6">
                <h3 class="text-xl font-bold text-white mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 pb-4 border-b border-[#282828] last:border-0 last:pb-0">
                        @if($item->product)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded-lg">
                        @else
                        <div class="w-20 h-20 bg-[#282828] rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h4 class="text-white font-semibold">{{ $item->product_name }}</h4>
                            <p class="text-gray-400 text-sm">Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[#FFD900] font-bold">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-[#161616] border border-[#282828] rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-white mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Subtotal</span>
                        <span class="text-white">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Tax (8%)</span>
                        <span class="text-white">${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Shipping</span>
                        <span class="text-white">
                            @if($order->shipping_cost == 0)
                                <span class="text-green-400">FREE</span>
                            @else
                                ${{ number_format($order->shipping_cost, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-[#282828]">
                        <span class="text-white font-bold text-lg">Total</span>
                        <span class="text-[#FFD900] font-bold text-xl">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Shipping Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-300">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Name</p>
                        <p class="font-semibold">{{ $order->shipping_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Email</p>
                        <p class="font-semibold">{{ $order->shipping_email }}</p>
                    </div>
                    @if($order->shipping_phone)
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Phone</p>
                        <p class="font-semibold">{{ $order->shipping_phone }}</p>
                    </div>
                    @endif
                    <div class="md:col-span-2">
                        <p class="text-gray-400 text-sm mb-1">Address</p>
                        <p class="font-semibold">{{ $order->full_shipping_address }}</p>
                    </div>
                    @if($order->tracking_number)
                    <div class="md:col-span-2 mt-4 p-4 bg-[#1F1F1F] border border-[#282828] rounded-lg">
                        <p class="text-gray-400 text-sm mb-1">Tracking Number</p>
                        <p class="font-semibold text-[#FFD900] text-lg">{{ $order->tracking_number }}</p>
                        @if($order->shipped_at)
                        <p class="text-gray-400 text-xs mt-1">Shipped on {{ $order->shipped_at->format('M d, Y g:i A') }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up">
            <a href="{{ route('profile.show') }}" class="btn primary-button text-center">
                View My Orders
            </a>
            <a href="{{ route('merch') }}" class="btn secondary-button text-center">
                Continue Shopping
            </a>
        </div>
    </div>
</main>
@endsection
