@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-20 px-6">
    <div class="container mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-10 text-center" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Book <span class="text-[#FFD900]">{{ $dj->stage_name }}</span>
            </h1>
            <p class="text-gray-400 text-lg">Fill in the details below to create your booking</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- DJ Info Card -->
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="bg-[#1F1F1F] border border-[#282828] p-6 rounded-lg sticky top-8">
                    <div class="mb-6">
                        <img src="{{ $dj->profile_image ? asset('storage/' . $dj->profile_image) : asset('images/talent-img-00' . (($dj->id % 3) + 1) . '.png') }}" alt="{{ $dj->stage_name }}" class="w-full rounded-lg mb-4">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $dj->stage_name }}</h3>
                        <p class="text-gray-400 mb-4">{{ $dj->city }}, {{ $dj->state }}</p>
                    </div>
                    <div class="space-y-3 border-t border-[#282828] pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Hourly Rate</span>
                            <span class="text-[#FFD900] font-bold">${{ number_format($dj->hourly_rate) }}/hr</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Experience</span>
                            <span class="text-white">{{ $dj->experience_years }}+ Years</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Rating</span>
                            <span class="text-white">{{ number_format($dj->rating, 1) }} ⭐</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-lg">
                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="dj_id" value="{{ $dj->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-white font-semibold mb-3">Booking Date *</label>
                                <input type="date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all">
                                @error('booking_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-white font-semibold mb-3">Start Time *</label>
                                <input type="time" name="start_time" value="{{ old('start_time') }}" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all">
                                @error('start_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">End Time *</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all">
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">Venue Address *</label>
                            <input type="text" name="venue_address" value="{{ old('venue_address') }}" placeholder="123 Main Street" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                            @error('venue_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-white font-semibold mb-3">City *</label>
                                <input type="text" name="city" value="{{ old('city') }}" placeholder="New York" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-white font-semibold mb-3">State *</label>
                                <input type="text" name="state" value="{{ old('state') }}" placeholder="NY" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                                @error('state')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-white font-semibold mb-3">Zipcode *</label>
                                <input type="text" name="zipcode" value="{{ old('zipcode') }}" placeholder="10001" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">
                                @error('zipcode')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-white font-semibold mb-3">Special Requests</label>
                            <textarea name="special_requests" rows="4" placeholder="Any special requirements or requests for the event..." class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all placeholder:text-[#555]">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-[#161616] border border-[#282828] p-6 rounded-lg">
                            <h4 class="text-white font-bold mb-4">Estimated Cost</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-400">
                                    <span>Hourly Rate:</span>
                                    <span>${{ number_format($dj->hourly_rate) }}/hr</span>
                                </div>
                                <div class="flex justify-between text-gray-400">
                                    <span>Duration:</span>
                                    <span id="duration-display">-</span>
                                </div>
                                <div class="border-t border-[#282828] pt-2 mt-2">
                                    <div class="flex justify-between text-white font-bold">
                                        <span>Estimated Total:</span>
                                        <span id="total-display" class="text-[#FFD900]">$0.00</span>
                                    </div>
                                </div>
                                <p class="text-gray-500 text-xs mt-2">Final amount will be calculated based on actual hours</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="btn primary-button flex-1 py-4 text-lg uppercase tracking-wider font-bold">
                                Create Booking
                            </button>
                            <a href="{{ route('dj.show', $dj->id) }}" class="btn secondary-button px-8 py-4">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');
    const bookingDateInput = document.querySelector('input[name="booking_date"]');
    const durationDisplay = document.getElementById('duration-display');
    const totalDisplay = document.getElementById('total-display');
    const hourlyRate = {{ $dj->hourly_rate }};

    function calculateTotal() {
        if (startTimeInput.value && endTimeInput.value && bookingDateInput.value) {
            const start = new Date(bookingDateInput.value + 'T' + startTimeInput.value);
            const end = new Date(bookingDateInput.value + 'T' + endTimeInput.value);
            
            if (end > start) {
                const diffMs = end - start;
                const diffHours = diffMs / (1000 * 60 * 60);
                const total = diffHours * hourlyRate;
                
                durationDisplay.textContent = diffHours.toFixed(1) + ' hours';
                totalDisplay.textContent = '$' + total.toFixed(2);
            } else {
                durationDisplay.textContent = 'Invalid time range';
                totalDisplay.textContent = '$0.00';
            }
        } else {
            durationDisplay.textContent = '-';
            totalDisplay.textContent = '$0.00';
        }
    }

    startTimeInput.addEventListener('change', calculateTotal);
    endTimeInput.addEventListener('change', calculateTotal);
    bookingDateInput.addEventListener('change', calculateTotal);
});
</script>
@endsection
