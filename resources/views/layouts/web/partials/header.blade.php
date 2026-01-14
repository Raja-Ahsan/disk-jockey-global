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
        <!-- Sign Up -->
        <a href="{{ url('/signup') }}" class="hidden lg:block text-[14px] md:text-[18px] font-semibold hover:text-[#FFD900] transition-colors duration-300">
            Sign Up
        </a>

        <!-- Login Button -->
        <a href="{{ url('/login') }}" class="hidden lg:block btn primary-button ">
            Login
        </a>

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
            <a href="{{ url('/signup') }}" class="text-[16px] font-semibold text-[#FFD900]">Sign Up</a>
            <a href="{{ url('/login') }}" class="text-[16px] font-semibold bg-[#FFD900] text-[#333333] text-center py-3">Login</a>
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


