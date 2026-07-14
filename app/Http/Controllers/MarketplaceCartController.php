<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceProduct;
use App\Models\MarketplaceProductVariation;
use Illuminate\Http\Request;

class MarketplaceCartController extends Controller
{
    public function index()
    {
        $cart = session()->get('marketplace_cart', []);
        $products = [];
        $total = 0;
        $updatedCart = [];
        $removedItems = false;
        $debugMessages = [];

        \Log::info('Marketplace cart contents:', ['cart' => $cart, 'count' => count($cart)]);

        if (count($cart) > 0) {
            $debugMessages[] = "Found " . count($cart) . " item(s) in cart session";
        } else {
            $debugMessages[] = "Cart session is empty";
        }

        foreach ($cart as $key => $item) {
            if (!isset($item['product_id']) || !isset($item['quantity']) || $item['quantity'] < 1) {
                $removedItems = true;
                $debugMessages[] = "Skipped item with key {$key}: Invalid structure";
                continue;
            }

            $product = MarketplaceProduct::with('productCategory', 'variations.attributes')->find($item['product_id']);

            if (!$product) {
                $removedItems = true;
                $debugMessages[] = "Skipped item with key {$key}: Product ID {$item['product_id']} not found";
                continue;
            }

            if (!$product->is_active) {
                $removedItems = true;
                $debugMessages[] = "Skipped item with key {$key}: Product {$product->name} is inactive";
                continue;
            }

            $variation = null;
            $price = $product->current_price;

            if ($product->isVariable()) {
                if (!isset($item['variation_id'])) {
                    $removedItems = true;
                    $debugMessages[] = "Skipped item with key {$key}: Variable product without variation_id";
                    continue;
                }

                $variation = MarketplaceProductVariation::with('attributes')->find($item['variation_id']);
                if (!$variation) {
                    $removedItems = true;
                    $debugMessages[] = "Skipped item with key {$key}: Variation ID {$item['variation_id']} not found";
                    continue;
                }

                if ($variation->marketplace_product_id != $product->id) {
                    $removedItems = true;
                    $debugMessages[] = "Skipped item with key {$key}: Variation doesn't belong to product";
                    continue;
                }

                if (!$variation->is_active) {
                    $removedItems = true;
                    $debugMessages[] = "Skipped item with key {$key}: Variation is inactive";
                    continue;
                }

                $price = $variation->current_price;
            }

            if ($variation) {
                if ($variation->stock < $item['quantity']) {
                    $debugMessages[] = "Warning: Item {$key} quantity ({$item['quantity']}) exceeds stock ({$variation->stock})";
                }
            } else {
                if ($product->stock < $item['quantity']) {
                    $debugMessages[] = "Warning: Item {$key} quantity ({$item['quantity']}) exceeds stock ({$product->stock})";
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
            $updatedCart[$key] = $item;
        }

        if ($removedItems && count($updatedCart) !== count($cart)) {
            session()->put('marketplace_cart', $updatedCart);
            session()->save();

            if (count($updatedCart) === 0) {
                session()->forget('marketplace_cart');
            }
        }

        \Log::info('Marketplace cart processing results:', [
            'original_count' => count($cart),
            'products_count' => count($products),
            'updated_cart_count' => count($updatedCart),
            'total' => $total,
            'debug_messages' => $debugMessages
        ]);

        return view('marketplace.cart', compact('products', 'total', 'debugMessages'));
    }

    public function add(Request $request, $id)
    {
        try {
            $product = MarketplaceProduct::with('variations')->findOrFail($id);

            if (!$product->is_active) {
                return redirect()->back()->with('error', 'Product is not available.');
            }

            $cart = session()->get('marketplace_cart', []);
            $quantity = (int) $request->input('quantity', 1);
            $variationId = $request->input('variation_id');

            if ($quantity < 1) {
                return redirect()->back()->with('error', 'Quantity must be at least 1.');
            }

            if ($product->isVariable()) {
                if (!$variationId) {
                    return redirect()->back()->with('error', 'Please select a variation.');
                }

                $variation = MarketplaceProductVariation::where('id', $variationId)
                    ->where('marketplace_product_id', $id)
                    ->where('is_active', true)
                    ->first();

                if (!$variation) {
                    return redirect()->back()->with('error', 'Selected variation is not available.');
                }

                if ($variation->stock < $quantity) {
                    return redirect()->back()->with('error', 'Not enough stock available for this variation.');
                }

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
                        'variation_id' => (int) $variationId
                    ];
                }
            } else {
                if (!$product->has_stock) {
                    return redirect()->back()->with('error', 'Product is out of stock.');
                }

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

            session()->put('marketplace_cart', $cart);
            session()->save();

            return redirect()->back()->with('success', 'Product added to cart successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $key)
    {
        $cart = session()->get('marketplace_cart', []);

        if (!isset($cart[$key])) {
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        $item = $cart[$key];
        $product = MarketplaceProduct::findOrFail($item['product_id']);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            return $this->remove($key);
        }

        if ($product->isVariable() && isset($item['variation_id'])) {
            $variation = MarketplaceProductVariation::find($item['variation_id']);
            if (!$variation || $quantity > $variation->stock) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }
        } else {
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }
        }

        $cart[$key]['quantity'] = $quantity;
        session()->put('marketplace_cart', $cart);
        session()->save();

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove($key)
    {
        $cart = session()->get('marketplace_cart', []);
        unset($cart[$key]);
        session()->put('marketplace_cart', $cart);
        session()->save();

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        session()->forget('marketplace_cart');
        session()->save();
        return redirect()->route('marketplace.cart.index')->with('success', 'Cart cleared!');
    }
}
