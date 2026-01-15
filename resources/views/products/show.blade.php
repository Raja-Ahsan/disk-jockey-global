@extends('layouts.web.master')

@section('content')
<style>
.color-option.selected {
    border-color: #FFD900 !important;
    border-width: 3px !important;
    transform: scale(1.1);
}
.color-option {
    cursor: pointer;
    transition: all 0.2s;
}
</style>
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
            <div class="product-img-wrapper relative">
                <img id="productMainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full rounded-lg">
                @if($product->sale_price || ($product->isVariable() && $product->variations->where('sale_price', '!=', null)->count() > 0))
                    <span class="absolute top-4 left-4 bg-red-500 text-white px-4 py-2 rounded font-bold">Sale</span>
                @endif
                @if($product->featured)
                    <span class="absolute top-4 right-4 bg-[#FFD900] text-[#333333] px-4 py-2 rounded font-bold">Featured</span>
                @endif
            </div>

            <!-- Product Details -->
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $product->name }}</h1>
                
                <!-- Price Display -->
                <div class="mb-6">
                    <div class="flex items-center gap-4 mb-2">
                        <span id="productPrice" class="text-[#FFD900] text-4xl font-bold">
                            @if($product->isVariable())
                                From ${{ number_format($product->min_price, 2) }}
                            @else
                                @if($product->sale_price)
                                    ${{ number_format($product->sale_price, 2) }}
                                @else
                                    ${{ number_format($product->price, 2) }}
                                @endif
                            @endif
                        </span>
                        @if(!$product->isVariable() && $product->sale_price)
                            <span class="text-gray-500 text-2xl line-through">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    @if($product->isVariable())
                        <p id="priceNote" class="text-gray-400 text-sm">Select options to see price</p>
                    @endif
                </div>

                <!-- Category -->
                @if($product->productCategory)
                <p class="text-gray-400 mb-4">
                    <span class="text-[#FFD900]">Category:</span> {{ $product->productCategory->full_name }}
                </p>
                @endif

                <!-- Short Description -->
                @if($product->short_description)
                <p class="text-gray-300 text-lg mb-6">{{ $product->short_description }}</p>
                @endif

                <!-- Stock Status -->
                <div class="mb-6">
                    @php
                        $hasStock = $product->has_stock;
                        $totalStock = $product->total_stock;
                    @endphp
                    <div id="stockStatus">
                        @if($hasStock)
                            @if($totalStock <= 5)
                                <p class="text-yellow-400 font-semibold">Only {{ $totalStock }} left in stock!</p>
                            @else
                                <p class="text-green-400 font-semibold">In Stock</p>
                            @endif
                        @else
                            <p class="text-red-400 font-semibold">Out of Stock</p>
                        @endif
                    </div>
                </div>

                <!-- Variable Product Variation Selection -->
                @if($product->isVariable() && $product->variations->count() > 0)
                @php
                    $availableVariations = $product->variations->where('is_active', true)->where('stock', '>', 0);
                    $attributes = [];
                    $variationsData = [];
                    
                    foreach ($availableVariations as $variation) {
                        $variationAttrs = [];
                        foreach ($variation->attributes as $attr) {
                            $variationAttrs[$attr->attribute_name] = $attr->attribute_value;
                            
                            if (!isset($attributes[$attr->attribute_name])) {
                                $attributes[$attr->attribute_name] = [];
                            }
                            if (!in_array($attr->attribute_value, $attributes[$attr->attribute_name])) {
                                $attributes[$attr->attribute_name][] = $attr->attribute_value;
                            }
                        }
                        
                        $variationsData[] = [
                            'id' => $variation->id,
                            'price' => (float)$variation->price,
                            'sale_price' => $variation->sale_price ? (float)$variation->sale_price : null,
                            'stock' => (int)$variation->stock,
                            'image' => $variation->image_url,
                            'attributes' => $variationAttrs
                        ];
                    }
                @endphp
                <form id="variationForm" action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-6 mb-6">
                    @csrf
                    <input type="hidden" name="variation_id" id="selectedVariationId" value="" required>

                    @foreach($attributes as $attrName => $attrValues)
                    <div>
                        <label class="block text-white font-semibold mb-3">
                            {{ ucfirst($attrName) }}: 
                            <span id="selected{{ ucfirst($attrName) }}" class="text-[#FFD900]"></span>
                        </label>
                        @if($attrName === 'color')
                            <div class="flex flex-wrap gap-3">
                                @foreach($attrValues as $value)
                                    @php
                                        $variationWithColor = $availableVariations->first(function($v) use ($value, $attrName) {
                                            return $v->attributes->where('attribute_name', $attrName)->where('attribute_value', $value)->count() > 0;
                                        });
                                        $colorDisplay = $variationWithColor ? $variationWithColor->attributes->where('attribute_name', $attrName)->first()->attribute_display : '#000000';
                                    @endphp
                                    <button type="button" 
                                            class="variation-option color-option w-12 h-12 rounded-full border-2 border-[#282828] hover:border-[#FFD900] transition-all {{ $loop->first ? 'selected' : '' }}"
                                            data-attribute="{{ $attrName }}"
                                            data-value="{{ $value }}"
                                            style="background-color: {{ $colorDisplay }}"
                                            title="{{ $value }}">
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <select name="variation_{{ $attrName }}" 
                                    class="variation-select w-full bg-[#1F1F1F] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none"
                                    data-attribute="{{ $attrName }}">
                                <option value="">Select {{ ucfirst($attrName) }}</option>
                                @foreach($attrValues as $value)
                                    <option value="{{ $value }}" {{ $loop->first ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    @endforeach

                    <!-- Selected Variation Info -->
                    <div id="variationInfo" class="hidden p-4 bg-[#1F1F1F] border border-[#282828] rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-white font-semibold">Selected:</span>
                            <span id="variationPrice" class="text-[#FFD900] font-bold text-xl"></span>
                        </div>
                        <div class="text-gray-400 text-sm">
                            <span id="variationStock"></span> in stock
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-white font-semibold mb-3">Quantity:</label>
                        <div class="flex items-center gap-4">
                            <button type="button" id="decreaseQty" class="w-10 h-10 bg-[#1F1F1F] border border-[#282828] text-white rounded-lg hover:bg-[#282828] transition-colors">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="1" 
                                   class="w-20 bg-[#1F1F1F] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none text-center">
                            <button type="button" id="increaseQty" class="w-10 h-10 bg-[#1F1F1F] border border-[#282828] text-white rounded-lg hover:bg-[#282828] transition-colors">+</button>
                        </div>
                    </div>

                        <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Add to Cart Button -->
                    <button type="submit" id="addToCartBtn" disabled class="w-full bg-[#FFD900] text-[#333333] py-4 text-lg font-bold rounded-lg hover:bg-[#FFA500] transition-colors disabled:bg-gray-500 disabled:text-gray-300 disabled:cursor-not-allowed">
                        Add to Cart
                    </button>
                </form>
                @else
                <!-- Simple Product Add to Cart -->
                @if($hasStock)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4 mb-6">
                    @csrf
                    
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label class="block text-white font-semibold mb-3">Quantity:</label>
                        <div class="flex items-center gap-4">
                            <button type="button" onclick="document.getElementById('quantity').stepDown()" class="w-10 h-10 bg-[#1F1F1F] border border-[#282828] text-white rounded-lg hover:bg-[#282828] transition-colors">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                   class="w-20 bg-[#1F1F1F] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none text-center">
                            <button type="button" onclick="document.getElementById('quantity').stepUp()" class="w-10 h-10 bg-[#1F1F1F] border border-[#282828] text-white rounded-lg hover:bg-[#282828] transition-colors">+</button>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#FFD900] text-[#333333] py-4 text-lg font-bold rounded-lg hover:bg-[#FFA500] transition-colors">
                        Add to Cart
                    </button>
                </form>
                @else
                <button disabled class="w-full bg-gray-500 text-gray-300 border border-gray-600 cursor-not-allowed py-4 text-lg font-bold rounded-lg mb-6">
                    Out of Stock
                </button>
                @endif
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-4 mb-6">
                    <a href="{{ route('cart.index') }}" class="flex-1 bg-[#1F1F1F] border border-[#282828] text-white py-3 text-center rounded-lg hover:bg-[#282828] transition-colors font-semibold">
                        View Cart
                    </a>
                    @if(session('cart') && count(session('cart')) > 0)
                    <a href="{{ route('checkout.index') }}" class="flex-1 bg-[#FFD900] text-[#333333] py-3 text-center rounded-lg hover:bg-[#FFA500] transition-colors font-bold">
                        Checkout
                    </a>
                    @endif
                </div>

                <!-- Shipping Info -->
                <div class="p-4 bg-[#1F1F1F] border border-[#282828] rounded-lg">
                    <p class="text-gray-300 text-sm mb-2">
                        <svg class="w-5 h-5 inline mr-2 text-[#FFD900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Free shipping on orders over $75
                    </p>
                    <p class="text-gray-400 text-xs">
                        Secure checkout with Stripe
                    </p>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        @if($product->description)
        <div class="mb-16 border-t border-[#282828] pt-12">
            <h2 class="text-3xl font-bold text-white mb-6">Product Description</h2>
            <div class="text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $product->description }}</div>
        </div>
        @endif

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
                        <span class="product-category">{{ $relatedProduct->productCategory->full_name ?? 'Uncategorized' }}</span>
                        <h3 class="product-name">{{ $relatedProduct->name }}</h3>
                        <p class="product-price">
                            @if($relatedProduct->isVariable())
                                From ${{ number_format($relatedProduct->min_price, 2) }}
                            @else
                                ${{ number_format($relatedProduct->current_price, 2) }}
                            @endif
                        </p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</main>

@if($product->isVariable() && $product->variations->count() > 0)
<script>
// Variation data
const variations = @json($variationsData);

let selectedAttributes = {};
let selectedVariation = null;

// Initialize selected attributes from first variation
if (variations.length > 0) {
    const firstVariation = variations[0];
    Object.keys(firstVariation.attributes).forEach(attrName => {
        selectedAttributes[attrName] = firstVariation.attributes[attrName];
    });
    updateVariationSelection();
}

// Color option click
document.querySelectorAll('.color-option').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.color-option').forEach(b => b.classList.remove('selected', 'border-[#FFD900]'));
        this.classList.add('selected', 'border-[#FFD900]');
        selectedAttributes[this.dataset.attribute] = this.dataset.value;
        updateSelectedAttribute(this.dataset.attribute, this.dataset.value);
        updateVariationSelection();
    });
});

