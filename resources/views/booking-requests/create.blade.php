@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-20 px-6">
    <div class="container mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold text-white mb-2 text-center">Request <span class="text-[#FFD900]">{{ $dj->stage_name }}</span></h1>
        <p class="text-gray-400 text-center mb-8">
            @if($needsDetailsForm)
            Complete your event details below, then send the request to the DJ/MC.
            @else
            Review your event details before sending the request to the DJ/MC.
            @endif
        </p>

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/40 rounded-xl">
            <ul class="text-red-400 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('booking-requests.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="dj_id" value="{{ $dj->id }}">

            @if(!$needsDetailsForm)
            <input type="hidden" name="client_name" value="{{ $bookingSearch['client_name'] ?? auth()->user()->name }}">
            <input type="hidden" name="event_date" value="{{ $bookingSearch['event_date'] }}">
            <input type="hidden" name="event_type" value="{{ $bookingSearch['event_type'] }}">
            <input type="hidden" name="venue_type" value="{{ $bookingSearch['venue_type'] }}">
            <input type="hidden" name="venue_type_other" value="{{ $bookingSearch['venue_type_other'] ?? '' }}">
            <input type="hidden" name="venue_name" value="{{ $bookingSearch['venue_name'] }}">
            <input type="hidden" name="venue_address" value="{{ $bookingSearch['venue_address'] }}">
            <input type="hidden" name="city" value="{{ $bookingSearch['city'] }}">
            <input type="hidden" name="state" value="{{ $bookingSearch['state'] ?? '' }}">
            <input type="hidden" name="zipcode" value="{{ $bookingSearch['zipcode'] }}">
            @if(!empty($bookingSearch['rush_guarantee']))
            <input type="hidden" name="rush_guarantee" value="1">
            @endif
            @endif

            @if($needsDetailsForm)
            <div class="bg-[#1F1F1F] border border-[#282828] p-6 md:p-8 rounded-xl space-y-5">
                <h3 class="text-white font-bold text-lg border-b border-[#282828] pb-3">Event Details</h3>

                <div class="booking-field-group">
                    <label class="booking-label">Name of Client</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $bookingSearch['client_name'] ?? auth()->user()->name) }}"
                        class="booking-form-input" required>
                </div>

                <div class="booking-field-group">
                    <label class="booking-label">Event Date</label>
                    <input type="date" name="event_date" value="{{ old('event_date', $bookingSearch['event_date'] ?? '') }}"
                        min="{{ date('Y-m-d') }}" class="booking-form-input" required>
                </div>

                <div class="booking-field-group">
                    <label class="booking-label">Event Type</label>
                    <select name="event_type" required class="booking-form-select">
                        <option value="" disabled {{ old('event_type', $bookingSearch['event_type'] ?? '') ? '' : 'selected' }}>Select event type</option>
                        @foreach (['Wedding', 'Birthday Party', 'Corporate Event', 'Festival', 'Club Night', 'Private Party', 'Other'] as $type)
                        <option value="{{ $type }}" {{ old('event_type', $bookingSearch['event_type'] ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="booking-field-group">
                    <label class="booking-label">Venue Type</label>
                    <select name="venue_type" id="venueTypeSelect" required class="booking-form-select">
                        <option value="" disabled {{ old('venue_type', $bookingSearch['venue_type'] ?? '') ? '' : 'selected' }}>Select venue type</option>
                        <option value="club" {{ old('venue_type', $bookingSearch['venue_type'] ?? '') === 'club' ? 'selected' : '' }}>Club</option>
                        <option value="hall" {{ old('venue_type', $bookingSearch['venue_type'] ?? '') === 'hall' ? 'selected' : '' }}>Hall</option>
                        <option value="house" {{ old('venue_type', $bookingSearch['venue_type'] ?? '') === 'house' ? 'selected' : '' }}>House</option>
                        <option value="other" {{ old('venue_type', $bookingSearch['venue_type'] ?? '') === 'other' ? 'selected' : '' }}>Other — please specify</option>
                    </select>
                </div>

                <div class="booking-field-group {{ old('venue_type', $bookingSearch['venue_type'] ?? '') === 'other' ? '' : 'hidden' }}" id="venueOtherWrap">
                    <label class="booking-label">Please Specify Venue Type</label>
                    <input type="text" name="venue_type_other" id="venueTypeOther"
                        value="{{ old('venue_type_other', $bookingSearch['venue_type_other'] ?? '') }}"
                        class="booking-form-input" placeholder="Describe your venue type">
                </div>

                <div class="booking-field-group">
                    <label class="booking-label">Venue Name</label>
                    <input type="text" name="venue_name" value="{{ old('venue_name', $bookingSearch['venue_name'] ?? '') }}"
                        class="booking-form-input" placeholder="Enter venue name" required>
                </div>

                <div class="booking-field-group">
                    <label class="booking-label">Venue Address</label>
                    <input type="text" name="venue_address" value="{{ old('venue_address', $bookingSearch['venue_address'] ?? '') }}"
                        class="booking-form-input" placeholder="Enter full venue address" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="booking-field-group">
                        <label class="booking-label">City</label>
                        <input type="text" name="city" value="{{ old('city', $bookingSearch['city'] ?? '') }}"
                            class="booking-form-input" placeholder="City" required>
                    </div>
                    <div class="booking-field-group">
                        <label class="booking-label">Zip Code</label>
                        <input type="text" name="zipcode" value="{{ old('zipcode', $bookingSearch['zipcode'] ?? '') }}"
                            class="booking-form-input" placeholder="Zip code" required>
                    </div>
                </div>

                <div class="booking-field-group">
                    <label class="booking-label">State <span class="text-[#777777] font-normal normal-case">(optional)</span></label>
                    <input type="text" name="state" value="{{ old('state', $bookingSearch['state'] ?? '') }}"
                        class="booking-form-input" placeholder="e.g. FL">
                </div>

                <label class="flex items-start gap-3 cursor-pointer p-4 bg-[#2a2a2a]/50 border border-[#3a3a3a] rounded-lg">
                    <input type="checkbox" name="rush_guarantee" value="1"
                        {{ old('rush_guarantee', $bookingSearch['rush_guarantee'] ?? false) ? 'checked' : '' }}
                        class="mt-1 w-4 h-4 accent-[#FFD900]">
                    <span class="text-sm text-gray-300">
                        <span class="text-white font-semibold">Guaranteed response within 6 hours</span> (+$50 fee)
                    </span>
                </label>
            </div>
            @endif

            <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-xl space-y-3 text-sm">
                <h3 class="text-white font-bold text-lg mb-2">Booking Summary</h3>
                @if(!$needsDetailsForm)
                <div class="flex justify-between"><span class="text-gray-400">Client</span><span class="text-white">{{ $bookingSearch['client_name'] ?? auth()->user()->name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Event Date</span><span class="text-white font-semibold">{{ \Carbon\Carbon::parse($bookingSearch['event_date'])->format('M d, Y') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Event Type</span><span class="text-white">{{ $bookingSearch['event_type'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Venue</span><span class="text-white">{{ $bookingSearch['venue_name'] }} ({{ $bookingSearch['venue_type_label'] ?? ucfirst($bookingSearch['venue_type']) }})</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Location</span><span class="text-white">{{ $bookingSearch['city'] }}, {{ $bookingSearch['zipcode'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Address</span><span class="text-white text-right max-w-[60%]">{{ $bookingSearch['venue_address'] }}</span></div>
                @endif
                <div class="flex justify-between"><span class="text-gray-400">DJ</span><span class="text-white font-semibold">{{ $dj->stage_name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">DJ Rate</span><span class="text-[#FFD900] font-bold">${{ number_format($dj->hourly_rate) }}/hr</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Booking Fee</span><span class="text-white">$100.00 (non-refundable)</span></div>
                @php
                $rushChecked = old('rush_guarantee', $bookingSearch['rush_guarantee'] ?? false);
                @endphp
                @if($rushChecked)
                <div class="flex justify-between"><span class="text-gray-400">6-Hour Rush Guarantee</span><span class="text-white">+$50.00</span></div>
                @endif
                <div class="flex justify-between border-t border-[#282828] pt-3">
                    <span class="text-white font-bold">Estimated Total</span>
                    <span class="text-[#FFD900] font-bold">${{ number_format($dj->hourly_rate + 100 + ($rushChecked ? 50 : 0), 2) }}</span>
                </div>
            </div>

            <p class="text-gray-400 text-sm text-center">
                The DJ/MC will receive your details by email and text. They have
                {{ ($rushChecked ?? false) ? '6 hours' : '48 hours' }} to accept or decline.
            </p>
            <button type="submit" class="btn primary-button btn-hover-glow w-full py-5 uppercase font-bold tracking-wider">
                Send Booking Request to DJ/MC
            </button>
            <a href="{{ route('dj.show', $dj->id) }}" class="btn block secondary-button w-full text-center py-4">Back to Profile</a>
        </form>
    </div>
</main>

@if($needsDetailsForm)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const venueSelect = document.getElementById('venueTypeSelect');
        const venueOtherWrap = document.getElementById('venueOtherWrap');
        const venueTypeOther = document.getElementById('venueTypeOther');

        function toggleVenueOther() {
            if (venueSelect.value === 'other') {
                venueOtherWrap.classList.remove('hidden');
                venueTypeOther.required = true;
            } else {
                venueOtherWrap.classList.add('hidden');
                venueTypeOther.required = false;
            }
        }

        venueSelect.addEventListener('change', toggleVenueOther);
        toggleVenueOther();
    });
</script>
@endif
@endsection