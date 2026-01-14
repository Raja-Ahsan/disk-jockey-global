<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DJ;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bookings = Booking::with('dj.user', 'event', 'user')
            ->where('user_id', Auth::id())
            ->orderBy('booking_date', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $dj = DJ::findOrFail($request->dj_id);
        return view('bookings.create', compact('dj'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dj_id' => 'required|exists:djs,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'venue_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'special_requests' => 'nullable|string',
        ]);

        $dj = DJ::findOrFail($request->dj_id);

        // Calculate total amount based on hours and hourly rate
        $start = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->booking_date . ' ' . $request->end_time);
        $hours = $end->diffInHours($start);
        $totalAmount = $hours * $dj->hourly_rate;
        $depositAmount = $totalAmount * 0.3; // 30% deposit

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'dj_id' => $request->dj_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'venue_address' => $request->venue_address,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'total_amount' => $totalAmount,
            'deposit_amount' => $depositAmount,
            'special_requests' => $request->special_requests,
            'booking_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Update DJ booking count
        $dj->increment('total_bookings');

        return redirect()->route('bookings.show', $booking->id)->with('success', 'Booking created! Please complete payment to confirm.');
    }

    public function show($id)
    {
        $booking = Booking::with('dj.user', 'event', 'user', 'review')
            ->findOrFail($id);

        if (Auth::id() !== $booking->user_id && Auth::id() !== $booking->dj->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'booking_date' => 'sometimes|date|after_or_equal:today',
            'start_time' => 'sometimes|required',
            'end_time' => 'sometimes|required|after:start_time',
            'venue_address' => 'sometimes|required|string|max:255',
            'special_requests' => 'nullable|string',
        ]);

        $booking->update($request->only([
            'booking_date', 'start_time', 'end_time', 'venue_address', 'special_requests'
        ]));

        return redirect()->route('bookings.show', $booking->id)->with('success', 'Booking updated successfully!');
    }

    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->dj->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->update([
            'booking_status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Booking confirmed!');
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->user_id && Auth::id() !== $booking->dj->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->update([
            'booking_status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->back()->with('success', 'Booking cancelled.');
    }

    public function complete($id)
    {
        $booking = Booking::findOrFail($id);

        if (Auth::id() !== $booking->dj->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->update([
            'booking_status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Booking marked as completed!');
    }
}
