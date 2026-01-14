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
            $amount = $request->type === 'deposit' ? $booking->deposit_amount : $booking->total_amount;
            $amountInCents = (int)($amount * 100);

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
            $paymentIntent = PaymentIntent::retrieve($booking->stripe_payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
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

                return redirect()->route('bookings.show', $booking->id)
                    ->with('success', 'Payment successful!');
            }

            return redirect()->back()->with('error', 'Payment not completed.');
        } catch (\Exception $e) {
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
