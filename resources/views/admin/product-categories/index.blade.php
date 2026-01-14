@extends('layouts.admin.master')

@section('title', 'Manage Product Categories')
@section('page-title', 'Manage Product Categories')
@section('page-description', 'Organize products with categories and subcategories')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white">Product Categories</h2>
            <p class="text-gray-400 text-sm">Organize your products with categories and subcategories</p>
        </div>
        <a href="{{ route('admin.product-categories.create') }}" class="btn primary-button">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Category
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 rounded-xl p-4 text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <!-- Categories Tree -->
    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#282828]">
            <h2 class="text-xl font-bold text-white">Categories & Subcategories</h2>
        </div>
        
        <div class="p-6">
            @if($categories->count() > 0)
            <div class="space-y-4">
                @foreach($categories as $category)
                <div class="bg-[#161616] border border-[#282828] rounded-lg p-6">
                    <!-- Main Category -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4 flex-1">
                            @if($category->image)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                            <div class="w-16 h-16 bg-[#282828] rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white mb-1">{{ $category->name }}</h3>
                                @if($category->description)
                                <p class="text-gray-400 text-sm">{{ \Illuminate\Support\Str::limit($category->description, 100) }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="text-gray-400 text-xs">
                                        {{ $category->products->count() }} product(s)
                                    </span>
                                    <span class="text-gray-400 text-xs">
                                        {{ $category->children->count() }} subcategory(ies)
                                    </span>
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $category->is_active ? 'bg-green-500' : 'bg-gray-500' }} text-white">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.product-categories.show', $category->id) }}" class="text-[#FFD900] hover:underline text-sm">View</a>
                            <a href="{{ route('admin.product-categories.edit', $category->id) }}" class="text-blue-400 hover:underline text-sm">Edit</a>
                            <form action="{{ route('admin.product-categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category? All subcategories will also be deleted.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Subcategories -->
                    @if($category->children->count() > 0)
                    <div class="ml-8 mt-4 pl-4 border-l-2 border-[#282828] space-y-3">
                        @foreach($category->children as $subcategory)
                        <div class="flex items-center justify-between py-3 border-b border-[#282828] last:border-0">
                            <div class="flex items-center gap-3 flex-1">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-white font-semibold">{{ $subcategory->name }}</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-gray-400 text-xs">
                                            {{ $subcategory->products->count() }} product(s)
                                        </span>
                                        <span class="px-2 py-1 rounded text-xs font-bold {{ $subcategory->is_active ? 'bg-green-500' : 'bg-gray-500' }} text-white">
                                            {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.product-categories.edit', $subcategory->id) }}" class="text-blue-400 hover:underline text-sm">Edit</a>
                                <form action="{{ route('admin.product-categories.destroy', $subcategory->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subcategory?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:underline text-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                        <a href="{{ route('admin.product-categories.create', ['parent_id' => $category->id]) }}" class="text-[#FFD900] hover:underline text-sm inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Subcategory
                        </a>
                    </div>
                    @else
                    <div class="ml-8 mt-4 pl-4 border-l-2 border-[#282828]">
                        <a href="{{ route('admin.product-categories.create', ['parent_id' => $category->id]) }}" class="text-[#FFD900] hover:underline text-sm inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Subcategory
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-white mb-2">No categories yet</h3>
                <p class="text-gray-400 mb-6">Create your first product category to organize products</p>
                <a href="{{ route('admin.product-categories.create') }}" class="btn primary-button inline-block">
                    Create Category
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
