@extends('layouts.admin.master')

@section('title', 'Manage Orders')
@section('page-title', 'Manage Orders')
@section('page-description', 'View and manage all orders')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search by order number, email, or customer name..."
                   class="flex-1 bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
            
            <select name="status" class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Order Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <select name="payment_status" class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                <option value="">All Payment Status</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            
            <button type="submit" class="btn primary-button whitespace-nowrap">Filter</button>
            
            @if(request()->hasAny(['search', 'status', 'payment_status']))
                <a href="{{ route('admin.orders.index') }}" class="btn secondary-button whitespace-nowrap">Clear</a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#282828]">
            <h2 class="text-xl font-bold text-white">All Orders ({{ $orders->total() }})</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Order #</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Customer</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Date</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Total</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Payment</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-white font-semibold">{{ $order->order_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-white font-semibold">{{ $order->shipping_name }}</p>
                                <p class="text-gray-400 text-xs">{{ $order->shipping_email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-[#FFD900] font-bold">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $order->status === 'delivered' ? 'bg-green-500' : 
                                   ($order->status === 'shipped' ? 'bg-blue-500' : 
                                   ($order->status === 'processing' ? 'bg-yellow-500' : 
                                   ($order->status === 'cancelled' ? 'bg-red-500' : 'bg-gray-500'))) }} 
                                text-white">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $order->payment_status === 'paid' ? 'bg-green-500' : 
                                   ($order->payment_status === 'failed' ? 'bg-red-500' : 
                                   ($order->payment_status === 'refunded' ? 'bg-purple-500' : 'bg-gray-500')) }} 
                                text-white">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-6 border-t border-[#282828]">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
