{{-- resources/views/reports/inventory/low-stock.blade.php --}}
@extends('layouts.app')

@section('title', 'Low Stock Alert')
@section('page-title', 'Low Stock Alert')
@section('page-description', 'Items requiring immediate attention')

@section('page-actions')
<div class="flex space-x-3">
    <form method="GET" action="{{ route('reports.inventory.low-stock') }}" class="flex items-center space-x-2">
        <label class="text-sm font-medium text-gray-700">Threshold:</label>
        <input type="number" name="threshold" value="{{ $threshold }}" min="0" max="10" 
               class="form-input w-20" onchange="this.form.submit()">
    </form>
    <a href="{{ route('reports.inventory.export', ['type' => 'low_stock', 'format' => 'csv']) }}" class="btn-primary">
        <i class="fas fa-download mr-2"></i>Export Low Stock
    </a>
    <a href="{{ route('reports.inventory.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Alert Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock Items</p>
                    <p class="text-2xl font-bold text-red-600">{{ $summary['low_stock_count'] }}</p>
                    <p class="text-xs text-gray-500">≤ {{ $threshold }} items</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-orange-100">
                    <i class="fas fa-dollar-sign text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock Value</p>
                    <p class="text-2xl font-bold text-orange-600">PKR {{ number_format($summary['low_stock_value']) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Stale Items</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $summary['stale_items_count'] }}</p>
                    <p class="text-xs text-gray-500">6+ months old</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-gray-100">
                    <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $summary['out_of_stock_count'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Category-wise Low Stock Analysis -->
    @if(count($categoryLowStock) > 0)
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-pie mr-2 text-purple-600"></i>
                Category-wise Low Stock Analysis
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categoryLowStock as $categoryName => $data)
                <div class="bg-gradient-to-r from-red-50 to-orange-50 p-4 rounded-lg border border-red-200">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-medium text-gray-900">{{ $categoryName }}</h3>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $data['count'] }} items
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div>Value: <span class="font-medium text-green-600">PKR {{ number_format($data['value']) }}</span></div>
                        <div>Avg Age: <span class="font-medium">{{ round($data['avg_age_days']) }} days</span></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($lowStockItems->count() > 0)
    <!-- Low Stock Items -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                    Low Stock Items ({{ $lowStockItems->count() }})
                </h2>
                <span class="text-sm text-red-600 font-medium">Requires immediate attention</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Current Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-600 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lowStockItems as $item)
                        <tr class="hover:bg-red-25">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->images && count($item->images) > 0)
                                        <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->group_item }}" 
                                             class="w-10 h-10 object-cover rounded-lg mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-ring text-red-600"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->tag_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->group_item }}</div>
                                        @if($item->karat)
                                            <div class="text-xs text-gray-400">{{ $item->karat }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item->quantity == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item->quantity }} {{ $item->quantity == 1 ? 'item' : 'items' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                PKR {{ number_format($item->total_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('items.edit', $item) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit mr-1"></i>Update Stock
                                </a>
                                <a href="{{ route('items.show', $item) }}" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($outOfStockItems->count() > 0)
    <!-- Out of Stock Items -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-times-circle mr-2 text-gray-600"></i>
                Out of Stock Items ({{ $outOfStockItems->count() }})
            </h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($outOfStockItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-ring text-gray-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->tag_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->group_item }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                PKR {{ number_format($item->total_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('items.edit', $item) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-plus mr-1"></i>Restock
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($staleItems->count() > 0)
    <!-- Stale Items -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-clock mr-2 text-yellow-600"></i>
                Stale Items (6+ months old, no recent sales)
            </h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-yellow-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-600 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-600 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-600 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-600 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-600 uppercase tracking-wider">Recommendation</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($staleItems->take(10) as $item)
                        <tr class="hover:bg-yellow-25">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->images && count($item->images) > 0)
                                        <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->group_item }}" 
                                             class="w-10 h-10 object-cover rounded-lg mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-ring text-yellow-600"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->tag_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->group_item }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                PKR {{ number_format($item->total_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->diffForHumans() }}
                                <div class="text-xs text-gray-400">{{ $item->created_at->diffInDays(now()) }} days</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->created_at->diffInMonths(now()) > 12)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Consider Discount
                                    </span>
                                @elseif($item->created_at->diffInMonths(now()) > 9)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Promote Item
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Monitor Closely
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($staleItems->count() > 10)
            <div class="px-6 py-4 bg-gray-50 text-center">
                <p class="text-sm text-gray-600">Showing first 10 items. Total stale items: {{ $staleItems->count() }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($zeroInventoryCategories->count() > 0)
    <!-- Zero Inventory Categories -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-ban mr-2 text-gray-600"></i>
                Categories with Zero Inventory
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($zeroInventoryCategories as $category)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500">{{ ucfirst($category->type) }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            No Stock
                        </span>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('items.create', ['category' => $category->id]) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                            <i class="fas fa-plus mr-1"></i>Add Items
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Action Required Summary -->
    @if($lowStockItems->count() > 0 || $outOfStockItems->count() > 0 || $staleItems->count() > 0)
    <div class="card border-l-4 border-red-500">
        <div class="card-body">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-red-900">Action Required</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @if($lowStockItems->count() > 0)
                                <li>{{ $lowStockItems->count() }} items are running low on stock and need immediate restocking</li>
                            @endif
                            @if($outOfStockItems->count() > 0)
                                <li>{{ $outOfStockItems->count() }} items are completely out of stock</li>
                            @endif
                            @if($staleItems->count() > 0)
                                <li>{{ $staleItems->count() }} items haven't sold in 6+ months and may need promotion or discounting</li>
                            @endif
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('items.create') }}" class="btn-primary text-sm">
                            <i class="fas fa-plus mr-2"></i>Add New Items
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection