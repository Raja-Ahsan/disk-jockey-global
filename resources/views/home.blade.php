@extends('layouts.web.master')

@section('content')
<main class="home-landing overflow-hidden">

    {{-- Hero + Booking Form --}}
    <section class="home-hero-section relative min-h-screen flex flex-col items-center justify-center px-4 md:px-8 py-24 md:py-28">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-[#161616]"></div>
            <div class="home-hero-glow absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full bg-[#FFD900]/5 blur-[120px] pointer-events-none"></div>
        </div>

        <div class="container mx-auto max-w-3xl relative z-10 w-full" data-aos="fade-up">
            <div class="text-center mb-8 md:mb-10">
                <h1 class="text-[28px] md:text-[42px] font-extrabold text-white leading-tight">
                    BOOK A <span class="text-[#FFD900]">DJ / MC</span> for your event
                </h1>
            </div>

            <div class="booking-form-card group">
                <div class="booking-form-card__inner">
                <form action="{{ route('book.search') }}" method="POST" id="bookingSearchForm" class="space-y-5" novalidate>
                    @csrf

                    <p class="booking-field-error {{ $errors->has('form') ? '' : 'hidden' }}" data-error-for="form">@error('form'){{ $message }}@enderror</p>

                    {{-- Client Name --}}
                    <div class="booking-field-group">
                        <label class="booking-label">Name of Client</label>
                        <input type="text" name="client_name" value="{{ old('client_name', auth()->user()->name ?? '') }}"
                               placeholder="Your full name"
                               class="booking-form-input {{ $errors->has('client_name') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('client_name') ? '' : 'hidden' }}" data-error-for="client_name">@error('client_name'){{ $message }}@enderror</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- City --}}
                        <div class="booking-field-group">
                            <label class="booking-label">City</label>
                            <input type="text" name="city" id="cityInput" value="{{ old('city') }}"
                                   placeholder="Enter city"
                                   class="booking-form-input {{ $errors->has('city') ? 'booking-form-input--error' : '' }}">
                            <p class="booking-field-error {{ $errors->has('city') ? '' : 'hidden' }}" data-error-for="city">@error('city'){{ $message }}@enderror</p>
                        </div>

                        {{-- Zip Code --}}
                        <div class="booking-field-group">
                            <label class="booking-label">Zip Code</label>
                            <input type="text" name="zipcode" id="zipcodeInput" value="{{ old('zipcode') }}"
                                   placeholder="Enter zip code"
                                   class="booking-form-input {{ $errors->has('zipcode') ? 'booking-form-input--error' : '' }}">
                            <p class="booking-field-error {{ $errors->has('zipcode') ? '' : 'hidden' }}" data-error-for="zipcode">@error('zipcode'){{ $message }}@enderror</p>
                        </div>
                    </div>

                    {{-- Find Near Me --}}
                    <div class="flex justify-end">
                        <button type="button" id="nearMeBtn" class="btn-near-me">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span id="nearMeText">Find DJ / MC Near Me</span>
                        </button>
                    </div>
                    <input type="hidden" name="use_near_me" id="useNearMe" value="{{ old('use_near_me', '0') }}">

                    {{-- Event Date --}}
                    <div class="booking-field-group">
                        <label class="booking-label">Date</label>
                        <input type="date" name="event_date" value="{{ old('event_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="booking-form-input {{ $errors->has('event_date') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('event_date') ? '' : 'hidden' }}" data-error-for="event_date">@error('event_date'){{ $message }}@enderror</p>
                    </div>

                    {{-- Event Type --}}
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

                    {{-- Venue Type --}}
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

                    {{-- Venue Name --}}
                    <div class="booking-field-group">
                        <label class="booking-label">Venue Name</label>
                        <input type="text" name="venue_name" value="{{ old('venue_name') }}"
                               placeholder="Enter venue name"
                               class="booking-form-input {{ $errors->has('venue_name') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('venue_name') ? '' : 'hidden' }}" data-error-for="venue_name">@error('venue_name'){{ $message }}@enderror</p>
                    </div>

                    {{-- Venue Address --}}
                    <div class="booking-field-group">
                        <label class="booking-label">Venue Address</label>
                        <input type="text" name="venue_address" value="{{ old('venue_address') }}"
                               placeholder="Enter full venue address"
                               class="booking-form-input {{ $errors->has('venue_address') ? 'booking-form-input--error' : '' }}">
                        <p class="booking-field-error {{ $errors->has('venue_address') ? '' : 'hidden' }}" data-error-for="venue_address">@error('venue_address'){{ $message }}@enderror</p>
                    </div>

                    {{-- Budget Filter --}}
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

                    {{-- Search by DJ/MC Name --}}
                    <div class="booking-field-group">
                        <label class="booking-label">Search by DJ / MC Name</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="text" name="dj_name" id="djNameInput" value="{{ old('dj_name') }}"
                                   placeholder="Enter DJ or MC stage name"
                                   class="booking-form-input flex-1 {{ $errors->has('dj_name') ? 'booking-form-input--error' : '' }}">
                            <button type="button" id="searchByNameBtn" class="btn secondary-button whitespace-nowrap px-6! py-4!">
                                Search by Name
                            </button>
                        </div>
                        <input type="hidden" name="search_by_name" id="searchByName" value="{{ old('search_by_name', '0') }}">
                        <p class="booking-field-error {{ $errors->has('dj_name') ? '' : 'hidden' }}" data-error-for="dj_name">@error('dj_name'){{ $message }}@enderror</p>
                    </div>

                    {{-- Rush guarantee --}}
                    <label class="flex items-start gap-3 cursor-pointer group p-4 bg-[#2a2a2a]/50 border border-[#3a3a3a] rounded-lg hover:border-[#FFD900]/40 transition-all">
                        <input type="checkbox" name="rush_guarantee" value="1" {{ old('rush_guarantee') ? 'checked' : '' }}
                               class="mt-1 w-4 h-4 accent-[#FFD900]">
                        <span class="text-sm text-gray-300">
                            <span class="text-white font-semibold">Guaranteed response within 6 hours</span> (+$50 fee)
                        </span>
                    </label>
                    <button type="submit" id="mainSearchBtn" class="btn primary-button btn-hover-glow w-full py-5! uppercase tracking-wider text-[16px] md:text-[18px] font-bold">
                        Search
                    </button>
                </form>
                </div>
            </div>
        </div>

        {{-- Scroll hint to merch --}}
        @if ($merchProducts->count() > 0)
            <a href="#merchandise" class="home-scroll-hint absolute bottom-6 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 text-[#777777] hover:text-[#FFD900] transition-colors duration-300">
                <span class="text-xs uppercase tracking-widest">Shop Merchandise</span>
                <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
        @endif
    </section>

    {{-- Merchandise Section --}}
    @if ($merchProducts->count() > 0)
    <section id="merchandise" class="home-merch-section py-16 md:py-20 bg-[#161616]">
        <div class="container mx-auto px-6 lg:px-16">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="merch-label">Merchandise</span>
                <h2 class="merch-title text-[28px] md:text-[40px]">
                    Shop Exclusive <span class="text-[#FFD900] font-bold">DJ & MC</span> Gear
                </h2>
                <p class="merch-desc mb-0!">Premium merchandise with prices — browse our collection below.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                @foreach ($merchProducts as $index => $product)
                <div class="product-card home-product-card group" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        <div class="product-img-wrapper relative home-product-img-wrap">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                            @if ($product->sale_price || ($product->isVariable() && $product->variations->where('sale_price', '!=', null)->count() > 0))
                                <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">Sale</span>
                            @endif
                        </div>
                        <span class="product-category">{{ $product->productCategory->full_name ?? 'General' }}</span>
                        <h3 class="product-name group-hover:text-[#FFD900] transition-colors duration-300">{{ $product->name }}</h3>
                        <div class="flex items-center gap-2">
                            @if ($product->isVariable())
                                <p class="product-price">${{ number_format($product->min_price, 2) }}+</p>
                            @elseif ($product->sale_price)
                                <p class="product-price">${{ number_format($product->sale_price, 2) }}</p>
                                <p class="text-gray-500 line-through text-sm">${{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="product-price">${{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="flex justify-center" data-aos="fade-up">
                <a href="{{ route('merch') }}" class="btn primary-button btn-hover-glow py-4! px-10! uppercase tracking-wider font-bold">
                    View Full Collection
                </a>
            </div>
        </div>
    </section>
    @endif

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const venueSelect = document.getElementById('venueTypeSelect');
    const venueOtherWrap = document.getElementById('venueOtherWrap');
    const venueTypeOther = document.getElementById('venueTypeOther');
    const nearMeBtn = document.getElementById('nearMeBtn');
    const nearMeText = document.getElementById('nearMeText');
    const cityInput = document.getElementById('cityInput');
    const zipcodeInput = document.getElementById('zipcodeInput');
    const useNearMe = document.getElementById('useNearMe');
    const searchByNameBtn = document.getElementById('searchByNameBtn');
    const searchByNameInput = document.getElementById('searchByName');
    const djNameInput = document.getElementById('djNameInput');
    const bookingForm = document.getElementById('bookingSearchForm');
    const mainSearchBtn = document.getElementById('mainSearchBtn');

    function showFieldError(field, message) {
        const errorEl = document.querySelector('[data-error-for="' + field + '"]');
        const input = bookingForm.querySelector('[name="' + field + '"]');

        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        if (input) {
            input.classList.add(input.tagName === 'SELECT' ? 'booking-form-select--error' : 'booking-form-input--error');
        }
    }

    function clearFieldError(field) {
        const errorEl = document.querySelector('[data-error-for="' + field + '"]');
        const input = bookingForm.querySelector('[name="' + field + '"]');

        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
        }

        if (input) {
            input.classList.remove('booking-form-input--error', 'booking-form-select--error');
        }
    }

    function clearAllErrors() {
        bookingForm.querySelectorAll('[data-error-for]').forEach(function (el) {
            if (el.dataset.errorFor !== 'form') {
                el.textContent = '';
                el.classList.add('hidden');
            }
        });
        bookingForm.querySelectorAll('.booking-form-input--error, .booking-form-select--error').forEach(function (el) {
            el.classList.remove('booking-form-input--error', 'booking-form-select--error');
        });
        clearFieldError('form');
    }

    function validateClientSide(searchByName) {
        const errors = {};
        const today = new Date().toISOString().split('T')[0];

        const getVal = function (name) {
            const el = bookingForm.querySelector('[name="' + name + '"]');
            return el ? el.value.trim() : '';
        };

        if (searchByName) {
            if (!getVal('dj_name')) {
                errors.dj_name = 'Please enter a DJ or MC name.';
            }
            if (!getVal('event_date')) {
                errors.event_date = 'Event date is required for name search.';
            } else if (getVal('event_date') < today) {
                errors.event_date = 'Event date must be today or later.';
            }
            return errors;
        }

        if (!getVal('client_name')) errors.client_name = 'Name of client is required.';
        if (!getVal('city')) errors.city = 'City is required.';
        if (!getVal('zipcode')) errors.zipcode = 'Zip code is required.';
        if (!getVal('event_date')) {
            errors.event_date = 'Event date is required.';
        } else if (getVal('event_date') < today) {
            errors.event_date = 'Event date must be today or later.';
        }
        if (!getVal('event_type')) errors.event_type = 'Please select an event type.';
        if (!getVal('venue_type')) errors.venue_type = 'Please select a venue type.';
        if (getVal('venue_type') === 'other' && !getVal('venue_type_other')) {
            errors.venue_type_other = 'Please specify the venue type.';
        }
        if (!getVal('venue_name')) errors.venue_name = 'Venue name is required.';
        if (!getVal('venue_address')) errors.venue_address = 'Venue address is required.';
        if (!getVal('budget_range')) errors.budget_range = 'Please select a budget range.';

        return errors;
    }

    function applyErrors(errors) {
        Object.keys(errors).forEach(function (field) {
            showFieldError(field, errors[field]);
        });

        const firstField = Object.keys(errors)[0];
        if (firstField) {
            const firstInput = bookingForm.querySelector('[name="' + firstField + '"]');
            if (firstInput) {
                firstInput.focus();
                firstInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    async function submitBookingForm(searchByName) {
        clearAllErrors();
        searchByNameInput.value = searchByName ? '1' : '0';

        const clientErrors = validateClientSide(searchByName);
        if (Object.keys(clientErrors).length > 0) {
            applyErrors(clientErrors);
            return;
        }

        const submitBtn = searchByName ? searchByNameBtn : mainSearchBtn;
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = searchByName ? 'Searching...' : 'Searching...';
        if (!searchByName) mainSearchBtn.disabled = true;

        try {
            const response = await fetch(bookingForm.action, {
                method: 'POST',
                body: new FormData(bookingForm),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json().catch(function () { return {}; });

            if (!response.ok) {
                if (data.errors) {
                    const serverErrors = {};
                    Object.keys(data.errors).forEach(function (key) {
                        serverErrors[key] = Array.isArray(data.errors[key]) ? data.errors[key][0] : data.errors[key];
                    });
                    applyErrors(serverErrors);
                } else {
                    showFieldError('form', data.message || 'Something went wrong. Please try again.');
                }
                return;
            }

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } catch (err) {
            showFieldError('form', 'Network error. Please check your connection and try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            mainSearchBtn.disabled = false;
            mainSearchBtn.textContent = 'Search';
        }
    }

    function toggleVenueOther() {
        if (venueSelect.value === 'other') {
            venueOtherWrap.classList.remove('hidden');
        } else {
            venueOtherWrap.classList.add('hidden');
            venueTypeOther.value = '';
            clearFieldError('venue_type_other');
        }
    }

    venueSelect.addEventListener('change', toggleVenueOther);
    toggleVenueOther();

    bookingForm.querySelectorAll('input, select').forEach(function (el) {
        el.addEventListener('input', function () {
            if (el.name) clearFieldError(el.name);
        });
        el.addEventListener('change', function () {
            if (el.name) clearFieldError(el.name);
        });
    });

    bookingForm.addEventListener('submit', function (e) {
        e.preventDefault();
        submitBookingForm(false);
    });

    if (searchByNameBtn) {
        searchByNameBtn.addEventListener('click', function () {
            submitBookingForm(true);
        });
    }

    nearMeBtn.addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }

        nearMeText.textContent = 'Locating...';
        nearMeBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            async function (position) {
                try {
                    const { latitude, longitude } = position.coords;
                    const res = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`,
                        { headers: { 'Accept-Language': 'en' } }
                    );
                    const data = await res.json();
                    const addr = data.address || {};

                    if (addr.city || addr.town || addr.village) {
                        cityInput.value = addr.city || addr.town || addr.village;
                    }
                    if (addr.postcode) {
                        zipcodeInput.value = addr.postcode;
                    }

                    useNearMe.value = '1';
                    nearMeText.textContent = 'Location Found!';
                    nearMeBtn.classList.add('btn-near-me-active');
                } catch (e) {
                    nearMeText.textContent = 'Could not detect location';
                } finally {
                    nearMeBtn.disabled = false;
                    setTimeout(() => {
                        nearMeText.textContent = 'Find DJ / MC Near Me';
                    }, 3000);
                }
            },
            function () {
                nearMeText.textContent = 'Location denied';
                nearMeBtn.disabled = false;
                setTimeout(() => {
                    nearMeText.textContent = 'Find DJ / MC Near Me';
                }, 3000);
            }
        );
    });
});
</script>
@endsection
