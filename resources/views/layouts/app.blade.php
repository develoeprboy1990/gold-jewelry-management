<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gold Jewelry Management') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false, userMenuOpen: false }">
    <div class="min-h-screen">
        <!-- Mobile Menu Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 lg:hidden"
             @click="sidebarOpen = false"
             style="display: none;"></div>

        <!-- Top Navigation -->
        <nav class="nav-gradient shadow-lg border-b border-yellow-800 relative z-40">
            <div class="max-w-full mx-auto px-4 sm:px-6">
                <div class="flex justify-between h-16">
                    <!-- Left side - Mobile Menu + Logo -->
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="lg:hidden text-white hover:text-yellow-200 transition p-2 -ml-2">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <div class="flex-shrink-0 flex items-center ml-2 lg:ml-0">
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                <i class="fas fa-gem text-yellow-700 text-lg lg:text-xl"></i>
                            </div>
                            <div class="hidden sm:block">
                                <h1 class="text-white text-lg lg:text-xl font-bold">Gold Jewelry Management</h1>
                                <p class="text-yellow-100 text-xs hidden lg:block">Professional Jewelry Store System</p>
                            </div>
                            <div class="block sm:hidden">
                                <h1 class="text-white text-lg font-bold">GJM</h1>
                            </div>
                        </div>
                    </div>

                    <!-- Right side - User Menu -->
                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <!-- Notifications (Mobile) -->
                        <button class="lg:hidden text-white hover:text-yellow-200 transition p-2">
                            <i class="fas fa-bell text-lg"></i>
                        </button>
                        
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    class="flex items-center text-white hover:text-yellow-200 transition px-2 py-1 rounded">
                                <span class="mr-2 hidden sm:block text-sm lg:text-base">{{ auth()->user()->name }}</span>
                                <i class="fas fa-user-circle text-lg lg:text-xl"></i>
                                <i class="fas fa-chevron-down ml-1 text-xs hidden sm:block"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="userMenuOpen" 
                                @click.away="userMenuOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                                style="display: none;">
                                
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profile Settings
                                </a>
                                
                                <div class="border-t border-gray-100"></div>
                                
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Mobile Sidebar -->
            <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   x-cloak>
                
                <!-- Sidebar Header -->
                <div class="p-4 lg:p-6 border-b border-gray-200 lg:border-none">
                    <div class="flex items-center justify-between lg:justify-center">
                        <div class="text-center">
                            <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full mx-auto flex items-center justify-center mb-2 lg:mb-3">
                                <i class="fas fa-store text-white text-lg lg:text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 text-sm lg:text-base">Golden Jewellers</h3>
                            <p class="text-xs text-gray-500">Est. 2020</p>
                        </div>
                        <!-- Close button for mobile -->
                        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 p-1">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="px-3 lg:px-4 mt-4">
                    <div class="space-y-1">
                        <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold px-3 mb-3">Main Navigation</p>
                        
                        <a href="{{ route('dashboard') }}" 
                           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           @click="sidebarOpen = false">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('customers.index') }}" 
                           class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                           @click="sidebarOpen = false">
                            <i class="fas fa-users w-5"></i>
                            <span>Customers</span>
                        </a>
                        
                        <a href="{{ route('items.index') }}" 
                           class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}"
                           @click="sidebarOpen = false">
                            <i class="fas fa-ring w-5"></i>
                            <span>Items & Stock</span>
                        </a>
                        
                        <a href="{{ route('sales.index') }}" 
                           class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                           @click="sidebarOpen = false">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span>Sales</span>
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0 lg:ml-0">
                <!-- Page Header -->
                <div class="bg-white border-b border-gray-200 px-4 py-4 lg:px-8 lg:py-6">
                    <div class="flex flex-col space-y-2 lg:flex-row lg:items-center lg:justify-between lg:space-y-0">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-900 truncate">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-gray-600 text-sm mt-1">@yield('page-description', 'Welcome to your jewelry management system')</p>
                        </div>
                        <div class="flex items-center space-x-2 lg:space-x-3 flex-shrink-0">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div class="px-4 py-4 lg:px-8">
                    @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Page Content -->
                <div class="px-4 pb-4 lg:px-8 lg:pb-8">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Mobile Bottom Navigation (Optional Alternative) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 z-30" style="display: none;" id="bottom-nav">
            <div class="flex justify-around">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-yellow-600">
                    <i class="fas fa-home text-lg mb-1"></i>
                    <span class="text-xs">Home</span>
                </a>
                <a href="{{ route('customers.index') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-yellow-600">
                    <i class="fas fa-users text-lg mb-1"></i>
                    <span class="text-xs">Customers</span>
                </a>
                <a href="{{ route('sales.create') }}" class="flex flex-col items-center py-2 px-3 text-white bg-yellow-600 rounded-lg">
                    <i class="fas fa-plus text-lg mb-1"></i>
                    <span class="text-xs">Sale</span>
                </a>
                <a href="{{ route('items.index') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-yellow-600">
                    <i class="fas fa-ring text-lg mb-1"></i>
                    <span class="text-xs">Items</span>
                </a>
                <a href="{{ route('sales.index') }}" class="flex flex-col items-center py-2 px-3 text-gray-600 hover:text-yellow-600">
                    <i class="fas fa-chart-bar text-lg mb-1"></i>
                    <span class="text-xs">Reports</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    @stack('scripts')

    <script>
        // Close mobile sidebar when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-close sidebar on route changes for mobile
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        // Close sidebar on mobile after link click
                        setTimeout(() => {
                            document.querySelector('[x-data]').__x.$data.sidebarOpen = false;
                        }, 100);
                    }
                });
            });

            // Optional: Enable bottom navigation
            // document.getElementById('bottom-nav').style.display = 'block';
        });

        // Handle responsive behavior
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                // Auto-close mobile sidebar on desktop
                if (document.querySelector('[x-data]').__x) {
                    document.querySelector('[x-data]').__x.$data.sidebarOpen = false;
                }
            }
        });
    </script>
</body>
</html>