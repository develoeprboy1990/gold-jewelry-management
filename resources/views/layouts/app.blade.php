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
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Top Navigation -->
        <nav class="nav-gradient shadow-lg border-b border-yellow-800">
            <div class="max-w-full mx-auto px-6">
                <div class="flex justify-between h-16">
                    <!-- Left side - Logo and Title -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-gem text-yellow-700 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-white text-xl font-bold">Gold Jewelry Management</h1>
                                <p class="text-yellow-100 text-xs">Professional Jewelry Store System</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right side - User Menu -->
                    <div class="flex items-center space-x-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-white hover:text-yellow-200 transition">
                                <span class="mr-2">{{ auth()->user()->name }}</span>
                                <i class="fas fa-user-circle text-xl"></i>
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                @click.away="open = false"
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
            <!-- Sidebar -->
            <aside class="w-64 min-h-screen bg-white shadow-lg border-r border-gray-200">
                <div class="p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full mx-auto flex items-center justify-center mb-3">
                            <i class="fas fa-store text-white text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Golden Jewellers</h3>
                        <p class="text-xs text-gray-500">Est. 2020</p>
                    </div>
                </div>

                <nav class="px-4">
                    <div class="space-y-1">
                        <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold px-3 mb-3">Main Navigation</p>
                        
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5"></i>
                            <span>Customers</span>
                        </a>
                        
                        <a href="{{ route('items.index') }}" class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                            <i class="fas fa-ring w-5"></i>
                            <span>Items & Stock</span>
                        </a>
                        
                        <a href="{{ route('sales.index') }}" class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span>Sales</span>
                        </a>
                        
                        <!--<a href="{{ route('gold-purchases.index') }}" class="sidebar-link {{ request()->routeIs('gold-purchases.*') ? 'active' : '' }}">
                            <i class="fas fa-coins w-5"></i>
                            <span>Gold Purchase</span>
                        </a>-->
                    </div>

                    <!--<div class="px-4 mt-8">
                        <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Reports</p>
                    </div>

                    <div class="mt-4 space-y-1">
                        <a href="{{ route('reports.sales.index') }}" class="sidebar-link {{ request()->routeIs('reports.sales.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span>Sales Reports</span>
                        </a>
                        
                        <a href="{{ route('reports.inventory.index') }}" class="sidebar-link {{ request()->routeIs('reports.inventory.*') ? 'active' : '' }}">
                            <i class="fas fa-warehouse w-5"></i>
                            <span>Inventory Reports</span>
                        </a>
                        
                        <a href="{{ route('reports.financial.index') }}" class="sidebar-link {{ request()->routeIs('reports.financial.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar w-5"></i>
                            <span>Financial Reports</span>
                        </a>
                    </div>-->
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden">
                <!-- Page Header -->
                <div class="bg-white border-b border-gray-200 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-gray-600 text-sm mt-1">@yield('page-description', 'Welcome to your jewelry management system')</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div class="px-8 py-4">
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
                <div class="px-8 pb-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    @stack('scripts')
</body>
</html>