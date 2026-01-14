@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-20 px-6">
    <div class="container mx-auto max-w-6xl">
        <!-- Header -->
        <div class="mb-10" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Booking <span class="text-[#FFD900]">Details</span>
            </h1>
            <div class="flex items-center gap-4">
                <span class="px-4 py-2 rounded text-sm font-bold 
                    {{ $booking->booking_status === 'confirmed' ? 'bg-green-500' : 
                       ($booking->booking_status === 'pending' ? 'bg-yellow-500' : 
                       ($booking->booking_status === 'completed' ? 'bg-blue-500' : 'bg-gray-500')) }} 
                    text-white">
                    {{ strtoupper($booking->booking_status) }}
                </span>
                <span class="px-4 py-2 rounded text-sm font-bold 
                    {{ $booking->payment_status === 'paid' ? 'bg-green-500' : 
                       ($booking->payment_status === 'partial' ? 'bg-yellow-500' : 'bg-gray-500') }} 
                    text-white">
                    Payment: {{ strtoupper($booking->payment_status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booking Information -->
                <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-lg" data-aos="fade-right">
                    <h2 class="text-2xl font-bold text-white mb-6">Booking Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Booking Date</p>
                            <p class="text-white text-lg font-semibold">{{ $booking->booking_date->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Time</p>
                            <p class="text-white text-lg font-semibold">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Venue Address</p>
                            <p class="text-white">{{ $booking->venue_address }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Location</p>
                            <p class="text-white">{{ $booking->city }}, {{ $booking->state }} {{ $booking->zipcode }}</p>
                        </div>
                        @if($booking->special_requests)
                        <div class="md:col-span-2">
                            <p class="text-gray-400 text-sm mb-1">Special Requests</p>
                            <p class="text-white">{{ $booking->special_requests }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- DJ Information -->
                <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-lg" data-aos="fade-right">
                    <h2 class="text-2xl font-bold text-white mb-6">DJ Information</h2>
                    <div class="flex items-center gap-6">
                        <img src="{{ $booking->dj->profile_image ? asset('storage/' . $booking->dj->profile_image) : asset('images/talent-img-00' . (($booking->dj->id % 3) + 1) . '.png') }}" alt="{{ $booking->dj->stage_name }}" class="w-24 h-24 rounded-lg object-cover">
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $booking->dj->stage_name }}</h3>
                            <p class="text-gray-400 mb-1">{{ $booking->dj->city }}, {{ $booking->dj->state }}</p>
                            <a href="{{ route('dj.show', $booking->dj->id) }}" class="text-[#FFD900] hover:underline">View Profile</a>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                @if($booking->payment_status !== 'paid' && Auth::id() === $booking->user_id)
                <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-lg" data-aos="fade-right">
                    <h2 class="text-2xl font-bold text-white mb-6">Payment</h2>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Total Amount</span>
                            <span class="text-[#FFD900] text-2xl font-bold">${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        @if($booking->payment_status === 'pending')
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Deposit Required (30%)</span>
                                <span class="text-white text-xl font-semibold">${{ number_format($booking->deposit_amount, 2) }}</span>
                            </div>
                        @elseif($booking->payment_status === 'partial')
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Remaining Balance</span>
                                <span class="text-white text-xl font-semibold">${{ number_format($booking->total_amount - $booking->deposit_amount, 2) }}</span>
                            </div>
                        @endif

                        <!-- Stripe Payment Form -->
                        <div id="payment-form-container" class="hidden">
                            <div id="card-element" class="bg-[#161616] border border-[#282828] p-4 rounded mb-4">
                                <!-- Stripe Elements will create form elements here -->
                            </div>
                            <div id="card-errors" role="alert" class="text-red-500 text-sm mb-4"></div>
                            <button id="submit-payment" class="btn primary-button w-full py-4 text-lg uppercase tracking-wider font-bold">
                                Pay $<span id="payment-amount">{{ number_format($booking->payment_status === 'pending' ? $booking->deposit_amount : ($booking->total_amount - $booking->deposit_amount), 2) }}</span>
                            </button>
                        </div>

                        <button id="initiate-payment" class="btn primary-button w-full py-4 text-lg uppercase tracking-wider font-bold">
                            {{ $booking->payment_status === 'pending' ? 'Pay Deposit $' . number_format($booking->deposit_amount, 2) : 'Pay Remaining Balance $' . number_format($booking->total_amount - $booking->deposit_amount, 2) }}
                        </button>
                    </div>
                </div>
                @else
                <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-lg" data-aos="fade-right">
                    <h2 class="text-2xl font-bold text-white mb-6">Payment</h2>
                    <div class="flex items-center gap-3 text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-bold text-lg">Payment Complete</p>
                            <p class="text-sm text-gray-400">Total: ${{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions (for DJ or Admin) -->
                @if(Auth::id() === $booking->dj->user_id || Auth::user()->isAdmin())
                <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-lg" data-aos="fade-right">
                    <h2 class="text-2xl font-bold text-white mb-6">Actions</h2>
                    <div class="flex flex-wrap gap-4">
                        @if($booking->booking_status === 'pending')
                            <form action="{{ route('bookings.confirm', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn primary-button">Confirm Booking</button>
                            </form>
                        @endif
                        @if($booking->booking_status === 'confirmed' && !$booking->completed_at)
                            <form action="{{ route('bookings.complete', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn primary-button">Mark as Completed</button>
                            </form>
                        @endif
                        @if($booking->booking_status !== 'cancelled' && $booking->booking_status !== 'completed')
                            <button onclick="showCancelModal()" class="btn secondary-button">Cancel Booking</button>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-[#1F1F1F] border border-[#282828] p-6 rounded-lg sticky top-8" data-aos="fade-left">
                    <h3 class="text-xl font-bold text-white mb-6">Booking Summary</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Booking ID</p>
                            <p class="text-white font-mono">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Created</p>
                            <p class="text-white">{{ $booking->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($booking->confirmed_at)
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Confirmed</p>
                            <p class="text-white">{{ $booking->confirmed_at->format('M d, Y') }}</p>
                        </div>
                        @endif
                        <div class="border-t border-[#282828] pt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-white">${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-white">Total</span>
                                <span class="text-[#FFD900]">${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Cancel Modal -->
<div id="cancelModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-[#1F1F1F] border border-[#282828] p-8 rounded-lg max-w-md w-full">
        <h3 class="text-2xl font-bold text-white mb-4">Cancel Booking</h3>
        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-white font-semibold mb-3">Reason for Cancellation</label>
                <textarea name="cancellation_reason" rows="4" required class="w-full bg-[#161616] border border-[#282828] text-white p-4 focus:border-[#FFD900] outline-none transition-all"></textarea>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="btn primary-button flex-1">Confirm Cancellation</button>
                <button type="button" onclick="hideCancelModal()" class="btn secondary-button">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<script>
let stripe, elements, cardElement, clientSecret;

function showCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function hideCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

// Initialize Stripe Payment
@if($booking->payment_status !== 'paid' && Auth::id() === $booking->user_id)
document.addEventListener('DOMContentLoaded', function() {
    @if(config('services.stripe.key'))
    stripe = Stripe('{{ config('services.stripe.key') }}');

    document.getElementById('initiate-payment')?.addEventListener('click', async function() {
        const paymentType = '{{ $booking->payment_status === 'pending' ? 'deposit' : 'full' }}';
        const button = this;
        button.disabled = true;
        button.textContent = 'Loading...';

        try {
            const response = await fetch('{{ route('payment.intent', $booking->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type: paymentType })
            });

            const data = await response.json();

            if (data.error) {
                alert('Error: ' + data.error);
                button.disabled = false;
                button.textContent = '{{ $booking->payment_status === 'pending' ? 'Pay Deposit $' . number_format($booking->deposit_amount, 2) : 'Pay Remaining Balance $' . number_format($booking->total_amount - $booking->deposit_amount, 2) }}';
                return;
            }

            clientSecret = data.clientSecret;

            // Create Stripe Elements
            elements = stripe.elements();
            cardElement = elements.create('card', {
                style: {
                    base: {
                        color: '#ffffff',
                        fontFamily: 'system-ui, sans-serif',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#888888',
                        },
                    },
                    invalid: {
                        color: '#fa755a',
                    },
                },
            });

            cardElement.mount('#card-element');

            // Handle real-time validation errors
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Show payment form
            document.getElementById('payment-form-container').classList.remove('hidden');
            button.style.display = 'none';

        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            button.disabled = false;
            button.textContent = '{{ $booking->payment_status === 'pending' ? 'Pay Deposit $' . number_format($booking->deposit_amount, 2) : 'Pay Remaining Balance $' . number_format($booking->total_amount - $booking->deposit_amount, 2) }}';
        }
    });

    // Handle form submission
    document.getElementById('submit-payment')?.addEventListener('click', async function(e) {
        e.preventDefault();
        const button = this;
        button.disabled = true;
        button.textContent = 'Processing...';

        const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
            }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            button.disabled = false;
            button.textContent = 'Pay ${{ number_format($booking->payment_status === 'pending' ? $booking->deposit_amount : ($booking->total_amount - $booking->deposit_amount), 2) }}';
        } else if (paymentIntent.status === 'succeeded') {
            // Redirect to confirmation page
            window.location.href = '{{ route('payment.confirm', $booking->id) }}';
        }
    });
    @else
    document.getElementById('initiate-payment')?.addEventListener('click', function() {
        alert('Stripe is not configured. Please add your Stripe keys to the .env file.');
    });
    @endif
});
@endif
</script>
@endsection
