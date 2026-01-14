<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DJ Dashboard') - Disk Jockey Global</title>
    
    @include('layouts.web.partials.head')
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        .sidebar-link.active {
            background: linear-gradient(135deg, #FFD900 0%, #FFA500 100%);
            color: #000;
        }
        .sidebar-link:hover:not(.active) {
            background-color: rgba(255, 217, 0, 0.1);
            color: #FFD900;
        }
        /* Custom scrollbar for sidebar */
        nav::-webkit-scrollbar {
            width: 6px;
        }
        nav::-webkit-scrollbar-track {
            background: #1F1F1F;
        }
        nav::-webkit-scrollbar-thumb {
            background: #282828;
            border-radius: 3px;
        }
        nav::-webkit-scrollbar-thumb:hover {
            background: #353535;
        }
        /* Ensure sidebar stays on top on mobile */
        @media (max-width: 1023px) {
            #sidebar {
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
            }
        }
    </style>
</head>
<body class="bg-[#161616]">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-[#1F1F1F] border-r border-[#282828] flex flex-col fixed lg:static h-full z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <!-- Logo -->
            <div class="p-4 sm:p-6 border-b border-[#282828] flex items-center justify-between">
                <a href="{{ route('dj.dashboard') }}" class="">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 sm:h-10 w-auto">
                    <span class="text-white font-medium text-[12px] hidden sm:inline">DJ Dashboard</span>
                </a>
                <!-- Close button for mobile -->
                <button id="closeSidebar" class="lg:hidden text-gray-400 hover:text-white transition-colors p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-3 sm:p-4 scrollbar-thin scrollbar-thumb-[#282828] scrollbar-track-[#1F1F1F]">
                <ul class="space-y-1 sm:space-y-2">
                    <li>
                        <a href="{{ route('dj.dashboard') }}" 
                           class="sidebar-link flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-white transition-all text-sm sm:text-base {{ request()->routeIs('dj.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-semibold truncate">Dashboard</span>
                        </a>
                    </li>
                    @if(Auth::user()->dj)
                    <li>
                        <a href="{{ route('dj.dashboard.profile') }}" 
                           class="sidebar-link flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-white transition-all text-sm sm:text-base {{ request()->routeIs('dj.dashboard.profile') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-semibold truncate">My Profile</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ route('dj.dashboard.edit') }}" 
                           class="sidebar-link flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-white transition-all text-sm sm:text-base {{ request()->routeIs('dj.dashboard.edit') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="font-semibold truncate">Edit Profile</span>
                        </a>
                    </li> -->
                    @endif
                    <li>
                        <a href="{{ route('dj.dashboard.bookings') }}" 
                           class="sidebar-link flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-3 rounded-lg text-white transition-all text-sm sm:text-base {{ request()->routeIs('dj.dashboard.bookings*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold truncate">My Bookings</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Section -->
            <div class="p-3 sm:p-4 border-t border-[#282828]">
                <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4">
                    @if(Auth::user()->dj && Auth::user()->dj->profile_image)
                        <img src="{{ asset('storage/' . Auth::user()->dj->profile_image) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#FFD900] flex items-center justify-center text-[#333333] font-bold text-sm sm:text-base">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-semibold text-xs sm:text-sm truncate">{{ Auth::user()->dj->stage_name ?? Auth::user()->name }}</p>
                        <p class="text-gray-400 text-xs truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('dj.dashboard.profile') }}" class="flex-1 px-3 py-2 bg-[#282828] text-white text-xs sm:text-sm rounded-lg hover:bg-[#353535] transition-colors text-center">
                        Profile
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2 bg-red-500/20 text-red-400 text-xs sm:text-sm rounded-lg hover:bg-red-500/30 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Bar -->
            <header class="bg-[#1F1F1F] border-b border-[#282828] px-4 sm:px-6 py-4 relative">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-1">
                        <!-- Mobile Menu Toggle (Hidden on desktop) -->
                        <button id="mobileMenuToggle" class="lg:hidden bg-[#282828] text-white p-2 rounded-lg hover:bg-[#353535] transition-colors flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div class="flex-1">
                            <h1 class="text-xl sm:text-2xl font-bold text-white">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-gray-400 text-xs sm:text-sm">@yield('page-description', 'Welcome to your DJ dashboard')</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4">
                        @if(Auth::user()->dj)
                        <a href="{{ route('dj.show', Auth::user()->dj->id) }}" target="_blank" class="text-gray-400 hover:text-[#FFD900] transition-colors" title="View Public Profile">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                        @endif
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-[#FFD900] transition-colors" title="Go to Home">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-[#161616] p-4 sm:p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black/50 z-20 hidden transition-opacity duration-300"></div>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const body = document.body;

        function openSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                body.style.overflow = 'hidden'; // Prevent body scroll when sidebar is open
            }
        }

        function closeSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                body.style.overflow = ''; // Restore body scroll
            }
        }

        menuToggle?.addEventListener('click', function(e) {
            e.stopPropagation();
            if (sidebar?.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        // Close sidebar button
        const closeSidebarBtn = document.getElementById('closeSidebar');
        closeSidebarBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            closeSidebar();
        });

        overlay?.addEventListener('click', function() {
            closeSidebar();
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 1024) {
                if (sidebar && !sidebar.contains(e.target) && !menuToggle?.contains(e.target)) {
                    closeSidebar();
                }
            }
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                // On desktop, ensure sidebar is visible
                if (sidebar) {
                    sidebar.classList.remove('-translate-x-full');
                }
                if (overlay) {
                    overlay.classList.add('hidden');
                }
                body.style.overflow = '';
            } else {
                // On mobile, close sidebar if open
                closeSidebar();
            }
        });
    </script>
</body>
</html>
