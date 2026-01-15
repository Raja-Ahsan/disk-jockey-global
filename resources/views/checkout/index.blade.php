@extends('layouts.web.master')

@section('content')
<main class="min-h-screen bg-[#161616] py-12 px-6">
    <div class="container mx-auto max-w-4xl">
        <!-- Header -->
        <div class="mb-8" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                Checkout
            </h1>
            <p class="text-gray-400">Complete your order</p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400" data-aos="fade-down">
                {{ session('error') }}
            </div>
        @endif

        <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Shipping Information -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6" data-aos="fade-up">
                        <h2 class="text-2xl font-bold text-white mb-6">Shipping Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-white font-semibold mb-2">Full Name *</label>
                                <input type="text" name="shipping_name" value="{{ old('shipping_name', Auth::user()->name) }}" required
                                       class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">Email *</label>
                                <input type="email" name="shipping_email" value="{{ old('shipping_email', Auth::user()->email) }}" required
                                       class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">Phone</label>
                                <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}"
                                       class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">Address Line 1 *</label>
                                <input type="text" name="shipping_address_line1" value="{{ old('shipping_address_line1') }}" required
                                       class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">Address Line 2</label>
                                <input type="text" name="shipping_address_line2" value="{{ old('shipping_address_line2') }}"
                                       class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-white font-semibold mb-2">City *</label>
                                    <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                           class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">State *</label>
                                    <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" required
                                           class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Zipcode *</label>
                                    <input type="text" name="shipping_zipcode" value="{{ old('shipping_zipcode') }}" required
                                           class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Country *</label>
                                    <select name="shipping_country" required
                                           class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
                                        <option value="US" {{ old('shipping_country', 'US') == 'US' ? 'selected' : '' }}>United States</option>
                                        <option value="CA" {{ old('shipping_country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="MX" {{ old('shipping_country') == 'MX' ? 'selected' : '' }}>Mexico</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-white font-semibold mb-2">Order Notes</label>
                                <textarea name="notes" rows="3"
                                          class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6" data-aos="fade-up">
                        <h2 class="text-2xl font-bold text-white mb-6">Payment Information</h2>
                        <div id="card-element" class="bg-[#161616] border border-[#282828] p-4 rounded-lg mb-4">
                            <!-- Stripe Elements will create form elements here -->
                        </div>
                        <div id="card-errors" role="alert" class="text-red-500 text-sm mb-4"></div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-[#1F1F1F] border border-[#282828] rounded-lg p-6 sticky top-6" data-aos="fade-left">
                        <h2 class="text-xl font-bold text-white mb-4">Order Summary</h2>
                        <div class="space-y-3 mb-6">
                            @foreach($products as $item)
                            <div class="flex justify-between text-sm">
                                <div class="flex-1">
                                    <span class="text-gray-400">{{ $item['product']->name }}</span>
                                    @if($item['variation'])
                                        <div class="text-xs text-gray-500 mt-1">
                                            @foreach($item['variation']->attributes as $attr)
                                                <span>{{ ucfirst($attr->attribute_name) }}: {{ $attr->attribute_value }}</span>
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    <span class="text-gray-500"> × {{ $item['quantity'] }}</span>
                                </div>
                                <span class="text-white ml-2">${{ number_format($item['subtotal'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="space-y-2 pt-4 border-t border-[#282828] mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-white">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Tax (8%)</span>
                                <span class="text-white">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Shipping</span>
                                <span class="text-white">
                                    @if($subtotal >= 75)
                                        <span class="text-green-400">FREE</span>
                                    @else
                                        ${{ number_format($shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-[#282828]">
                                <span class="text-white font-bold text-lg">Total</span>
                                <span class="text-[#FFD900] font-bold text-xl">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        <button type="submit" id="submit-button" class="btn primary-button w-full py-4 text-lg">
                            Complete Order
                        </button>
                        <a href="{{ route('cart.index') }}" class="block text-center text-gray-400 hover:text-[#FFD900] mt-4 text-sm">
                            ← Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
@if(config('services.stripe.key'))
document.addEventListener('DOMContentLoaded', function() {
    let stripe = Stripe('{{ config('services.stripe.key') }}');
    let elements = stripe.elements();
    let cardElement = elements.create('card', {
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

    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    document.getElementById('checkout-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';

        try {
            const formData = new FormData(this);
            const response = await fetch('{{ route('checkout.process') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            let data;
            try {
                data = await response.json();
            } catch (jsonError) {
                console.error('JSON parse error:', jsonError);
                const text = await response.text();
                console.error('Response text:', text);
                alert('Error: Invalid response from server. Please try again.');
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
                return;
            }

            if (!response.ok) {
                let errorMessage = 'An error occurred. Please try again.';
                
                if (response.status === 422 && data.errors) {
                    // Validation errors
                    const errorMessages = [];
                    for (const field in data.errors) {
                        if (data.errors[field]) {
                            errorMessages.push(data.errors[field].join(', '));
                        }
                    }
                    errorMessage = 'Validation errors:\n' + errorMessages.join('\n');
                } else if (data.error) {
                    errorMessage = data.error;
                } else if (data.message) {
                    errorMessage = data.message;
                } else {
                    errorMessage = `Server error (${response.status}): ${response.statusText}`;
                }
                
                console.error('Checkout error:', errorMessage, data);
                alert('Error: ' + errorMessage);
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
                return;
            }

            if (data.error) {
                console.error('Checkout error:', data.error);
                alert('Error: ' + data.error);
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
                return;
            }

            if (!data.clientSecret || !data.orderId) {
                console.error('Invalid response data:', data);
                alert('Error: Invalid response from server. Missing payment information.');
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
                return;
            }

            let paymentResult;
            try {
                paymentResult = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: {
                        card: cardElement,
                    }
                });
            } catch (stripeError) {
                console.error('Stripe confirmation error:', stripeError);
                document.getElementById('card-errors').textContent = stripeError.message || 'Payment confirmation failed';
                alert('Error: ' + (stripeError.message || 'Payment confirmation failed'));
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
                return;
            }

            const { error, paymentIntent } = paymentResult;

            if (error) {
                console.error('Stripe payment error:', error);
                document.getElementById('card-errors').textContent = error.message;
                alert('Payment Error: ' + error.message);
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
                return;
            }

            if (paymentIntent && (paymentIntent.status === 'succeeded' || paymentIntent.status === 'requires_capture')) {
                // Payment succeeded, confirm on server
                try {
                    const confirmResponse = await fetch('{{ url('/checkout/confirm') }}/' + data.orderId, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_intent_id: paymentIntent.id,
                            status: paymentIntent.status
                        })
                    });

                    const confirmData = await confirmResponse.json();

                    if (confirmResponse.ok && confirmData.success) {
                        // Redirect to confirmation page
                        if (confirmData.redirect) {
                            window.location.href = confirmData.redirect;
                        } else {
                            window.location.href = '{{ url('/orders') }}/' + data.orderId + '/confirmation';
                        }
                    } else {
                        const errorMsg = confirmData.error || 'Unknown error occurred';
                        console.error('Confirmation error:', errorMsg);
                        alert('Error confirming payment: ' + errorMsg);
                        submitButton.disabled = false;
                        submitButton.textContent = 'Complete Order';
                    }
                } catch (confirmError) {
                    console.error('Confirmation error:', confirmError);
                    alert('Payment succeeded but confirmation failed. Please contact support with order ID: ' + data.orderId + '. Error: ' + confirmError.message);
                    submitButton.disabled = false;
                    submitButton.textContent = 'Complete Order';
                }
            } else {
                document.getElementById('card-errors').textContent = 'Payment status: ' + (paymentIntent?.status || 'unknown');
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
            }
        } catch (error) {
            console.error('Unexpected error:', error);
            let errorMessage = 'An unexpected error occurred. Please try again.';
            if (error.message) {
                errorMessage = error.message;
            } else if (error.toString) {
                errorMessage = error.toString();
            }
            console.error('Error details:', {
                message: error.message,
                stack: error.stack,
                name: error.name
            });
            alert('Error: ' + errorMessage);
            submitButton.disabled = false;
            submitButton.textContent = 'Complete Order';
        }
    });
});
@endif
</script>
@endsection
