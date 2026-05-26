<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background: #161616; color: #fff; padding: 24px;">
    <h2 style="color: #FFD900;">Booking Update</h2>
    <p>Hello {{ $bookingRequest->client_name }},</p>

    @if($type === 'submitted')
        <p>Your booking request for <strong>{{ $bookingRequest->dj->stage_name }}</strong> on {{ $bookingRequest->event_date->format('M d, Y') }} has been sent.</p>
        <p>The DJ/MC has until <strong>{{ $bookingRequest->expires_at->format('M d, Y g:i A') }}</strong> to respond, or the request will expire automatically.</p>
    @elseif($type === 'accepted')
        <p><strong>{{ $bookingRequest->dj->stage_name }}</strong> accepted your booking!</p>
        <p>Please complete payment: DJ fee ${{ number_format($bookingRequest->dj_amount, 2) }} + non-refundable booking fee ${{ number_format($bookingRequest->booking_fee, 2) }} = <strong>${{ number_format($bookingRequest->total_amount, 2) }}</strong></p>
    @elseif($type === 'rejected')
        <p>Unfortunately, <strong>{{ $bookingRequest->dj->stage_name }}</strong> declined your request. Please search again and select another DJ/MC.</p>
        <p><a href="{{ route('browse') }}" style="background:#FFD900;color:#333;padding:12px 24px;text-decoration:none;font-weight:bold;">Browse DJs</a></p>
    @else
        <p>Your booking request will expire on {{ $bookingRequest->expires_at->format('M d, Y g:i A') }} if the DJ does not respond.</p>
    @endif
</body>
</html>
