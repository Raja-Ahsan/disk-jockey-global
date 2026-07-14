<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background: #161616; color: #fff; padding: 24px;">
    <h2 style="color: #FFD900;">New Plan My Event Request</h2>
    <p>A customer submitted event details for planning assistance.</p>
    <ul>
        <li><strong>Client:</strong> {{ $planMyEventRequest->client_name }}</li>
        @if($planMyEventRequest->email)
            <li><strong>Email:</strong> {{ $planMyEventRequest->email }}</li>
        @endif
        @if($planMyEventRequest->phone)
            <li><strong>Phone:</strong> {{ $planMyEventRequest->phone }}</li>
        @endif
        <li><strong>Event Date:</strong> {{ $planMyEventRequest->event_date->format('M d, Y') }}</li>
        <li><strong>Event Type:</strong> {{ $planMyEventRequest->event_type }}</li>
        <li><strong>City / Zip:</strong> {{ $planMyEventRequest->city }}, {{ $planMyEventRequest->zipcode }}</li>
        <li><strong>Venue:</strong> {{ $planMyEventRequest->venue_name }} ({{ $planMyEventRequest->venue_type_label }})</li>
        <li><strong>Address:</strong> {{ $planMyEventRequest->venue_address }}</li>
        @if($planMyEventRequest->budget_range)
            <li><strong>Budget:</strong> {{ $planMyEventRequest->budget_range }}
                @if($planMyEventRequest->budget_min || $planMyEventRequest->budget_max)
                    (${{ number_format($planMyEventRequest->budget_min, 0) }} – ${{ number_format($planMyEventRequest->budget_max, 0) }})
                @endif
            </li>
        @endif
        @if($planMyEventRequest->dj_name)
            <li><strong>Preferred DJ / MC:</strong> {{ $planMyEventRequest->dj_name }}</li>
        @endif
        @if($planMyEventRequest->use_near_me)
            <li><strong>Near Me:</strong> Yes</li>
        @endif
        @if($planMyEventRequest->rush_guarantee)
            <li><strong>Rush Response (6hr):</strong> Yes</li>
        @endif
        @if($planMyEventRequest->special_requests)
            <li><strong>Special Requests:</strong> {{ $planMyEventRequest->special_requests }}</li>
        @endif
    </ul>
    <p style="margin-top: 24px; color: #999;">Request #{{ $planMyEventRequest->id }} · Status: {{ ucfirst(str_replace('_', ' ', $planMyEventRequest->status)) }}</p>
</body>
</html>
