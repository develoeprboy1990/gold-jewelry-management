@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your jewelry business')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('sales.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>
        New Sale
    </a>
    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
        <i class="fas fa-download mr-2"></i>
        Export Data
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Customers -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
                    <p class="text-xs text-green-600 flex items-center mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +12% from last month
                    </p>
                </div>
            </div>
        </div>

        <!-- Items in Stock -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-ring text-green-600 text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Items in Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_items']) }}</p>
                    @if($stats['low_stock_items'] > 0)
                        <p class="text-xs text-red-600 flex items-center mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ $stats['low_stock_items'] }} low stock
                        </p>
                    @else
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-check mr-1"></i>
                            All items stocked
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Today's Sales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['todays_sales']) }}</p>
                    <p class="text-xs text-blue-600 flex items-center mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        Updated live
                    </p>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-purple-100">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">PKR {{ number_format($stats['monthly_revenue']) }}</p>
                    <p class="text-xs text-green-600 flex items-center mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +8.2% vs last month
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-bolt mr-2 text-yellow-600"></i>
                Quick Actions
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <a href="{{ route('customers.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105">
                    <div class="flex items-center">
                        <i class="fas fa-user-plus text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Add Customer</h3>
                            <p class="text-sm opacity-90">Create new customer profile</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('items.create') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105">
                    <div class="flex items-center">
                        <i class="fas fa-plus text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Add Item</h3>
                            <p class="text-sm opacity-90">Add jewelry to inventory</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('sales.create') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-4 rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all transform hover:scale-105">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold">New Sale</h3>
                            <p class="text-sm opacity-90">Process jewelry sale</p>
                        </div>
                    </div>
                </a>
                
                <!--<a href="{{ route('gold-purchases.create') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all transform hover:scale-105">
                    <div class="flex items-center">
                        <i class="fas fa-coins text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Gold Purchase</h3>
                            <p class="text-sm opacity-90">Buy gold from customers</p>
                        </div>
                    </div>
                </a>-->
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Sales -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-history mr-2 text-blue-600"></i>
                        Recent Sales
                    </h2>
                    <a href="{{ route('sales.index') }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">View All →</a>
                </div>
            </div>
            <div class="card-body">
                @if($stats['recent_sales']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_sales'] as $sale)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-receipt text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $sale->customer->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $sale->sale_no }} • {{ $sale->sale_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">PKR {{ number_format($sale->net_bill) }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Completed
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-gray-400 text-3xl mb-4"></i>
                        <p class="text-gray-500">No recent sales</p>
                        <a href="{{ route('sales.create') }}" class="btn-primary mt-4">Create First Sale</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Current Gold Rates -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-coins mr-2 text-yellow-600"></i>
                    Current Gold Rates (Per Tola)
                </h2>
                <p class="text-sm text-gray-500">Last updated: {{ now()->format('M d, Y h:i A') }}</p>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4" id="gold-rates">
                    <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                        <h3 class="text-sm font-medium text-yellow-800">24K Gold</h3>
                        <p class="text-xl font-bold text-yellow-900" id="rate-24k">PKR 180,000</p>
                        <span class="text-xs text-green-600">+2.1%</span>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                        <h3 class="text-sm font-medium text-yellow-800">22K Gold</h3>
                        <p class="text-xl font-bold text-yellow-900" id="rate-22k">PKR 165,000</p>
                        <span class="text-xs text-green-600">+1.8%</span>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                        <h3 class="text-sm font-medium text-yellow-800">21K Gold</h3>
                        <p class="text-xl font-bold text-yellow-900" id="rate-21k">PKR 157,500</p>
                        <span class="text-xs text-green-600">+1.5%</span>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                        <h3 class="text-sm font-medium text-yellow-800">18K Gold</h3>
                        <p class="text-xl font-bold text-yellow-900" id="rate-18k">PKR 135,000</p>
                        <span class="text-xs text-red-600">-0.5%</span>
                    </div>
                </div>
                
                <!-- Market Summary -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-blue-900">Market Summary</h4>
                            <p class="text-sm text-blue-700">Gold prices are trending upward today</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-arrow-up mr-1"></i>
                                Bullish
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts & Notifications -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-bell mr-2 text-red-600"></i>
                Alerts & Notifications
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($stats['low_stock_items'] > 0)
                <div class="flex items-start p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Low Stock Alert</h3>
                        <p class="text-sm text-red-600 mt-1">{{ $stats['low_stock_items'] }} items are running low in stock</p>
                        <a href="{{ route('items.index') }}" class="text-xs text-red-700 hover:text-red-900 font-medium mt-2 inline-block">View Items →</a>
                    </div>
                </div>
                @endif

                <!--<div class="flex items-start p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-coins text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Today's Gold Purchase</h3>
                        <p class="text-sm text-blue-600 mt-1">PKR {{ number_format($stats['todays_purchases']) }} worth of gold purchased today</p>
                        <a href="{{ route('gold-purchases.index') }}" class="text-xs text-blue-700 hover:text-blue-900 font-medium mt-2 inline-block">View Purchases →</a>
                    </div>
                </div>-->

                <div class="flex items-start p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">System Status</h3>
                        <p class="text-sm text-green-600 mt-1">All systems operational and running smoothly</p>
                        <span class="text-xs text-green-700 font-medium mt-2 inline-block">Last backup: 2 hours ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-refresh gold rates every 5 minutes
setInterval(function() {
    fetch('{{ route("api.gold-rates") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('rate-24k').textContent = 'PKR ' + new Intl.NumberFormat('en-PK').format(data['24k']);
            document.getElementById('rate-22k').textContent = 'PKR ' + new Intl.NumberFormat('en-PK').format(data['22k']);
            document.getElementById('rate-21k').textContent = 'PKR ' + new Intl.NumberFormat('en-PK').format(data['21k']);
            document.getElementById('rate-18k').textContent = 'PKR ' + new Intl.NumberFormat('en-PK').format(data['18k']);
        })
        .catch(error => console.error('Error fetching gold rates:', error));
}, 300000); // 5 minutes

// Add some interactive animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate stats on page load
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
});
</script>
@endpush
@endsection