// Select change
document.querySelectorAll('.variation-select').forEach(select => {
    select.addEventListener('change', function() {
        selectedAttributes[this.dataset.attribute] = this.value;
        updateSelectedAttribute(this.dataset.attribute, this.value);
        updateVariationSelection();
    });
});

function updateSelectedAttribute(attrName, value) {
    const display = document.getElementById('selected' + attrName.charAt(0).toUpperCase() + attrName.slice(1));
    if (display) {
        display.textContent = value;
    }
}

function updateVariationSelection() {
    // Find matching variation
    selectedVariation = variations.find(v => {
        return Object.keys(selectedAttributes).every(attrName => {
            return v.attributes[attrName] === selectedAttributes[attrName];
        });
    });

    const variationInfo = document.getElementById('variationInfo');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const quantityInput = document.getElementById('quantity');
    const productPrice = document.getElementById('productPrice');
    const priceNote = document.getElementById('priceNote');
    const stockStatus = document.getElementById('stockStatus');

    if (selectedVariation && selectedVariation.stock > 0) {
        // Show variation info
        variationInfo.classList.remove('hidden');
        
        const price = selectedVariation.sale_price || selectedVariation.price;
        document.getElementById('variationPrice').textContent = '$' + price.toFixed(2);
        if (selectedVariation.sale_price) {
            document.getElementById('variationPrice').innerHTML = '$' + price.toFixed(2) + ' <span class="text-gray-500 line-through text-sm">$' + selectedVariation.price.toFixed(2) + '</span>';
        }
        
        document.getElementById('variationStock').textContent = selectedVariation.stock;
        const variationIdInput = document.getElementById('selectedVariationId');
        if (variationIdInput) {
            variationIdInput.value = selectedVariation.id;
        }
        
        // Update quantity max
        quantityInput.max = selectedVariation.stock;
        if (parseInt(quantityInput.value) > selectedVariation.stock) {
            quantityInput.value = selectedVariation.stock;
        }
        
        // Update price display
        productPrice.innerHTML = '$' + price.toFixed(2);
        if (selectedVariation.sale_price) {
            productPrice.innerHTML = '$' + price.toFixed(2) + ' <span class="text-gray-500 line-through text-lg ml-2">$' + selectedVariation.price.toFixed(2) + '</span>';
        }
        if (priceNote) priceNote.classList.add('hidden');
        
        // Update stock status
        if (selectedVariation.stock <= 5) {
            stockStatus.innerHTML = '<p class="text-yellow-400 font-semibold">Only ' + selectedVariation.stock + ' left in stock!</p>';
        } else {
            stockStatus.innerHTML = '<p class="text-green-400 font-semibold">In Stock</p>';
        }
        
        // Update image if variation has image
        if (selectedVariation.image) {
            document.getElementById('productMainImage').src = selectedVariation.image;
        }
        
        // Enable add to cart
        addToCartBtn.disabled = false;
    } else {
        variationInfo.classList.add('hidden');
        addToCartBtn.disabled = true;
        if (priceNote) priceNote.classList.remove('hidden');
        stockStatus.innerHTML = '<p class="text-red-400 font-semibold">This combination is out of stock</p>';
    }
}

// Quantity controls
document.getElementById('increaseQty')?.addEventListener('click', function() {
    const qty = document.getElementById('quantity');
    if (parseInt(qty.value) < parseInt(qty.max)) {
        qty.value = parseInt(qty.value) + 1;
    }
});

document.getElementById('decreaseQty')?.addEventListener('click', function() {
    const qty = document.getElementById('quantity');
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
});

// Initialize selected attributes display
Object.keys(selectedAttributes).forEach(attrName => {
    updateSelectedAttribute(attrName, selectedAttributes[attrName]);
});

// Form submission validation
document.getElementById('variationForm')?.addEventListener('submit', function(e) {
    const variationId = document.getElementById('selectedVariationId').value;
    if (!variationId || variationId === '') {
        e.preventDefault();
        alert('Please select a variation before adding to cart.');
        return false;
    }
    if (!selectedVariation || selectedVariation.stock < 1) {
        e.preventDefault();
        alert('Selected variation is out of stock.');
        return false;
    }
});
</script>
@endif
@endsection
