<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 24px;">
    <h2>Booking Cancelled</h2>
    <p>Booking #{{ $booking->id }} was cancelled by {{ $cancelledBy }}.</p>
    <p>DJ: {{ $booking->dj->stage_name ?? 'N/A' }} | Customer: {{ $booking->user->name ?? 'N/A' }}</p>
    <p>Reason: {{ $booking->cancellation_reason ?? 'Not provided' }}</p>
</body>
</html>
