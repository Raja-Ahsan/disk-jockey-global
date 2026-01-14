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
                                    <input type="text" name="shipping_country" value="{{ old('shipping_country', 'US') }}" required
                                           class="w-full bg-[#161616] border border-[#282828] text-white p-3 rounded-lg focus:border-[#FFD900] focus:outline-none">
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
                                <span class="text-gray-400">{{ $item['product']->name }} × {{ $item['quantity'] }}</span>
                                <span class="text-white">${{ number_format($item['subtotal'], 2) }}</span>
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

            const data = await response.json();

            if (data.error) {
                alert('Error: ' + data.error);
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
                return;
            }

            const { error, paymentIntent } = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardElement,
                }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Complete Order';
            } else if (paymentIntent.status === 'succeeded') {
                const confirmForm = document.createElement('form');
                confirmForm.method = 'POST';
                confirmForm.action = '{{ url('/checkout/confirm') }}/' + data.orderId;
                confirmForm.style.display = 'none';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                confirmForm.appendChild(csrfInput);
                
                document.body.appendChild(confirmForm);
                confirmForm.submit();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitButton.disabled = false;
            submitButton.textContent = 'Complete Order';
        }
    });
});
@endif
</script>
@endsection
