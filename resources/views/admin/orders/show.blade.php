@extends('layouts.admin.master')

@section('title', 'Order Details')
@section('page-title', 'Order Details')
@section('page-description', 'View and update order information')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">Order {{ $order->order_number }}</h2>
            <p class="text-gray-400 text-sm">Placed on {{ $order->created_at->format('F d, Y g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn secondary-button">
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 pb-4 border-b border-[#282828] last:border-0 last:pb-0">
                        @if($item->product)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                        <div class="w-16 h-16 bg-[#282828] rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <!-- Order Summary -->
                <div class="mt-6 pt-6 border-t border-[#282828]">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="text-white">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tax</span>
                            <span class="text-white">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Shipping</span>
                            <span class="text-white">${{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-[#282828]">
                            <span class="text-white font-bold">Total</span>
                            <span class="text-[#FFD900] font-bold text-xl">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Shipping Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Name</p>
                        <p class="text-white font-semibold">{{ $order->shipping_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Email</p>
                        <p class="text-white font-semibold">{{ $order->shipping_email }}</p>
                    </div>
                    @if($order->shipping_phone)
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Phone</p>
                        <p class="text-white font-semibold">{{ $order->shipping_phone }}</p>
                    </div>
                    @endif
                    <div class="md:col-span-2">
                        <p class="text-gray-400 text-sm mb-1">Address</p>
                        <p class="text-white font-semibold">{{ $order->full_shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Order Status Update -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Update Status</h3>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-white font-semibold mb-2">Order Status</label>
                            <select name="status" class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Payment Status</label>
                            <select name="payment_status" class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Tracking Number</label>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                                   placeholder="Enter tracking number"
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            <p class="text-gray-400 text-xs mt-1">Add tracking number when order is shipped</p>
                        </div>

                        <button type="submit" class="w-full btn primary-button">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Information -->
            <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
                <h3 class="text-xl font-bold text-white mb-4">Order Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-400 text-sm">Order Number</p>
                        <p class="text-white font-semibold">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Customer</p>
                        <p class="text-white font-semibold">{{ $order->user->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Payment Method</p>
                        <p class="text-white font-semibold">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                    </div>
                    @if($order->notes)
                    <div>
                        <p class="text-gray-400 text-sm">Notes</p>
                        <p class="text-white text-sm">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
