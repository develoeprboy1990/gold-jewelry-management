@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Customers -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Customers</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items in Stock -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-ring text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Items in Stock</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_items']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Today's Sales</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['todays_sales']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Monthly Revenue</h3>
                        <p class="text-2xl font-bold text-gray-900">PKR {{ number_format($stats['monthly_revenue']) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="{{ route('customers.create') }}" class="btn-primary text-center block">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add Customer
                </a>
                <a href="{{ route('orders.create') }}" class="btn-primary text-center block">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    New Order
                </a>
                <a href="{{ route('items.create') }}" class="btn-primary text-center block">
                    <i class="fas fa-plus mr-2"></i>
                    Add Item
                </a>
                <a href="{{ route('sales.create') }}" class="btn-primary text-center block">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    New Sale
                </a>
                <a href="{{ route('gold-purchases.create') }}" class="btn-primary text-center block">
                    <i class="fas fa-coins mr-2"></i>
                    Gold Purchase
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Sales -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Sales</h2>
                    <a href="{{ route('sales.index') }}" class="text-yellow-600 hover:text-yellow-700 text-sm">View All</a>
                </div>
            </div>
            <div class="card-body">
                @if($stats['recent_sales']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_sales'] as $sale)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $sale->customer->name }}</p>
                                <p class="text-sm text-gray-500">Sale #{{ $sale->sale_no }}</p>
                                <p class="text-xs text-gray-400">{{ $sale->sale_date->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">PKR {{ number_format($sale->net_bill) }}</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
                    </div>
                @endif
            </div>
        </div>

        <!-- Alerts & Notifications -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Alerts & Notifications</h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @if($stats['low_stock_items'] > 0)
                    <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Low Stock Alert</p>
                            <p class="text-sm text-red-600">{{ $stats['low_stock_items'] }} items are running low in stock</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">Today's Gold Purchase</p>
                            <p class="text-sm text-blue-600">PKR {{ number_format($stats['todays_purchases']) }} worth of gold purchased today</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">System Status</p>
                            <p class="text-sm text-green-600">All systems operational</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Gold Rates -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Current Gold Rates (Per Tola)</h2>
            <p class="text-sm text-gray-500">Last updated: {{ now()->format('M d, Y h:i A') }}</p>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="gold-rates">
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h3 class="text-sm font-medium text-yellow-800">24K Gold</h3>
                    <p class="text-2xl font-bold text-yellow-900" id="rate-24k">PKR 180,000</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h3 class="text-sm font-medium text-yellow-800">22K Gold</h3>
                    <p class="text-2xl font-bold text-yellow-900" id="rate-22k">PKR 165,000</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h3 class="text-sm font-medium text-yellow-800">21K Gold</h3>
                    <p class="text-2xl font-bold text-yellow-900" id="rate-21k">PKR 157,500</p>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h3 class="text-sm font-medium text-yellow-800">18K Gold</h3>
                    <p class="text-2xl font-bold text-yellow-900" id="rate-18k">PKR 135,000</p>
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
</script>
@endpush
@endsection