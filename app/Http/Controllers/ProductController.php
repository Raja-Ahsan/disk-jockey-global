<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('orderItems', 'productCategory');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(12);
        $categories = ProductCategory::active()->rootCategories()->with('children')->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::active()->with('productCategory')->findOrFail($id);
        $relatedProducts = Product::active()
            ->where('id', '!=', $id)
            ->where('category_id', $product->category_id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
