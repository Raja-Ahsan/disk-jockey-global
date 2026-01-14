<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product && $product->is_active && $product->isInStock()) {
                $qty = $item['quantity'];
                $price = $product->current_price;
                $products[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'subtotal' => $price * $qty
                ];
                $subtotal += $price * $qty;
            }
        }

        if (empty($products)) {
            return redirect()->route('cart.index')->with('error', 'No valid products in cart.');
        }

        $tax = $subtotal * 0.08; // 8% tax
        $shipping = $subtotal >= 75 ? 0 : 10; // Free shipping over $75
        $total = $subtotal + $tax + $shipping;

        return view('checkout.index', compact('products', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address_line1' => 'required|string|max:255',
            'shipping_address_line2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_zipcode' => 'required|string|max:10',
            'shipping_country' => 'required|string|max:2',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product && $product->is_active && $product->stock >= $item['quantity']) {
                $qty = $item['quantity'];
                $price = $product->current_price;
                $products[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'price' => $price,
                    'subtotal' => $price * $qty
                ];
                $subtotal += $price * $qty;
            }
        }

        if (empty($products)) {
            return redirect()->route('cart.index')->with('error', 'No valid products in cart.');
        }

        $tax = round($subtotal * 0.08, 2);
        $shipping = $subtotal >= 75 ? 0 : 10;
        $total = round($subtotal + $tax + $shipping, 2);

        try {
            // Create payment intent
            $amountInCents = (int)round($total * 100);

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
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address_line1' => $request->shipping_address_line1,
                'shipping_address_line2' => $request->shipping_address_line2,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zipcode' => $request->shipping_zipcode,
                'shipping_country' => $request->shipping_country ?? 'US',
                'notes' => $request->notes,
            ]);

            // Create order items and update stock
            foreach ($products as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update product stock
                $item['product']->decrement('stock', $item['quantity']);
            }

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'orderId' => $order->id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirm(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        try {
            if (!$order->stripe_payment_intent_id) {
                return redirect()->back()->with('error', 'No payment intent found.');
            }

            $paymentIntent = PaymentIntent::retrieve($order->stripe_payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);

                // Clear cart
                session()->forget('cart');

                return redirect()->route('orders.confirmation', $order->id)
                    ->with('success', 'Payment successful!');
            }

            return redirect()->back()->with('error', 'Payment not completed.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment error: ' . $e->getMessage());
        }
    }
}
