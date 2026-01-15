<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariation;
use App\Models\ProductVariationAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Product::withTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->with('productCategory', 'variations')->orderBy('created_at', 'desc')->paginate(15);
        $categories = ProductCategory::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'product_type' => 'required|in:simple,variable',
            'price' => 'required_if:product_type,simple|nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock' => 'required_if:product_type,simple|nullable|integer|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'featured' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'description', 'short_description', 'product_type', 'price', 'sale_price',
            'sku', 'stock', 'category_id', 'is_active', 'featured'
        ]);

        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['featured'] = $request->has('featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle gallery
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        $product = Product::create($data);

        // Handle variations for variable products
        if ($request->product_type === 'variable' && $request->has('variations')) {
            $this->saveVariations($product, $request->variations);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    private function saveVariations($product, $variations)
    {
        foreach ($variations as $index => $variationData) {
            if (empty($variationData['attributes']) || !is_array($variationData['attributes'])) {
                continue;
            }

            $variation = ProductVariation::create([
                'product_id' => $product->id,
                'sku' => $variationData['sku'] ?? null,
                'price' => $variationData['price'] ?? null,
                'sale_price' => $variationData['sale_price'] ?? null,
                'stock' => $variationData['stock'] ?? 0,
                'sort_order' => $index,
                'is_default' => isset($variationData['is_default']) && $variationData['is_default'] === '1',
                'is_active' => !isset($variationData['is_active']) || $variationData['is_active'] === '1',
            ]);

            // Handle variation image
            if (isset($variationData['image']) && $variationData['image']->isValid()) {
                $variation->image = $variationData['image']->store('products/variations', 'public');
                $variation->save();
            }

            // Save attributes
            foreach ($variationData['attributes'] as $attrName => $attrValue) {
                if (!empty($attrValue)) {
                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => $attrName,
                        'attribute_value' => $attrValue,
                        'attribute_display' => $variationData['attribute_display'][$attrName] ?? null,
                        'sort_order' => 0,
                    ]);
                }
            }
        }
    }

    public function show($id)
    {
        $product = Product::withTrashed()->with(['variations.attributes', 'productCategory'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::withTrashed()->with(['variations.attributes', 'productCategory'])->findOrFail($id);
        $categories = ProductCategory::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'product_type' => 'required|in:simple,variable',
            'price' => 'required_if:product_type,simple|nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $id,
            'stock' => 'required_if:product_type,simple|nullable|integer|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'featured' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'description', 'short_description', 'product_type', 'price', 'sale_price',
            'sku', 'stock', 'category_id', 'is_active', 'featured'
        ]);

        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['featured'] = $request->has('featured');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle gallery
        if ($request->hasFile('gallery')) {
            // Delete old gallery images
            if ($product->gallery) {
                foreach ($product->gallery as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        } elseif ($request->has('gallery_remove')) {
            // Remove specific gallery images
            $gallery = $product->gallery ?? [];
            foreach ($request->gallery_remove as $removeIndex) {
                if (isset($gallery[$removeIndex])) {
                    Storage::disk('public')->delete($gallery[$removeIndex]);
                    unset($gallery[$removeIndex]);
                }
            }
            $data['gallery'] = array_values($gallery);
        }

        $product->update($data);

        // Handle variations for variable products
        if ($request->product_type === 'variable') {
            // Delete existing variations if needed
            if ($request->has('delete_variations')) {
                foreach ($request->delete_variations as $variationId) {
                    $variation = ProductVariation::find($variationId);
                    if ($variation) {
                        if ($variation->image) {
                            Storage::disk('public')->delete($variation->image);
                        }
                        $variation->delete();
                    }
                }
            }

            // Update or create variations
            if ($request->has('variations')) {
                $this->updateVariations($product, $request->variations);
            }
        } else {
            // Delete all variations if switching to simple product
            foreach ($product->variations as $variation) {
                if ($variation->image) {
                    Storage::disk('public')->delete($variation->image);
                }
                $variation->delete();
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    private function updateVariations($product, $variations)
    {
        foreach ($variations as $index => $variationData) {
            if (empty($variationData['attributes']) || !is_array($variationData['attributes'])) {
                continue;
            }

            // Update existing or create new variation
            if (isset($variationData['id']) && $variationData['id']) {
                $variation = ProductVariation::find($variationData['id']);
                if (!$variation) continue;

                $variation->update([
                    'sku' => $variationData['sku'] ?? null,
                    'price' => $variationData['price'] ?? null,
                    'sale_price' => $variationData['sale_price'] ?? null,
                    'stock' => $variationData['stock'] ?? 0,
                    'sort_order' => $index,
                    'is_default' => isset($variationData['is_default']) && $variationData['is_default'] === '1',
                    'is_active' => !isset($variationData['is_active']) || $variationData['is_active'] === '1',
                ]);
            } else {
                $variation = ProductVariation::create([
                    'product_id' => $product->id,
                    'sku' => $variationData['sku'] ?? null,
                    'price' => $variationData['price'] ?? null,
                    'sale_price' => $variationData['sale_price'] ?? null,
                    'stock' => $variationData['stock'] ?? 0,
                    'sort_order' => $index,
                    'is_default' => isset($variationData['is_default']) && $variationData['is_default'] === '1',
                    'is_active' => !isset($variationData['is_active']) || $variationData['is_active'] === '1',
                ]);
            }

            // Handle variation image
            if (isset($variationData['image']) && $variationData['image']->isValid()) {
                if ($variation->image) {
                    Storage::disk('public')->delete($variation->image);
                }
                $variation->image = $variationData['image']->store('products/variations', 'public');
                $variation->save();
            }

            // Delete old attributes
            $variation->attributes()->delete();

            // Save new attributes
            foreach ($variationData['attributes'] as $attrName => $attrValue) {
                if (!empty($attrValue)) {
                    ProductVariationAttribute::create([
                        'variation_id' => $variation->id,
                        'attribute_name' => $attrName,
                        'attribute_value' => $attrValue,
                        'attribute_display' => $variationData['attribute_display'][$attrName] ?? null,
                        'sort_order' => 0,
                    ]);
                }
            }
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
