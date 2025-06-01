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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-yellow-600 to-yellow-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <i class="fas fa-gem text-white text-2xl mr-3"></i>
                            <h1 class="text-white text-xl font-bold">Gold Jewelry Management</h1>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="flex items-center text-white hover:text-yellow-200 transition">
                                <span class="mr-2">{{ auth()->user()->name }}</span>
                                <i class="fas fa-user-circle text-xl"></i>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-yellow-200 transition">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 min-h-screen bg-white shadow-lg">
                <nav class="mt-8">
                    <div class="px-4">
                        <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Main Navigation</p>
                    </div>
                    
                    <div class="mt-4 space-y-1">
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5"></i>
                            <span>Customers</span>
                        </a>
                        
                        <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list w-5"></i>
                            <span>Orders & Estimates</span>
                        </a>
                        
                        <a href="{{ route('items.index') }}" class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                            <i class="fas fa-ring w-5"></i>
                            <span>Items & Stock</span>
                        </a>
                        
                        <a href="{{ route('sales.index') }}" class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span>Sales</span>
                        </a>
                        
                        <a href="{{ route('gold-purchases.index') }}" class="sidebar-link {{ request()->routeIs('gold-purchases.*') ? 'active' : '' }}">
                            <i class="fas fa-coins w-5"></i>
                            <span>Gold Purchase</span>
                        </a>
                    </div>

                    <div class="px-4 mt-8">
                        <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Reports</p>
                    </div>
                    
                    <div class="mt-4 space-y-1">
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span>Sales Reports</span>
                        </a>
                        
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-warehouse w-5"></i>
                            <span>Inventory Reports</span>
                        </a>
                        
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-file-invoice-dollar w-5"></i>
                            <span>Financial Reports</span>
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-8">
                <!-- Breadcrumb -->
                @if(isset($breadcrumbs))
                <nav class="mb-6">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        @foreach($breadcrumbs as $breadcrumb)
                            @if(!$loop->last)
                                <li>
                                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-yellow-600">{{ $breadcrumb['label'] }}</a>
                                </li>
                                <li><i class="fas fa-chevron-right text-gray-400"></i></li>
                            @else
                                <li class="text-gray-900 font-medium">{{ $breadcrumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
                @endif

                <!-- Page Header -->
                @if(isset($pageTitle))
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $pageTitle }}</h1>
                    @if(isset($pageDescription))
                        <p class="mt-2 text-gray-600">{{ $pageDescription }}</p>
                    @endif
                </div>
                @endif

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                <!-- Main Content Area -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    @stack('scripts')

    <style>
        .sidebar-link {
            @apply flex items-center px-4 py-3 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition-colors duration-200;
        }
        
        .sidebar-link.active {
            @apply bg-yellow-100 text-yellow-700 border-r-4 border-yellow-600;
        }
        
        .sidebar-link i {
            @apply mr-3;
        }
        
        .btn-primary {
            @apply bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .form-input {
            @apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500;
        }
        
        .form-select {
            @apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500;
        }
        
        .card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200;
        }
        
        .card-header {
            @apply px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg;
        }
        
        .card-body {
            @apply p-6;
        }
    </style>
</body>
</html>