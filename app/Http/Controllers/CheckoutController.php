<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $stripeSecret = config('services.stripe.secret');
        if ($stripeSecret) {
            Stripe::setApiKey($stripeSecret);
        }
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                continue;
            }

            $product = Product::with('productCategory', 'variations.attributes')->find($item['product_id']);
            
            if (!$product || !$product->is_active) {
                continue;
            }

            $variation = null;
            $price = $product->current_price;
            
            // Handle variable products
            if ($product->isVariable() && isset($item['variation_id'])) {
                $variation = ProductVariation::with('attributes')->find($item['variation_id']);
                if ($variation && $variation->product_id == $product->id && $variation->is_active) {
                    $price = $variation->current_price;
                } else {
                    continue; // Invalid variation
                }
            } elseif ($product->isVariable() && !isset($item['variation_id'])) {
                continue; // Variable product without variation
            }

            // Check stock
            $availableStock = $variation ? $variation->stock : $product->stock;
            if ($availableStock < $item['quantity']) {
                continue; // Not enough stock
            }

            $qty = $item['quantity'];
            $products[] = [
                'product' => $product,
                'variation' => $variation,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $price * $qty
            ];
            $subtotal += $price * $qty;
        }

        if (empty($products)) {
            return redirect()->route('cart.index')->with('error', 'No valid products in cart.');
        }

        $tax = round($subtotal * 0.08, 2); // 8% tax
        $shipping = $subtotal >= 75 ? 0 : 10; // Free shipping over $75
        $total = round($subtotal + $tax + $shipping, 2);

        return view('checkout.index', compact('products', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        try {
            $validated = $request->validate([
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email|max:255',
                'shipping_phone' => 'nullable|string|max:20',
                'shipping_address_line1' => 'required|string|max:255',
                'shipping_address_line2' => 'nullable|string|max:255',
                'shipping_city' => 'required|string|max:255',
                'shipping_state' => 'required|string|max:255',
                'shipping_zipcode' => 'required|string|max:10',
                'shipping_country' => 'required|string|max:255',
                'notes' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors(),
                'message' => 'Please check the form and try again.'
            ], 422);
        }

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty.'], 400);
        }

        $products = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                continue;
            }

            $product = Product::with('variations')->find($item['product_id']);
            
            if (!$product || !$product->is_active) {
                continue;
            }

            $variation = null;
            $price = $product->current_price;
            
            // Handle variable products
            if ($product->isVariable() && isset($item['variation_id'])) {
                $variation = ProductVariation::with('attributes')->find($item['variation_id']);
                if ($variation && $variation->product_id == $product->id && $variation->is_active) {
                    $price = $variation->current_price;
                } else {
                    continue; // Invalid variation
                }
            } elseif ($product->isVariable() && !isset($item['variation_id'])) {
                continue; // Variable product without variation
            }

            // Check stock
            $availableStock = $variation ? $variation->stock : $product->stock;
            if ($availableStock < $item['quantity']) {
                return response()->json(['error' => "Not enough stock available for {$product->name}."], 400);
            }

            $qty = $item['quantity'];
            $products[] = [
                'product' => $product,
                'variation' => $variation,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $price * $qty
            ];
            $subtotal += $price * $qty;
        }

        if (empty($products)) {
            return response()->json(['error' => 'No valid products in cart.'], 400);
        }

        $tax = round($subtotal * 0.08, 2);
        $shipping = $subtotal >= 75 ? 0 : 10;
        $total = round($subtotal + $tax + $shipping, 2);

        // Validate minimum amount
        if ($total < 0.50) {
            return response()->json(['error' => 'Order total must be at least $0.50.'], 400);
        }

        try {
            // Validate Stripe configuration
            $stripeSecret = config('services.stripe.secret');
            if (!$stripeSecret) {
                \Log::error('Stripe secret key is not configured');
                return response()->json(['error' => 'Payment service is not configured. Please contact support.'], 500);
            }

            // Create payment intent
            $amountInCents = (int)round($total * 100);

            if ($amountInCents < 50) {
                return response()->json(['error' => 'Order amount is too small. Minimum order is $0.50.'], 400);
            }

            \Log::info('Creating payment intent', [
                'amount' => $amountInCents,
                'user_id' => Auth::id(),
                'total' => $total
            ]);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'metadata' => [
                    'user_id' => Auth::id(),
                    'type' => 'order',
                ],
            ]);

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'stripe',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'shipping_name' => $validated['shipping_name'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_phone' => $validated['shipping_phone'] ?? null,
                'shipping_address_line1' => $validated['shipping_address_line1'],
                'shipping_address_line2' => $validated['shipping_address_line2'] ?? null,
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zipcode' => $validated['shipping_zipcode'],
                'shipping_country' => substr($validated['shipping_country'] ?? 'US', 0, 2),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items and update stock
            foreach ($products as $item) {
                $productName = $item['product']->name;
                if ($item['variation']) {
                    // Load attributes if not already loaded
                    if (!$item['variation']->relationLoaded('attributes')) {
                        $item['variation']->load('attributes');
                    }
                    $variationName = $item['variation']->variation_name;
                    $productName .= ' - ' . $variationName;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $productName,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stock
                if ($item['variation']) {
                    $item['variation']->decrement('stock', $item['quantity']);
                } else {
                    $item['product']->decrement('stock', $item['quantity']);
                }
            }

            \Log::info('Payment intent created successfully', [
                'payment_intent_id' => $paymentIntent->id,
                'order_id' => $order->id,
                'amount' => $amountInCents
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'orderId' => $order->id,
            ]);

        } catch (\Stripe\Exception\CardException $e) {
            \Log::error('Stripe card error: ' . $e->getMessage());
            $errorMessage = $e->getError()->message ?? 'Card payment failed';
            return response()->json(['error' => $errorMessage], 400);
        } catch (\Stripe\Exception\RateLimitException $e) {
            \Log::error('Stripe rate limit error: ' . $e->getMessage());
            return response()->json(['error' => 'Too many requests. Please try again later.'], 429);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            \Log::error('Stripe invalid request: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payment request: ' . $e->getMessage()], 400);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            \Log::error('Stripe authentication error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment authentication failed. Please check your Stripe configuration.'], 401);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            \Log::error('Stripe API connection error: ' . $e->getMessage());
            return response()->json(['error' => 'Network error. Please check your internet connection and try again.'], 503);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment service error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function confirm(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            if (Auth::id() !== $order->user_id) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                abort(403);
            }

            if (!$order->stripe_payment_intent_id) {
                \Log::error('No payment intent found for order: ' . $orderId);
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'No payment intent found.'], 400);
                }
                return redirect()->back()->with('error', 'No payment intent found.');
            }

            $paymentIntent = PaymentIntent::retrieve($order->stripe_payment_intent_id);

            \Log::info('Payment intent status for order ' . $orderId . ': ' . $paymentIntent->status);

            // Check if payment succeeded or requires capture (both mean payment was successful)
            if (in_array($paymentIntent->status, ['succeeded', 'requires_capture'])) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                \Log::info('Order ' . $orderId . ' updated to paid status');

                // Clear cart
                session()->forget('cart');
                session()->save();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Payment confirmed successfully',
                        'redirect' => route('orders.confirmation', $order->id)
                    ]);
                }

                return redirect()->route('orders.confirmation', $order->id)
                    ->with('success', 'Payment successful!');
            }

            \Log::warning('Payment intent status is not succeeded for order ' . $orderId . ': ' . $paymentIntent->status);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Payment not completed. Status: ' . $paymentIntent->status], 400);
            }

            return redirect()->back()->with('error', 'Payment not completed. Status: ' . $paymentIntent->status);
        } catch (\Exception $e) {
            \Log::error('Payment confirmation error for order ' . $orderId . ': ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Payment error: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Payment error: ' . $e->getMessage());
        }
    }
}
