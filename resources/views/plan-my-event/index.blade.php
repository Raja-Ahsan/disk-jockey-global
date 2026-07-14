@extends('layouts.web.master')

@section('content')
<main class="home-landing overflow-hidden">
    <section class="home-hero-section relative min-h-screen flex flex-col items-center justify-center px-4 md:px-8 py-24 md:py-28">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-[#161616]"></div>
            <div class="home-hero-glow absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full bg-[#FFD900]/5 blur-[120px] pointer-events-none"></div>
        </div>

        <div class="container mx-auto max-w-3xl relative z-10 w-full" data-aos="fade-up">
            <div class="text-center mb-8 md:mb-10">
                <h1 class="text-[28px] md:text-[42px] font-extrabold text-white leading-tight">
                    Enter Your Event Details and We Will Get Back to You.
                </h1>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500/20 border border-green-500/40 rounded-lg text-green-300 text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="booking-form-card group">
                <div class="booking-form-card__inner">
                <form action="{{ route('plan-my-event.store') }}" method="POST" id="planMyEventForm" class="space-y-5" novalidate>
                    @csrf

                    <div class="booking-field-group">
                        <label class="booking-label">Name of Client</label>
                        <input type="text" name="client_name" value="{{ old('client_name', auth()->user()->name ?? '') }}"
                               placeholder="Your full name"
                               class="booking-form-input {{ $errors->has('client_name') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('client_name') ? '' : 'hidden' }}" data-error-for="client_name">@error('client_name'){{ $message }}@enderror</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="booking-field-group">
                            <label class="booking-label">City</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   placeholder="Enter city"
                                   class="booking-form-input {{ $errors->has('city') ? 'booking-form-input--error' : '' }}">
                            <p class="booking-field-error {{ $errors->has('city') ? '' : 'hidden' }}" data-error-for="city">@error('city'){{ $message }}@enderror</p>
                        </div>

                        <div class="booking-field-group">
                            <label class="booking-label">Zip Code</label>
                            <input type="text" name="zipcode" value="{{ old('zipcode') }}"
                                   placeholder="Enter zip code"
                                   class="booking-form-input {{ $errors->has('zipcode') ? 'booking-form-input--error' : '' }}">
                            <p class="booking-field-error {{ $errors->has('zipcode') ? '' : 'hidden' }}" data-error-for="zipcode">@error('zipcode'){{ $message }}@enderror</p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <label class="btn-near-me cursor-pointer inline-flex items-center gap-2">
                            <input type="checkbox" name="use_near_me" value="1" {{ old('use_near_me') ? 'checked' : '' }}
                                   class="w-4 h-4 accent-[#FFD900]">
                            <span>Find DJ / MC Near Me</span>
                        </label>
                    </div>

                    <div class="booking-field-group">
                        <label class="booking-label">Date</label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="booking-form-input {{ $errors->has('event_date') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('event_date') ? '' : 'hidden' }}" data-error-for="event_date">@error('event_date'){{ $message }}@enderror</p>
                    </div>

                    <div class="booking-field-group">
                        <label class="booking-label">Event Type</label>
                        <div class="relative group">
                            <select name="event_type" class="booking-form-select {{ $errors->has('event_type') ? 'booking-form-select--error' : '' }}">
                                <option value="" disabled {{ old('event_type') ? '' : 'selected' }}>Select event type</option>
                                @foreach (['Wedding', 'Birthday Party', 'Corporate Event', 'Festival', 'Club Night', 'Private Party', 'Other'] as $type)
                                    <option value="{{ $type }}" {{ old('event_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            <div class="booking-select-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p class="booking-field-error {{ $errors->has('event_type') ? '' : 'hidden' }}" data-error-for="event_type">@error('event_type'){{ $message }}@enderror</p>
                    </div>

                    <div class="booking-field-group">
                        <label class="booking-label">Venue Type</label>
                        <div class="relative group">
                            <select name="venue_type" id="venueTypeSelect" class="booking-form-select {{ $errors->has('venue_type') ? 'booking-form-select--error' : '' }}">
                                <option value="" disabled {{ old('venue_type') ? '' : 'selected' }}>Select venue type</option>
                                <option value="club" {{ old('venue_type') === 'club' ? 'selected' : '' }}>Club</option>
                                <option value="hall" {{ old('venue_type') === 'hall' ? 'selected' : '' }}>Hall</option>
                                <option value="house" {{ old('venue_type') === 'house' ? 'selected' : '' }}>House</option>
                                <option value="other" {{ old('venue_type') === 'other' ? 'selected' : '' }}>Other — please specify</option>
                            </select>
                            <div class="booking-select-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p class="booking-field-error {{ $errors->has('venue_type') ? '' : 'hidden' }}" data-error-for="venue_type">@error('venue_type'){{ $message }}@enderror</p>
                    </div>

                    <div class="booking-field-group {{ old('venue_type') === 'other' ? '' : 'hidden' }}" id="venueOtherWrap">
                        <label class="booking-label">Please Specify Venue Type</label>
                        <input type="text" name="venue_type_other" id="venueTypeOther" value="{{ old('venue_type_other') }}"
                               placeholder="Describe your venue type"
                               class="booking-form-input {{ $errors->has('venue_type_other') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('venue_type_other') ? '' : 'hidden' }}" data-error-for="venue_type_other">@error('venue_type_other'){{ $message }}@enderror</p>
                    </div>

                    <div class="booking-field-group">
                        <label class="booking-label">Venue Name</label>
                        <input type="text" name="venue_name" value="{{ old('venue_name') }}"
                               placeholder="Enter venue name"
                               class="booking-form-input {{ $errors->has('venue_name') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('venue_name') ? '' : 'hidden' }}" data-error-for="venue_name">@error('venue_name'){{ $message }}@enderror</p>
                    </div>

                    <div class="booking-field-group">
                        <label class="booking-label">Venue Address</label>
                        <input type="text" name="venue_address" value="{{ old('venue_address') }}"
                               placeholder="Enter full venue address"
                               class="booking-form-input {{ $errors->has('venue_address') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('venue_address') ? '' : 'hidden' }}" data-error-for="venue_address">@error('venue_address'){{ $message }}@enderror</p>
                    </div>

                    <div class="booking-field-group">
                        <label class="booking-label">Budget Filter ($100 – $25,000)</label>
                        <div class="relative group">
                            <select name="budget_range" class="booking-form-select {{ $errors->has('budget_range') ? 'booking-form-select--error' : '' }}">
                                <option value="" disabled {{ old('budget_range') ? '' : 'selected' }}>Select your budget range</option>
                                <option value="100-500" {{ old('budget_range') === '100-500' ? 'selected' : '' }}>$100 – $500</option>
                                <option value="500-1000" {{ old('budget_range') === '500-1000' ? 'selected' : '' }}>$500 – $1,000</option>
                                <option value="1000-2500" {{ old('budget_range') === '1000-2500' ? 'selected' : '' }}>$1,000 – $2,500</option>
                                <option value="2500-5000" {{ old('budget_range') === '2500-5000' ? 'selected' : '' }}>$2,500 – $5,000</option>
                                <option value="5000-10000" {{ old('budget_range') === '5000-10000' ? 'selected' : '' }}>$5,000 – $10,000</option>
                                <option value="10000-25000" {{ old('budget_range') === '10000-25000' ? 'selected' : '' }}>$10,000 – $25,000</option>
                            </select>
                            <div class="booking-select-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p class="booking-field-error {{ $errors->has('budget_range') ? '' : 'hidden' }}" data-error-for="budget_range">@error('budget_range'){{ $message }}@enderror</p>
                    </div>

                    <div class="booking-field-group">
                        <label class="booking-label">Preferred DJ / MC Name <span class="text-gray-500 font-normal">(optional)</span></label>
                        <input type="text" name="dj_name" value="{{ old('dj_name') }}"
                               placeholder="Enter DJ or MC stage name"
                               class="booking-form-input {{ $errors->has('dj_name') ? 'booking-form-input--error' : '' }}">
                        <input type="hidden" name="search_by_name" value="0">
                        <p class="booking-field-error {{ $errors->has('dj_name') ? '' : 'hidden' }}" data-error-for="dj_name">@error('dj_name'){{ $message }}@enderror</p>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer group p-4 bg-[#2a2a2a]/50 border border-[#3a3a3a] rounded-lg hover:border-[#FFD900]/40 transition-all">
                        <input type="checkbox" name="rush_guarantee" value="1" {{ old('rush_guarantee') ? 'checked' : '' }}
                               class="mt-1 w-4 h-4 accent-[#FFD900]">
                        <span class="text-sm text-gray-300">
                            <span class="text-white font-semibold">Guaranteed response within 6 hours</span> (+$50 fee)
                        </span>
                    </label>

                    <button type="submit" class="btn primary-button btn-hover-glow w-full py-5! uppercase tracking-wider text-[16px] md:text-[18px] font-bold">
                        Submit Event Details
                    </button>
                </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var venueSelect = document.getElementById('venueTypeSelect');
    var venueOtherWrap = document.getElementById('venueOtherWrap');
    if (venueSelect && venueOtherWrap) {
        venueSelect.addEventListener('change', function () {
            if (this.value === 'other') {
                venueOtherWrap.classList.remove('hidden');
            } else {
                venueOtherWrap.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
