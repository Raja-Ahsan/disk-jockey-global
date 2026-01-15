@extends('layouts.admin.master')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('page-description', 'Update product information')

@section('content')
<div class="space-y-6">
    @if($errors->any())
        <div class="bg-red-500/20 border border-red-500 rounded-xl p-4">
            <h3 class="text-red-400 font-bold mb-2">Please fix the following errors:</h3>
            <ul class="list-disc list-inside text-red-400">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 rounded-xl p-4 text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-[#1F1F1F] border border-[#282828] rounded-xl p-8">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')

            <!-- Product Type Selection -->
            <div class="mb-8 pb-6 border-b border-[#282828]">
                <h3 class="text-xl font-bold text-white mb-4">Product Type *</h3>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-[#282828] rounded-lg cursor-pointer hover:border-[#FFD900] transition-colors product-type-option {{ old('product_type', $product->product_type ?? 'simple') === 'simple' ? 'border-[#FFD900]' : '' }}" data-type="simple">
                        <input type="radio" name="product_type" value="simple" {{ old('product_type', $product->product_type ?? 'simple') === 'simple' ? 'checked' : '' }} class="sr-only product-type-radio">
                        <div class="flex-1">
                            <div class="text-white font-bold mb-1">Simple Product</div>
                            <div class="text-gray-400 text-sm">Single product with fixed price and stock</div>
                        </div>
                        <svg class="w-6 h-6 text-[#FFD900] {{ old('product_type', $product->product_type ?? 'simple') === 'simple' ? '' : 'hidden' }} check-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </label>
                    <label class="flex items-center p-4 border-2 border-[#282828] rounded-lg cursor-pointer hover:border-[#FFD900] transition-colors product-type-option {{ old('product_type', $product->product_type ?? 'simple') === 'variable' ? 'border-[#FFD900]' : '' }}" data-type="variable">
                        <input type="radio" name="product_type" value="variable" {{ old('product_type', $product->product_type ?? 'simple') === 'variable' ? 'checked' : '' }} class="sr-only product-type-radio">
                        <div class="flex-1">
                            <div class="text-white font-bold mb-1">Variable Product</div>
                            <div class="text-gray-400 text-sm">Product with variations (color, size, etc.)</div>
                        </div>
                        <svg class="w-6 h-6 text-[#FFD900] {{ old('product_type', $product->product_type ?? 'simple') === 'variable' ? '' : 'hidden' }} check-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Basic Information</h3>
                    
                    <div>
                        <label class="block text-white font-semibold mb-2">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Short Description</label>
                        <textarea name="short_description" rows="2"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Description</label>
                        <textarea name="description" rows="5"
                                  class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Product Image</label>
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg mb-3">
                        @endif
                        <input type="file" name="image" accept="image/*"
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#FFD900] file:text-[#333333] hover:file:bg-[#FFA500] cursor-pointer">
                        <p class="text-gray-400 text-xs mt-2">Recommended: 800x800px. Max file size: 2MB</p>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Product Gallery</label>
                        @if($product->gallery && count($product->gallery) > 0)
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                @foreach($product->gallery as $index => $galleryImage)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $galleryImage) }}" alt="Gallery {{ $index + 1 }}" class="w-full h-20 object-cover rounded">
                                        <button type="button" onclick="removeGalleryImage({{ $index }})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">×</button>
                                        <input type="hidden" name="gallery_remove[]" value="{{ $index }}" class="gallery-remove-input" disabled>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="gallery[]" accept="image/*" multiple
                               class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#FFD900] file:text-[#333333] hover:file:bg-[#FFA500] cursor-pointer">
                        <p class="text-gray-400 text-xs mt-2">Upload multiple images for product gallery (optional)</p>
                        <div id="galleryPreview" class="mt-3 grid grid-cols-4 gap-2"></div>
                    </div>
                </div>

                <!-- Pricing & Inventory -->
                <div class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Pricing & Inventory</h3>
                    
                    <div id="simplePricing" class="{{ old('product_type', $product->product_type ?? 'simple') === 'variable' ? 'hidden' : '' }}">
                        <div>
                            <label class="block text-white font-semibold mb-2">Regular Price ($) *</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01"
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Sale Price ($)</label>
                            <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="0.01"
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            <p class="text-gray-400 text-xs mt-1">Leave empty if no sale price</p>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-2">Stock Quantity *</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                   class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                        </div>
                    </div>

                    <div id="variablePricing" class="{{ old('product_type', $product->product_type ?? 'simple') === 'variable' ? '' : 'hidden' }}">
                        <div class="bg-[#161616] border border-[#282828] rounded-lg p-4">
                            <p class="text-gray-400 text-sm">For variable products, pricing and stock are managed per variation below.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Category</label>
                        <select name="category_id" 
                                class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->full_name }}
                                </option>
                                @foreach($category->children as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('category_id', $product->category_id) == $subcategory->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;└─ {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        <p class="text-gray-400 text-xs mt-1">
                            <a href="{{ route('admin.product-categories.create') }}" target="_blank" class="text-[#FFD900] hover:underline">Create new category</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Variations Section (for variable products) -->
            <div id="variationsSection" class="mt-8 pt-6 border-t border-[#282828] {{ old('product_type', $product->product_type ?? 'simple') === 'variable' ? '' : 'hidden' }}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Product Variations</h3>
                    <button type="button" id="addVariation" class="px-4 py-2 bg-[#FFD900] text-[#333333] rounded-lg hover:bg-[#FFA500] font-bold transition-colors">
                        + Add Variation
                    </button>
                </div>
                <div id="variationsContainer" class="space-y-4">
                    @if($product->isVariable() && $product->variations->count() > 0)
                        @foreach($product->variations as $variation)
                            @include('admin.products.partials.variation-item', ['variation' => $variation, 'index' => $variation->id])
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Status Options -->
            <div class="mt-6 pt-6 border-t border-[#282828]">
                <h3 class="text-xl font-bold text-white mb-4">Status Options</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 text-[#FFD900] bg-[#161616] border-[#282828] rounded focus:ring-[#FFD900] focus:ring-2">
                        <span class="ml-3 text-white font-semibold">Active (Visible to customers)</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}
                               class="w-5 h-5 text-[#FFD900] bg-[#161616] border-[#282828] rounded focus:ring-[#FFD900] focus:ring-2">
                        <span class="ml-3 text-white font-semibold">Featured Product</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-[#282828] flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-3 bg-[#282828] text-white rounded-lg hover:bg-[#353535] transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-[#FFD900] text-[#333333] rounded-lg hover:bg-[#FFA500] font-bold transition-colors">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let variationCount = {{ $product->variations->count() ?? 0 }};

// Product type selection
document.querySelectorAll('.product-type-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const isVariable = this.value === 'variable';
        document.getElementById('simplePricing').classList.toggle('hidden', isVariable);
        document.getElementById('variablePricing').classList.toggle('hidden', !isVariable);
        document.getElementById('variationsSection').classList.toggle('hidden', !isVariable);
        
        // Update required fields
        const priceInput = document.querySelector('input[name="price"]');
        const stockInput = document.querySelector('input[name="stock"]');
        if (isVariable) {
            priceInput?.removeAttribute('required');
            stockInput?.removeAttribute('required');
        } else {
            priceInput?.setAttribute('required', 'required');
            stockInput?.setAttribute('required', 'required');
        }
        
        // Update visual selection
        document.querySelectorAll('.product-type-option').forEach(opt => {
            const checkIcon = opt.querySelector('.check-icon');
            if (opt.dataset.type === this.value) {
                opt.classList.add('border-[#FFD900]');
                checkIcon.classList.remove('hidden');
            } else {
                opt.classList.remove('border-[#FFD900]');
                checkIcon.classList.add('hidden');
            }
        });
    });
});

