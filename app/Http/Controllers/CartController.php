<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $variation = null;
                $price = $product->current_price;
                
                // Handle variable products
                if ($product->isVariable() && isset($item['variation_id'])) {
                    $variation = ProductVariation::find($item['variation_id']);
                    if ($variation) {
                        $price = $variation->current_price;
                    }
                }

                $subtotal = $price * $item['quantity'];
                $products[] = [
                    'cart_key' => $key,
                    'product' => $product,
                    'variation' => $variation,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
                $total += $subtotal;
            }
        }

        return view('cart.index', compact('products', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->is_active || !$product->isInStock()) {
            return redirect()->back()->with('error', 'Product is not available.');
        }

        $cart = session()->get('cart', []);
        $quantity = (int)$request->input('quantity', 1);
        $variationId = $request->input('variation_id');

        // For variable products, validate variation
        if ($product->isVariable()) {
            if (!$variationId) {
                return redirect()->back()->with('error', 'Please select a variation.');
            }

            $variation = ProductVariation::where('id', $variationId)
                ->where('product_id', $id)
                ->where('is_active', true)
                ->firstOrFail();

            if ($variation->stock < $quantity) {
                return redirect()->back()->with('error', 'Not enough stock available for this variation.');
            }

            // Use variation ID as cart key for variable products
            $cartKey = $id . '_' . $variationId;
            
            if (isset($cart[$cartKey])) {
                $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
                if ($newQuantity > $variation->stock) {
                    return redirect()->back()->with('error', 'Not enough stock available.');
                }
                $cart[$cartKey]['quantity'] = $newQuantity;
            } else {
                $cart[$cartKey] = [
                    'quantity' => $quantity,
                    'product_id' => $id,
                    'variation_id' => $variationId
                ];
            }
        } else {
            // Simple product
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }

            if (isset($cart[$id])) {
                $newQuantity = $cart[$id]['quantity'] + $quantity;
                if ($newQuantity > $product->stock) {
                    return redirect()->back()->with('error', 'Not enough stock available.');
                }
                $cart[$id]['quantity'] = $newQuantity;
            } else {
                $cart[$id] = [
                    'quantity' => $quantity,
                    'product_id' => $id
                ];
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        
        if (!isset($cart[$key])) {
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        $item = $cart[$key];
        $product = Product::findOrFail($item['product_id']);
        $quantity = (int)$request->input('quantity', 1);

        if ($quantity <= 0) {
            return $this->remove($key);
        }

        // Check stock
        if ($product->isVariable() && isset($item['variation_id'])) {
            $variation = ProductVariation::find($item['variation_id']);
            if (!$variation || $quantity > $variation->stock) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }
        } else {
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }
        }

        $cart[$key]['quantity'] = $quantity;
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }
}
