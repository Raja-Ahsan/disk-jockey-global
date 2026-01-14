<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Disk Jockey Global</title>
    
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
    </style>
</head>
<body class="bg-[#161616]">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#1F1F1F] border-r border-[#282828] flex flex-col fixed lg:static h-full z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <!-- Logo -->
            <div class="p-6 border-b border-[#282828]">
                <a href="{{ route('admin.dashboard') }}" class="">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                    <span class="text-white font-bold text-sm">Admin Panel</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.djs.index') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white transition-all {{ request()->routeIs('admin.djs.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-semibold">Manage DJs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white transition-all {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold">Manage Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.index') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white transition-all {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-semibold">Manage Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white transition-all {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-semibold">Manage Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.product-categories.index') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white transition-all {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="font-semibold">Product Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}" 
                           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white transition-all {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="font-semibold">Manage Orders</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-[#282828]">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-[#FFD900] flex items-center justify-center text-[#333333] font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-semibold text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-gray-400 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('profile.show') }}" class="flex-1 px-3 py-2 bg-[#282828] text-white text-sm rounded-lg hover:bg-[#353535] transition-colors text-center">
                        Profile
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2 bg-red-500/20 text-red-400 text-sm rounded-lg hover:bg-red-500/30 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <!-- Top Bar -->
            <header class="bg-[#1F1F1F] border-b border-[#282828] px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-gray-400 text-sm">@yield('page-description', 'Welcome to the admin panel')</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" target="_blank" class="text-gray-400 hover:text-[#FFD900] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-[#161616] p-6">
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

    <!-- Mobile Menu Toggle (Hidden on desktop) -->
    <button id="mobileMenuToggle" class="lg:hidden fixed top-4 left-4 z-40 bg-[#1F1F1F] text-white p-2 rounded-lg border border-[#282828]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="lg:hidden fixed inset-0 bg-black/50 z-20 hidden"></div>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.querySelector('aside');
        const overlay = document.getElementById('mobileOverlay');

        menuToggle?.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay?.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>
</body>
</html>
