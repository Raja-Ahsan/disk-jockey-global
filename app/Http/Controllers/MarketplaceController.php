<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceProduct;
use App\Models\MarketplaceCategory;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketplaceProduct::active()->with('orderItems', 'productCategory', 'variations');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(12);
        $categories = MarketplaceCategory::active()->rootCategories()->with('children')->orderBy('name')->get();

        return view('marketplace.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = MarketplaceProduct::active()->with('productCategory', 'variations.attributes')->findOrFail($id);
        $relatedProducts = MarketplaceProduct::active()
            ->where('id', '!=', $id)
            ->where('category_id', $product->category_id)
            ->with('variations')
            ->limit(4)
            ->get();

        return view('marketplace.show', compact('product', 'relatedProducts'));
    }
}
