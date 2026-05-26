<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background: #161616; color: #fff; padding: 24px;">
    <h2 style="color: #FFD900;">New Booking Request</h2>
    <p>Hello {{ $bookingRequest->dj->stage_name }},</p>
    <p>A customer has requested you for an event. Details:</p>
    <ul>
        <li><strong>Client:</strong> {{ $bookingRequest->client_name }}</li>
        <li><strong>Event Date:</strong> {{ $bookingRequest->event_date->format('M d, Y') }}</li>
        <li><strong>Event Type:</strong> {{ $bookingRequest->event_type }}</li>
        <li><strong>Venue:</strong> {{ $bookingRequest->venue_name }} ({{ $bookingRequest->venue_type }})</li>
        <li><strong>Address:</strong> {{ $bookingRequest->venue_address }}, {{ $bookingRequest->city }}, {{ $bookingRequest->zipcode }}</li>
        <li><strong>Your Rate:</strong> ${{ number_format($bookingRequest->dj_amount, 2) }}</li>
        @if($bookingRequest->rush_guarantee)
            <li><strong>Rush Response (6hr):</strong> Yes (+${{ number_format($bookingRequest->rush_fee, 2) }})</li>
        @endif
    </ul>
    <p>This request expires: <strong>{{ $bookingRequest->expires_at->format('M d, Y g:i A') }}</strong></p>
    <p style="margin-top: 24px;">
        <a href="{{ $bookingRequest->respondUrl('yes') }}" style="background:#22c55e;color:#fff;padding:12px 24px;text-decoration:none;font-weight:bold;margin-right:8px;">YES — Accept</a>
        <a href="{{ $bookingRequest->respondUrl('no') }}" style="background:#ef4444;color:#fff;padding:12px 24px;text-decoration:none;font-weight:bold;">NO — Decline</a>
    </p>
</body>
</html>
