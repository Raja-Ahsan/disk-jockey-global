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
    });
</script>


