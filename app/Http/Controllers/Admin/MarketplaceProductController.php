<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceCategory;
use App\Models\MarketplaceProductVariation;
use App\Models\MarketplaceProductVariationAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketplaceProductController extends Controller
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
        $query = MarketplaceProduct::withTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->with('productCategory', 'variations')->orderBy('created_at', 'desc')->paginate(15);
        $categories = MarketplaceCategory::active()->orderBy('name')->get();

        return view('admin.marketplace-products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = MarketplaceCategory::active()->orderBy('name')->get();
        return view('admin.marketplace-products.create', compact('categories'));
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
            'sku' => 'nullable|string|unique:marketplace_products,sku',
            'stock' => 'required_if:product_type,simple|nullable|integer|min:0',
            'category_id' => 'nullable|exists:marketplace_categories,id',
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
            $data['image'] = $request->file('image')->store('marketplace', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('marketplace/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        }

        $product = MarketplaceProduct::create($data);

        if ($request->product_type === 'variable' && $request->has('variations')) {
            $this->saveVariations($product, $request->variations);
        }

        return redirect()->route('admin.marketplace-products.index')->with('success', 'Marketplace product created successfully!');
    }

    private function saveVariations($product, $variations)
    {
        foreach ($variations as $index => $variationData) {
            if (empty($variationData['attributes']) || !is_array($variationData['attributes'])) {
                continue;
            }

            $variation = MarketplaceProductVariation::create([
                'marketplace_product_id' => $product->id,
                'sku' => $variationData['sku'] ?? null,
                'price' => $variationData['price'] ?? null,
                'sale_price' => $variationData['sale_price'] ?? null,
                'stock' => $variationData['stock'] ?? 0,
                'sort_order' => $index,
                'is_default' => isset($variationData['is_default']) && $variationData['is_default'] === '1',
                'is_active' => !isset($variationData['is_active']) || $variationData['is_active'] === '1',
            ]);

            if (isset($variationData['image']) && $variationData['image']->isValid()) {
                $variation->image = $variationData['image']->store('marketplace/variations', 'public');
                $variation->save();
            }

            foreach ($variationData['attributes'] as $attrName => $attrValue) {
                if (!empty($attrValue)) {
                    MarketplaceProductVariationAttribute::create([
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
        $product = MarketplaceProduct::withTrashed()->with(['variations.attributes', 'productCategory'])->findOrFail($id);
        return view('admin.marketplace-products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = MarketplaceProduct::withTrashed()->with(['variations.attributes', 'productCategory'])->findOrFail($id);
        $categories = MarketplaceCategory::active()->orderBy('name')->get();
        return view('admin.marketplace-products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = MarketplaceProduct::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'product_type' => 'required|in:simple,variable',
            'price' => 'required_if:product_type,simple|nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:marketplace_products,sku,' . $id,
            'stock' => 'required_if:product_type,simple|nullable|integer|min:0',
            'category_id' => 'nullable|exists:marketplace_categories,id',
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
            $data['image'] = $request->file('image')->store('marketplace', 'public');
        }

        if ($request->hasFile('gallery')) {
            if ($product->gallery) {
                foreach ($product->gallery as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('marketplace/gallery', 'public');
            }
            $data['gallery'] = $gallery;
        } elseif ($request->has('gallery_remove')) {
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

        if ($request->product_type === 'variable') {
            if ($request->has('delete_variations')) {
                foreach ($request->delete_variations as $variationId) {
                    $variation = MarketplaceProductVariation::find($variationId);
                    if ($variation) {
                        if ($variation->image) {
                            Storage::disk('public')->delete($variation->image);
                        }
                        $variation->delete();
                    }
                }
            }

            if ($request->has('variations')) {
                $this->updateVariations($product, $request->variations);
            }
        } else {
            foreach ($product->variations as $variation) {
                if ($variation->image) {
                    Storage::disk('public')->delete($variation->image);
                }
                $variation->delete();
            }
        }

        return redirect()->route('admin.marketplace-products.index')->with('success', 'Marketplace product updated successfully!');
    }

    private function updateVariations($product, $variations)
    {
        foreach ($variations as $index => $variationData) {
            if (empty($variationData['attributes']) || !is_array($variationData['attributes'])) {
                continue;
            }

            if (isset($variationData['id']) && $variationData['id']) {
                $variation = MarketplaceProductVariation::find($variationData['id']);
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
                $variation = MarketplaceProductVariation::create([
                    'marketplace_product_id' => $product->id,
                    'sku' => $variationData['sku'] ?? null,
                    'price' => $variationData['price'] ?? null,
                    'sale_price' => $variationData['sale_price'] ?? null,
                    'stock' => $variationData['stock'] ?? 0,
                    'sort_order' => $index,
                    'is_default' => isset($variationData['is_default']) && $variationData['is_default'] === '1',
                    'is_active' => !isset($variationData['is_active']) || $variationData['is_active'] === '1',
                ]);
            }

            if (isset($variationData['image']) && $variationData['image']->isValid()) {
                if ($variation->image) {
                    Storage::disk('public')->delete($variation->image);
                }
                $variation->image = $variationData['image']->store('marketplace/variations', 'public');
                $variation->save();
            }

            $variation->attributes()->delete();

            foreach ($variationData['attributes'] as $attrName => $attrValue) {
                if (!empty($attrValue)) {
                    MarketplaceProductVariationAttribute::create([
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
        $product = MarketplaceProduct::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.marketplace-products.index')->with('success', 'Marketplace product deleted successfully!');
    }
}
