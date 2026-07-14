<div class="variation-item bg-[#161616] border border-[#282828] rounded-lg p-6" data-index="{{ $variation->id }}">
    <input type="hidden" name="variations[{{ $variation->id }}][id]" value="{{ $variation->id }}">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-white font-bold">Variation #{{ $variation->id }}</h4>
        <button type="button" class="removeVariation text-red-400 hover:text-red-300">Remove</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @php
            $colorAttr = $variation->attributes->where('attribute_name', 'color')->first();
            $sizeAttr = $variation->attributes->where('attribute_name', 'size')->first();
            $typeAttr = $variation->attributes->where('attribute_name', 'type')->first();
        @endphp
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">Color</label>
            <div class="flex gap-2">
                <input type="text" name="variations[{{ $variation->id }}][attributes][color]" 
                       value="{{ $colorAttr->attribute_value ?? '' }}" placeholder="e.g., Red" 
                       class="flex-1 bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
                <input type="color" name="variations[{{ $variation->id }}][attribute_display][color]" 
                       value="{{ $colorAttr->attribute_display ?? '#FF0000' }}" 
                       class="w-12 h-10 rounded cursor-pointer">
            </div>
        </div>
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">Size</label>
            <input type="text" name="variations[{{ $variation->id }}][attributes][size]" 
                   value="{{ $sizeAttr->attribute_value ?? '' }}" placeholder="e.g., Large" 
                   class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
        </div>
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">Type</label>
            <input type="text" name="variations[{{ $variation->id }}][attributes][type]" 
                   value="{{ $typeAttr->attribute_value ?? '' }}" placeholder="e.g., Premium" 
                   class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
        </div>
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">Price ($)</label>
            <input type="number" name="variations[{{ $variation->id }}][price]" 
                   value="{{ $variation->price }}" step="0.01" min="0" 
                   class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
        </div>
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">Sale Price ($)</label>
            <input type="number" name="variations[{{ $variation->id }}][sale_price]" 
                   value="{{ $variation->sale_price }}" step="0.01" min="0" 
                   class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
        </div>
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">Stock</label>
            <input type="number" name="variations[{{ $variation->id }}][stock]" 
                   value="{{ $variation->stock }}" min="0" 
                   class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
        </div>
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">SKU</label>
            <input type="text" name="variations[{{ $variation->id }}][sku]" 
                   value="{{ $variation->sku }}" 
                   class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm">
        </div>
        <div>
            <label class="block text-white font-semibold mb-2 text-sm">Variation Image</label>
            @if($variation->image)
                <img src="{{ $variation->image_url }}" alt="Variation" class="w-16 h-16 object-cover rounded mb-2">
            @endif
            <input type="file" name="variations[{{ $variation->id }}][image]" accept="image/*" 
                   class="w-full bg-[#1F1F1F] border border-[#282828] text-white p-2 rounded text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-[#FFD900] file:text-[#333333]">
        </div>
    </div>
    <div class="mt-4 flex gap-4">
        <label class="flex items-center cursor-pointer">
            <input type="checkbox" name="variations[{{ $variation->id }}][is_default]" value="1" 
                   {{ $variation->is_default ? 'checked' : '' }}
                   class="w-4 h-4 text-[#FFD900] bg-[#1F1F1F] border-[#282828] rounded">
            <span class="ml-2 text-white text-sm">Set as default</span>
        </label>
        <label class="flex items-center cursor-pointer">
            <input type="checkbox" name="variations[{{ $variation->id }}][is_active]" value="1" 
                   {{ $variation->is_active ? 'checked' : '' }}
                   class="w-4 h-4 text-[#FFD900] bg-[#1F1F1F] border-[#282828] rounded">
            <span class="ml-2 text-white text-sm">Active</span>
        </label>
    </div>
</div>
