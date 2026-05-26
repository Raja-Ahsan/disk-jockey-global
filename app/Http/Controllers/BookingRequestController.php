<?php

namespace App\Http\Controllers;

use App\Mail\AdminBookingCancelledMail;
use App\Mail\CustomerBookingStatusMail;
use App\Mail\DjBookingRequestMail;
use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\DJ;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingRequestController extends Controller
{
    public function __construct(
        protected SmsService $sms
    ) {
        $this->middleware('auth')->except(['respond']);
    }

    public function create(Request $request)
    {
        $request->validate(['dj_id' => 'required|exists:djs,id']);

        $dj = DJ::findOrFail($request->dj_id);
        $bookingSearch = session('booking_search');

        if (! $bookingSearch) {
            return redirect()->route('home')->withErrors([
                'error' => 'Please search for a DJ from the home page first.',
            ]);
        }

        $needsDetailsForm = ! $this->isBookingDetailsComplete($bookingSearch);

        return view('booking-requests.create', compact('dj', 'bookingSearch', 'needsDetailsForm'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dj_id' => 'required|exists:djs,id',
            'client_name' => 'required|string|max:255',
            'event_date' => 'required|date|after_or_equal:today',
            'event_type' => 'required|string|max:255',
            'venue_type' => 'required|in:club,hall,house,other',
            'venue_type_other' => 'required_if:venue_type,other|nullable|string|max:255',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'zipcode' => 'required|string|max:20',
            'rush_guarantee' => 'nullable|boolean',
        ]);

        $venueTypeLabel = $validated['venue_type'] === 'other'
            ? ($validated['venue_type_other'] ?? 'Other')
            : ucfirst($validated['venue_type']);

        $search = array_merge(session('booking_search', []), [
            'client_name' => $validated['client_name'],
            'event_date' => $validated['event_date'],
            'event_type' => $validated['event_type'],
            'venue_type' => $validated['venue_type'],
            'venue_type_other' => $validated['venue_type_other'] ?? null,
            'venue_type_label' => $venueTypeLabel,
            'venue_name' => $validated['venue_name'],
            'venue_address' => $validated['venue_address'],
            'city' => $validated['city'],
            'state' => $validated['state'] ?? null,
            'zipcode' => $validated['zipcode'],
            'rush_guarantee' => $request->boolean('rush_guarantee'),
            'dj_id' => $validated['dj_id'],
        ]);

        unset($search['search_by_name_only']);
        session(['booking_search' => $search]);

        if (Auth::user()->name !== $validated['client_name']) {
            Auth::user()->update(['name' => $validated['client_name']]);
        }

        $dj = DJ::findOrFail($validated['dj_id']);
        $rush = ! empty($search['rush_guarantee']);
        $rushFee = $rush ? 50.00 : 0.00;
        $bookingFee = 100.00;
        $djAmount = (float) ($dj->hourly_rate ?? 0);
        $totalAmount = $djAmount + $bookingFee + $rushFee;

        $bookingRequest = BookingRequest::create([
            'user_id' => Auth::id(),
            'dj_id' => $dj->id,
            'client_name' => $search['client_name'],
            'event_type' => $search['event_type'],
            'event_date' => $search['event_date'],
            'venue_type' => $search['venue_type'],
            'venue_type_other' => $search['venue_type_other'] ?? null,
            'venue_name' => $search['venue_name'],
            'venue_address' => $search['venue_address'],
            'city' => $search['city'],
            'state' => $search['state'] ?? 'N/A',
            'zipcode' => $search['zipcode'],
            'budget_min' => $search['budget_min'] ?? null,
            'budget_max' => $search['budget_max'] ?? null,
            'rush_guarantee' => $rush,
            'rush_fee' => $rushFee,
            'booking_fee' => $bookingFee,
            'dj_amount' => $djAmount,
            'total_amount' => $totalAmount,
            'special_requests' => $this->formatSpecialRequests($search),
            'status' => 'pending',
            'dj_response' => 'pending',
        ]);

        $this->notifyDj($bookingRequest);
        $this->notifyCustomerSubmitted($bookingRequest);

        return redirect()->route('booking-requests.show', $bookingRequest->id)
            ->with('success', 'Booking request sent to the DJ/MC. They have ' . ($rush ? '6 hours' : '48 hours') . ' to respond.');
    }

    public function show($id)
    {
        $bookingRequest = BookingRequest::with('dj.user', 'user')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('booking-requests.show', compact('bookingRequest'));
    }

    public function respond(Request $request, string $token)
    {
        $request->validate(['answer' => 'required|in:yes,no']);

        $bookingRequest = BookingRequest::with('dj.user', 'user')
            ->where('response_token', $token)
            ->firstOrFail();

        if ($bookingRequest->isExpired()) {
            $bookingRequest->update(['status' => 'expired']);

            return view('booking-requests.respond', [
                'bookingRequest' => $bookingRequest,
                'message' => 'This booking request has expired.',
                'success' => false,
            ]);
        }

        if ($bookingRequest->dj_response !== 'pending') {
            return view('booking-requests.respond', [
                'bookingRequest' => $bookingRequest,
                'message' => 'This request was already responded to.',
                'success' => false,
            ]);
        }

        $answer = $request->get('answer');

        if ($answer === 'yes') {
            return $this->acceptRequest($bookingRequest);
        }

        return $this->rejectRequest($bookingRequest);
    }

    public function cancel(Request $request, $id)
    {
        $bookingRequest = BookingRequest::findOrFail($id);

        if (Auth::id() !== $bookingRequest->user_id
            && Auth::id() !== $bookingRequest->dj->user_id
            && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $bookingRequest->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        $this->notifyAdminCancellation($bookingRequest, Auth::user()->name);

        return redirect()->back()->with('success', 'Booking request cancelled.');
    }

    protected function isBookingDetailsComplete(?array $search): bool
    {
        if (! $search || empty($search['event_date'])) {
            return false;
        }

        foreach (['event_type', 'venue_type', 'venue_name', 'venue_address', 'city', 'zipcode'] as $field) {
            if (empty($search[$field])) {
                return false;
            }
        }

        if ($search['venue_type'] === 'other' && empty($search['venue_type_other'])) {
            return false;
        }

        return true;
    }

    protected function acceptRequest(BookingRequest $bookingRequest)
    {
        $booking = Booking::create([
            'user_id' => $bookingRequest->user_id,
            'dj_id' => $bookingRequest->dj_id,
            'booking_date' => $bookingRequest->event_date,
            'start_time' => '18:00:00',
            'end_time' => '22:00:00',
            'venue_address' => $bookingRequest->venue_address,
            'city' => $bookingRequest->city,
            'state' => $bookingRequest->state ?? 'N/A',
            'zipcode' => $bookingRequest->zipcode,
            'total_amount' => $bookingRequest->total_amount,
            'deposit_amount' => $bookingRequest->total_amount,
            'special_requests' => $bookingRequest->special_requests,
            'booking_status' => 'pending_payment',
            'payment_status' => 'pending',
        ]);

        $bookingRequest->update([
            'dj_response' => 'yes',
            'status' => 'accepted',
            'dj_responded_at' => now(),
            'booking_id' => $booking->id,
        ]);

        Mail::to($bookingRequest->user->email)->send(new CustomerBookingStatusMail($bookingRequest, 'accepted'));
        $this->sms->send(
            $bookingRequest->user->phone ?? '',
            "{$bookingRequest->dj->stage_name} accepted your booking! Pay now: " . route('bookings.show', $booking->id)
        );

        return view('booking-requests.respond', [
            'bookingRequest' => $bookingRequest,
            'message' => 'Thank you! The customer has been notified to complete payment.',
            'success' => true,
        ]);
    }

    protected function rejectRequest(BookingRequest $bookingRequest)
    {
        $bookingRequest->update([
            'dj_response' => 'no',
            'status' => 'rejected',
            'dj_responded_at' => now(),
        ]);

        Mail::to($bookingRequest->user->email)->send(new CustomerBookingStatusMail($bookingRequest, 'rejected'));
        $this->sms->send(
            $bookingRequest->user->phone ?? '',
            "{$bookingRequest->dj->stage_name} declined your booking. Please choose another DJ on Disk Jockey Global."
        );

        return view('booking-requests.respond', [
            'bookingRequest' => $bookingRequest,
            'message' => 'The customer has been notified to select another DJ/MC.',
            'success' => true,
        ]);
    }

    protected function notifyDj(BookingRequest $bookingRequest): void
    {
        $bookingRequest->load('dj.user');

        $djEmail = $bookingRequest->dj->user->email ?? null;
        $djPhone = $bookingRequest->dj->phone ?? $bookingRequest->dj->user->phone ?? null;

        if ($djEmail) {
            Mail::to($djEmail)->send(new DjBookingRequestMail($bookingRequest));
        }

        $expires = $bookingRequest->expires_at->format('M d, g:i A');
        $this->sms->send(
            $djPhone ?? '',
            "New booking from {$bookingRequest->client_name} for {$bookingRequest->event_date->format('M d')}. Respond by {$expires}. YES: {$bookingRequest->respondUrl('yes')} NO: {$bookingRequest->respondUrl('no')}"
        );
    }

    protected function notifyCustomerSubmitted(BookingRequest $bookingRequest): void
    {
        Mail::to($bookingRequest->user->email)->send(new CustomerBookingStatusMail($bookingRequest, 'submitted'));
        Mail::to($bookingRequest->user->email)->send(new CustomerBookingStatusMail($bookingRequest, 'expiry_notice'));

        $window = $bookingRequest->rush_guarantee ? '6 hours' : '48 hours';
        $this->sms->send(
            $bookingRequest->user->phone ?? '',
            "Your booking request to {$bookingRequest->dj->stage_name} was sent. The DJ has {$window} to respond."
        );
    }

    protected function notifyAdminCancellation(BookingRequest $bookingRequest, string $cancelledBy): void
    {
        $admin = User::where('role', 'admin')->first();
        if ($admin && $bookingRequest->booking_id) {
            $booking = Booking::with('dj', 'user')->find($bookingRequest->booking_id);
            if ($booking) {
                Mail::to($admin->email)->send(new AdminBookingCancelledMail($booking, $cancelledBy));
            }
        }
    }

    protected function formatSpecialRequests(array $search): string
    {
        $parts = [
            'Event: ' . ($search['event_type'] ?? ''),
            'Venue: ' . ($search['venue_name'] ?? '') . ' (' . ($search['venue_type_label'] ?? '') . ')',
        ];

        return implode(' | ', $parts);
    }
}
