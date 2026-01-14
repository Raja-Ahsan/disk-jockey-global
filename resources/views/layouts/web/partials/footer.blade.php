<footer class="bg-[#191919] text-white pt-20">
    <!-- Main Content Container with Padding -->
    <div class="px-6 lg:px-16 pb-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            <!-- Brand Section -->
            <div class="flex flex-col space-y-6">
                <a href="/" class="flex items-center -ml-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Disk Jockey Global Logo" class="h-14 w-auto">
                </a>
                <h3 class="text-[28px] font-bold leading-[1.4]">
                    Your Trusted DJ / MC Booking Platform
                </h3>
                <p class="text-[var(--text-white)] font-normal text-[18px] leading-[1.4] max-w-xs">
                    Disk Jockey Global is a centralized entertainment booking platform connecting professional DJs and MCs with events across the United States.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="flex flex-col space-y-6 lg:pl-10">
                <h4 class="footer-h4">Quick Links</h4>
                <ul class="flex flex-col space-y-4">
                    <li><a href="{{ url('/') }}" class="header-desktop-nav">Home</a></li>
                    <li><a href="{{ url('/about') }}" class="header-desktop-nav">About Us</a></li>
                    <li><a href="{{ url('/browse') }}" class="header-desktop-nav">Browse DJs & MCs</a></li>
                    <li><a href="{{ url('/how-it-works') }}" class="header-desktop-nav">How It Works</a></li>
                    <li><a href="{{ url('/gallery') }}" class="header-desktop-nav">Gallery</a></li>
                    <li><a href="{{ url('/contact') }}" class="header-desktop-nav">Contact</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div class="flex flex-col space-y-6 lg:pl-10">
                <h4 class="footer-h4">Legal</h4>
                <ul class="flex flex-col space-y-4">
                    <li><a href="#" class="header-desktop-nav">Terms & Conditions</a></li>
                    <li><a href="#" class="header-desktop-nav">Privacy Policy</a></li>
                    <li><a href="#" class="header-desktop-nav">Refund Policy</a></li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="flex flex-col space-y-6 lg:pl-6">
                <h4 class="footer-h4">Contact</h4>
                <div class="flex flex-col space-y-5">
                    <!-- Phone -->
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/footer-phone-icon.png') }}" alt="Phone" class="w-10 h-10">
                        <a href="tel:+18149677641" class="header-desktop-nav !font-bold">
                            +1 814-967-7641
                        </a>
                    </div>
                    <!-- Email -->
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/footer-mail-icon.png') }}" alt="Email" class="w-10 h-10">
                        <a href="mailto:diskjockeygloball@gmail.com" class="header-desktop-nav !font-bold">
                            diskjockeygloball@gmail.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="py-6 bg-[#202020] w-full text-center">
        <p class="text-[var(--text-white)] text-[14px]">
            &copy; 2025 Disk Jockey Global. All rights reserved.
        </p>
    </div>
</footer>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: false,
  });
</script>
</body>
</html>