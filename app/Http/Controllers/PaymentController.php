<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        try {
            // Ensure booking has valid total amount
            if (!$booking->total_amount || $booking->total_amount <= 0) {
                return response()->json([
                    'error' => 'This booking has an invalid total amount. Please contact support.'
                ], 400);
            }

            // Determine the amount to charge
            if ($request->type === 'deposit') {
                // If deposit_amount is null or 0, calculate 30% of total
                if (!$booking->deposit_amount || $booking->deposit_amount <= 0) {
                    $amount = round($booking->total_amount * 0.3, 2);
                    // Update the booking with the calculated deposit
                    $booking->update(['deposit_amount' => $amount]);
                } else {
                    $amount = $booking->deposit_amount;
                }
            } else {
                // For remaining balance (when payment_status is 'partial')
                $depositPaid = $booking->deposit_amount ?? 0;
                $amount = round($booking->total_amount - $depositPaid, 2);
            }

            // Ensure amount is valid and at least $0.01
            if ($amount === null || $amount <= 0) {
                return response()->json([
                    'error' => 'Invalid payment amount. Please contact support.'
                ], 400);
            }

            // Convert to cents with proper rounding to avoid floating point issues
            $amountInCents = (int)round($amount * 100);

            // Stripe requires at least 1 cent
            if ($amountInCents < 1) {
                return response()->json([
                    'error' => 'Payment amount must be at least $0.01.'
                ], 400);
            }

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => Auth::id(),
                    'type' => $request->type ?? 'full',
                ],
            ]);

            $booking->update([
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'amount' => $amount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmPayment(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        try {
            if (!$booking->stripe_payment_intent_id) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'No payment intent found'], 400);
                }
                return redirect()->back()->with('error', 'No payment intent found.');
            }

            $paymentIntent = PaymentIntent::retrieve($booking->stripe_payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                // Determine if this was a deposit or full payment
                $depositAmount = $booking->deposit_amount ?? 0;
                $isDeposit = $paymentIntent->amount === (int)round($depositAmount * 100) && $depositAmount > 0;
                
                $paymentStatus = $isDeposit ? 'partial' : 'paid';

                $booking->update([
                    'payment_status' => $paymentStatus,
                ]);

                if ($paymentStatus === 'paid') {
                    $booking->update([
                        'booking_status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Payment confirmed successfully!',
                        'payment_status' => $paymentStatus
                    ]);
                }

                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Payment successful!');
            }

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Payment not completed'], 400);
            }

            return redirect()->back()->with('error', 'Payment not completed.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Payment error: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $booking = Booking::where('stripe_payment_intent_id', $paymentIntent->id)->first();

            if ($booking) {
                $paymentStatus = $paymentIntent->amount === (int)($booking->deposit_amount * 100) ? 'partial' : 'paid';

                $booking->update([
                    'payment_status' => $paymentStatus,
                ]);

                if ($paymentStatus === 'paid') {
                    $booking->update([
                        'booking_status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['received' => true]);
    }
}
