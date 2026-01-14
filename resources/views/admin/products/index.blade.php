@extends('layouts.admin.master')

@section('title', 'Manage Products')
@section('page-title', 'Manage Products')
@section('page-description', 'View and manage all products')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">Products</h2>
            <p class="text-gray-400 text-sm">Manage your product catalog</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn primary-button">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Product
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search products..."
                   class="flex-1 bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
            
            <select name="category" 
                    class="bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
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
            
            <button type="submit" class="btn primary-button whitespace-nowrap">Search</button>
            
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.products.index') }}" class="btn secondary-button whitespace-nowrap">Clear</a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#282828]">
            <h2 class="text-xl font-bold text-white">All Products ({{ $products->total() }})</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#282828]">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Product</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Category</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Price</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Stock</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Status</th>
                        <th class="px-6 py-4 text-left text-gray-400 font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-[#282828] hover:bg-[#282828] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <h3 class="text-white font-semibold">{{ $product->name }}</h3>
                                    <p class="text-gray-400 text-xs">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $product->productCategory->full_name ?? 'Uncategorized' }}</td>
                        <td class="px-6 py-4">
                            @if($product->sale_price)
                                <span class="text-[#FFD900] font-bold">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-gray-500 line-through text-sm ml-2">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-[#FFD900] font-bold">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white {{ $product->stock <= 5 ? 'text-yellow-400' : 'text-green-400' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $product->is_active ? 'bg-green-500' : 'bg-gray-500' }} text-white">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($product->featured)
                                <span class="px-2 py-1 rounded text-xs font-bold bg-[#FFD900] text-[#333333] ml-2">Featured</span>
                            @endif
                            @if($product->deleted_at)
                                <span class="px-2 py-1 rounded text-xs font-bold bg-red-500 text-white ml-2">Deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-400 hover:underline text-sm">Edit</a>
                                @if($product->deleted_at)
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline text-sm">Delete</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will soft delete the product.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline text-sm">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No products found. <a href="{{ route('admin.products.create') }}" class="text-[#FFD900] hover:underline">Create your first product</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-6 border-t border-[#282828]">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
