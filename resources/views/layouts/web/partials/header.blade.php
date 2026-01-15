<!-- Premium Header Section -->
<header class="bg-[#000000] text-white py-4 md:py-6 px-4 md:px-8 lg:px-16 flex items-center justify-between sticky top-0 z-50 shadow-2xl">
    <!-- Logo Section -->
     <a href="/" class="flex items-center group">
        <img src="{{ asset('images/logo.png') }}" alt="Disk Jockey Global Logo" class="header-logo h-10 md:h-12 w-auto">
     </a>

    <!-- Navigation Menu (Desktop) -->
    <nav class="hidden lg:flex items-center space-x-4 xl:space-x-8">
        <a href="{{ url('/') }}" class="header-desktop-nav">Home</a>
        <a href="{{ url('/about-us') }}" class="header-desktop-nav">About Us</a>
        <a href="{{ url('/browse') }}" class="header-desktop-nav">Browse DJ / MC</a>
        <a href="{{ url('/how-it-works') }}" class="header-desktop-nav">How It Works</a>
        <a href="{{ url('/gallery') }}" class="header-desktop-nav text-nowrap">Gallery</a>
        <a href="{{ url('/merch') }}" class="header-desktop-nav text-nowrap">Merch</a>
        <a href="{{ url('/contact') }}" class="header-desktop-nav">Contact</a>
    </nav>

    <!-- CTAs -->
    <div class="flex items-center space-x-4 md:space-x-6 xl:space-x-10">
        @php
            $cart = session()->get('cart', []);
            $cartCount = 0;
            $cartItems = [];
            $cartTotal = 0;
            
            foreach ($cart as $key => $item) {
                if (isset($item['product_id']) && isset($item['quantity'])) {
                    $product = \App\Models\Product::with('productCategory')->find($item['product_id']);
                    if ($product && $product->is_active) {
                        $variation = null;
                        $price = $product->current_price;
                        
                        if ($product->isVariable() && isset($item['variation_id'])) {
                            $variation = \App\Models\ProductVariation::with('attributes')->find($item['variation_id']);
                            if ($variation && $variation->product_id == $product->id) {
                                $price = $variation->current_price;
                            }
                        }
                        
                        $cartCount += $item['quantity'];
                        $subtotal = $price * $item['quantity'];
                        $cartTotal += $subtotal;
                        
                        $cartItems[] = [
                            'cart_key' => $key,
                            'product' => $product,
                            'variation' => $variation,
                            'quantity' => $item['quantity'],
                            'price' => $price,
                            'subtotal' => $subtotal
                        ];
                    }
                }
            }
        @endphp

        <!-- Cart Icon with Dropdown -->
        <div class="relative group" id="cartDropdown">
            <a href="{{ route('cart.index') }}" class="relative flex items-center text-white hover:text-[#FFD900] transition-colors">
                <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-[#FFD900] text-[#333333] text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center min-w-[20px]">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                @endif
            </a>
            
            <!-- Cart Dropdown -->
            @if($cartCount > 0)
            <div id="cartDropdownMenu" class="hidden lg:group-hover:block absolute right-0 mt-2 w-80 md:w-96 bg-[#1F1F1F] border border-[#282828] rounded-lg shadow-xl z-50 max-h-[600px] overflow-y-auto">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#282828]">
                        <h3 class="text-white font-bold text-lg">Shopping Cart</h3>
                        <span class="text-[#FFD900] text-sm">{{ $cartCount }} {{ $cartCount == 1 ? 'item' : 'items' }}</span>
                    </div>
                    
                    <div class="space-y-3 mb-4 max-h-[400px] overflow-y-auto">
                        @foreach(array_slice($cartItems, 0, 5) as $item)
                        <div class="flex gap-3 p-2 bg-[#161616] rounded-lg">
                            <img src="{{ $item['variation'] && $item['variation']->image ? $item['variation']->image_url : $item['product']->image_url }}" 
                                 alt="{{ $item['product']->name }}" 
                                 class="w-16 h-16 object-cover rounded">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-white text-sm font-semibold truncate">{{ $item['product']->name }}</h4>
                                @if($item['variation'] && $item['variation']->attributes->count() > 0)
                                    <p class="text-gray-400 text-xs mt-1">
                                        @foreach($item['variation']->attributes as $attr)
                                            {{ ucfirst($attr->attribute_name) }}: {{ $attr->attribute_value }}
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    </p>
                                @endif
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-gray-400 text-xs">Qty: {{ $item['quantity'] }}</span>
                                    <span class="text-[#FFD900] font-bold text-sm">${{ number_format($item['subtotal'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @if(count($cartItems) > 5)
                            <p class="text-gray-400 text-xs text-center py-2">
                                +{{ count($cartItems) - 5 }} more item(s)
                            </p>
                        @endif
                    </div>
                    
                    <div class="pt-3 border-t border-[#282828]">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-white font-semibold">Subtotal:</span>
                            <span class="text-[#FFD900] font-bold text-lg">${{ number_format($cartTotal, 2) }}</span>
                        </div>
                        <a href="{{ route('cart.index') }}" class="block w-full bg-[#FFD900] text-[#333333] text-center py-2 rounded-lg font-bold hover:bg-[#FFA500] transition-colors mb-2">
                            View Cart
                        </a>
                        @auth
                            <a href="{{ route('checkout.index') }}" class="block w-full bg-[#1F1F1F] border border-[#282828] text-white text-center py-2 rounded-lg font-semibold hover:bg-[#282828] transition-colors">
                                Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full bg-[#1F1F1F] border border-[#282828] text-white text-center py-2 rounded-lg font-semibold hover:bg-[#282828] transition-colors">
                                Login to Checkout
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endif
        </div>

        @auth
            <!-- User Dashboard Dropdown (Desktop) -->
            <div class="hidden lg:block relative group">
                <button class="flex items-center space-x-2 text-[14px] md:text-[18px] font-semibold hover:text-[#FFD900] transition-colors duration-300">
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-[#1F1F1F] border border-[#282828] rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                    <div class="py-2">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-white hover:bg-[#282828] hover:text-[#FFD900] transition-colors">
                                Admin Dashboard
                            </a>
                        @elseif(Auth::user()->isDJ())
                            <a href="{{ route('dj.dashboard') }}" class="block px-4 py-2 text-white hover:bg-[#282828] hover:text-[#FFD900] transition-colors">
                                DJ Dashboard
                            </a>
                        @else
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-white hover:bg-[#282828] hover:text-[#FFD900] transition-colors">
                                My Dashboard
                            </a>
                        @endif
                        <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-white hover:bg-[#282828] hover:text-[#FFD900] transition-colors">
                            My Bookings
                        </a>
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-white hover:bg-[#282828] hover:text-[#FFD900] transition-colors">
                            My Profile
                        </a>
                        <hr class="border-[#282828] my-1">
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-white hover:bg-[#282828] hover:text-[#FFD900] transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Sign Up -->
            <a href="{{ url('/signup') }}" class="hidden lg:block text-[14px] md:text-[18px] font-semibold hover:text-[#FFD900] transition-colors duration-300">
                Sign Up
            </a>

            <!-- Login Button -->
            <a href="{{ url('/login') }}" class="hidden lg:block btn primary-button ">
                Login
            </a>
        @endauth

        <!-- Mobile Menu Toggle -->
        <button id="menuBtn" class="lg:hidden block focus:outline-none">
            <span id="menuIcon" class="text-3xl text-[var(--text-white)]">☰</span>
        </button>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobileMenu" class="hidden fixed inset-0 bg-[#000000] text-[#FFFFFF] z-40 pt-32 px-10">
    <nav class="flex flex-col space-y-4">
        <a href="{{ url('/') }}" class="mobile-menu-nav">Home</a>
        <a href="{{ url('/about-us') }}" class="mobile-menu-nav">About Us</a>
        <a href="{{ url('/browse') }}" class="mobile-menu-nav">Browse DJ / MC</a>
        <a href="{{ url('/how-it-works') }}" class="mobile-menu-nav">How It Works</a>
        <a href="{{ url('/gallery') }}" class="mobile-menu-nav">Gallery</a>
        <a href="{{ url('/merch') }}" class="mobile-menu-nav">Merch</a>
        <a href="{{ url('/contact') }}" class="mobile-menu-nav">Contact</a>
        
        <!-- Mobile Cart Link -->
        <a href="{{ route('cart.index') }}" class="mobile-menu-nav flex items-center justify-between">
            <span>Cart</span>
            @if($cartCount > 0)
                <span class="bg-[#FFD900] text-[#333333] text-xs font-bold rounded-full px-2 py-1">
                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                </span>
            @endif
        </a>
        
        <div class="flex flex-col space-y-4 pt-4">
            @auth
                <div class="text-[16px] font-semibold text-[#FFD900] mb-2">{{ Auth::user()->name }}</div>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-[16px] font-semibold text-[#FFD900]">Admin Dashboard</a>
                @elseif(Auth::user()->isDJ())
                    <a href="{{ route('dj.dashboard') }}" class="text-[16px] font-semibold text-[#FFD900]">DJ Dashboard</a>
                @else
                    <a href="{{ route('profile.show') }}" class="text-[16px] font-semibold text-[#FFD900]">My Dashboard</a>
                @endif
                <a href="{{ route('bookings.index') }}" class="text-[16px] font-semibold text-[#FFD900]">My Bookings</a>
                <a href="{{ route('profile.show') }}" class="text-[16px] font-semibold text-[#FFD900]">My Profile</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-[16px] font-semibold bg-[#FFD900] text-[#333333] text-center py-3">Logout</button>
                </form>
            @else
                <a href="{{ url('/signup') }}" class="text-[16px] font-semibold text-[#FFD900]">Sign Up</a>
                <a href="{{ url('/login') }}" class="text-[16px] font-semibold bg-[#FFD900] text-[#333333] text-center py-3">Login</a>
            @endauth
        </div>
    </nav>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuBtn = document.getElementById("menuBtn");
        const mobileMenu = document.getElementById("mobileMenu");
        const menuIcon = document.getElementById("menuIcon");

        menuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
            
            if (mobileMenu.classList.contains("hidden")) {
                menuIcon.innerText = "☰";
                document.body.style.overflow = "auto";
            } else {
                menuIcon.innerText = "✕";
                document.body.style.overflow = "hidden";
            }
        });

        // Close menu when a link is clicked
        const links = mobileMenu.querySelectorAll('nav a');
        links.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIcon.innerText = "☰";
                document.body.style.overflow = "auto";
            });
        });

        // Cart dropdown toggle (for mobile/tablet)
        const cartDropdown = document.getElementById('cartDropdown');
        const cartDropdownMenu = document.getElementById('cartDropdownMenu');
        
        if (cartDropdown && cartDropdownMenu) {
            const cartButton = cartDropdown.querySelector('button');
            
            // Toggle on click for mobile/tablet
            cartButton.addEventListener('click', (e) => {
                if (window.innerWidth < 1024) {
                    e.preventDefault();
                    cartDropdownMenu.classList.toggle('hidden');
                } else {
                    // On desktop, redirect to cart page on click
                    window.location.href = '{{ route('cart.index') }}';
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!cartDropdown.contains(e.target) && window.innerWidth < 1024) {
                    cartDropdownMenu.classList.add('hidden');
                }
            });
        }
    });
</script>