// Gallery preview
document.querySelector('input[name="gallery[]"]')?.addEventListener('change', function(e) {
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';
    Array.from(e.target.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-20 object-cover rounded';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});

function removeGalleryImage(index) {
    const input = document.querySelectorAll('.gallery-remove-input')[index];
    if (input) {
        input.disabled = false;
        input.closest('.relative').style.display = 'none';
    }
}

// Add variation
document.getElementById('addVariation')?.addEventListener('click', function() {
    variationCount++;
    const variationHtml = `
        <div class="variation-item bg-[#161616] border border-[#282828] rounded-lg p-6" data-index="${variationCount}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-white font-bold">New Variation</h4>
                <button type="button" class="removeVariation text-red-400 hover:text-red-300">Remove</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Color</label>
                    <div class="flex gap-2">
                        <input type="text" name="variations[${variationCount}][attributes][color]" placeholder="e.g., Red" 
                               class="flex-1 bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                        <input type="color" name="variations[${variationCount}][attribute_display][color]" 
                               class="w-12 h-10 rounded cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Size</label>
                    <input type="text" name="variations[${variationCount}][attributes][size]" placeholder="e.g., Large" 
                           class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Type</label>
                    <input type="text" name="variations[${variationCount}][attributes][type]" placeholder="e.g., Premium" 
                           class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Price ($)</label>
                    <input type="number" name="variations[${variationCount}][price]" step="0.01" min="0" 
                           class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Sale Price ($)</label>
                    <input type="number" name="variations[${variationCount}][sale_price]" step="0.01" min="0" 
                           class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Stock</label>
                    <input type="number" name="variations[${variationCount}][stock]" min="0" value="0" 
                           class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">SKU</label>
                    <input type="text" name="variations[${variationCount}][sku]" 
                           class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Variation Image</label>
                    <input type="file" name="variations[${variationCount}][image]" accept="image/*" 
                           class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-[#FFD900] file:text-[#333333]">
                </div>
            </div>
            <div class="mt-4 flex gap-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="variations[${variationCount}][is_default]" value="1" 
                           class="w-4 h-4 text-[#FFD900] bg-[#1F1F1F] border-[#282828] rounded">
                    <span class="ml-2 text-white text-sm">Set as default</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="variations[${variationCount}][is_active]" value="1" checked 
                           class="w-4 h-4 text-[#FFD900] bg-[#1F1F1F] border-[#282828] rounded">
                    <span class="ml-2 text-white text-sm">Active</span>
                </label>
            </div>
        </div>
    `;
    document.getElementById('variationsContainer').insertAdjacentHTML('beforeend', variationHtml);
    
    // Add remove functionality
    document.querySelectorAll('.removeVariation').forEach(btn => {
        btn.addEventListener('click', function() {
            const variationItem = this.closest('.variation-item');
            const variationId = variationItem.querySelector('input[name*="[id]"]');
            if (variationId && variationId.value) {
                // Mark for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_variations[]';
                deleteInput.value = variationId.value;
                document.getElementById('productForm').appendChild(deleteInput);
            }
            variationItem.remove();
        });
    });
});

// Initialize remove buttons
document.querySelectorAll('.removeVariation').forEach(btn => {
    btn.addEventListener('click', function() {
        const variationItem = this.closest('.variation-item');
        const variationId = variationItem.querySelector('input[name*="[id]"]');
        if (variationId && variationId.value) {
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'delete_variations[]';
            deleteInput.value = variationId.value;
            document.getElementById('productForm').appendChild(deleteInput);
        }
        variationItem.remove();
    });
});
</script>

<style>
.product-type-option.border-\[#FFD900\] .check-icon {
    display: block !important;
}
</style>
@endsection

