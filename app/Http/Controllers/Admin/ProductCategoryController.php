<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
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

    public function index()
    {
        $categories = ProductCategory::with('parent', 'children', 'products')
            ->rootCategories()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.product-categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = ProductCategory::rootCategories()->active()->orderBy('name')->get();
        return view('admin.product-categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'parent_id', 'sort_order', 'is_active']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-categories', 'public');
        }

        ProductCategory::create($data);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function show($id)
    {
        $category = ProductCategory::with('parent', 'children', 'products')->findOrFail($id);
        return view('admin.product-categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        $parentCategories = ProductCategory::rootCategories()
            ->where('id', '!=', $id)
            ->active()
            ->orderBy('name')
            ->get();
        return view('admin.product-categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id|not_in:' . $id,
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'parent_id', 'sort_order', 'is_active']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('product-categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with existing products. Please reassign products first.');
        }

        // Check if category has subcategories
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with subcategories. Please delete or move subcategories first.');
        }

        $category->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
