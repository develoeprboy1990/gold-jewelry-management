{{-- resources/views/reports/inventory/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventory Reports')
@section('page-title', 'Inventory Reports')
@section('page-description', 'Comprehensive inventory analysis and stock management')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('reports.inventory.export', request()->query()) }}" class="btn-primary">
        <i class="fas fa-download mr-2"></i>Export Inventory
    </a>
    <a href="{{ route('reports.inventory.valuation') }}" class="btn-secondary">
        <i class="fas fa-calculator mr-2"></i>Valuation Report
    </a>
    <a href="{{ route('reports.inventory.low-stock') }}" class="btn-secondary">
        <i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Alert
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Inventory Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalItems) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Stock</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($inStockItems) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-red-100">
                    <i class="fas fa-shopping-cart text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sold</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($soldItems) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">On Order</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($onOrderItems) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-purple-100">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inventory Value</p>
                    <p class="text-xl font-bold text-purple-600">PKR {{ number_format($totalInventoryValue) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-3xl font-bold text-yellow-600">{{ number_format($averageItemValue) }}</div>
                <div class="text-sm text-gray-600">Average Item Value (PKR)</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-3xl font-bold text-blue-600">{{ number_format($totalWeight, 2) }}g</div>
                <div class="text-sm text-gray-600">Total Weight</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-3xl font-bold text-green-600">{{ number_format($averageWeight, 2) }}g</div>
                <div class="text-sm text-gray-600">Average Weight</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-3xl font-bold {{ $lowStockItems > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $lowStockItems }}</div>
                <div class="text-sm text-gray-600">Low Stock Items</div>
                @if($lowStockItems > 0)
                    <a href="{{ route('reports.inventory.low-stock') }}" class="text-xs text-red-600 hover:text-red-800">View Details →</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Filters & Sorting</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.inventory.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="in_stock" {{ $status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="sold" {{ $status == 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="on_order" {{ $status == 'on_order' ? 'selected' : '' }}>On Order</option>
                    </select>
                </div>
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700">Sort By</label>
                    <select name="sort_by" id="sort_by" class="form-select">
                        <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="total_price" {{ $sortBy == 'total_price' ? 'selected' : '' }}>Price</option>
                        <option value="weight" {{ $sortBy == 'weight' ? 'selected' : '' }}>Weight</option>
                        <option value="tag_number" {{ $sortBy == 'tag_number' ? 'selected' : '' }}>Tag Number</option>
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Order</label>
                    <select name="sort_order" id="sort_order" class="form-select">
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-filter mr-2"></i>Apply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Breakdown -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Category Breakdown</h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($categoryBreakdown as $category)
                    @if($category->items_count > 0)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900">{{ $category->name }}</div>
                            <div class="text-sm text-gray-500">{{ $category->items_count }} items</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-green-600">PKR {{ number_format($category->items_sum_total_price) }}</div>
                            <div class="text-sm text-gray-500">
                                Avg: PKR {{ number_format($category->items_sum_total_price / $category->items_count) }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Additions -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Recent Additions</h2>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach($recentItems as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
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
                                <div class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-green-600">PKR {{ number_format($item->total_price) }}</div>
                            <div class="text-xs text-gray-500">{{ $item->weight }}g</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory List -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Inventory Items</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('reports.inventory.export', array_merge(request()->query(), ['type' => 'current', 'format' => 'csv'])) }}" 
                       class="btn-secondary text-sm">
                        <i class="fas fa-file-csv mr-1"></i>Export CSV
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physical Properties</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pricing</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->images && count($item->images) > 0)
                                        <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->group_item }}" 
                                             class="w-12 h-12 object-cover rounded-lg mr-4">
                                    @else
                                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-ring text-yellow-600"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->tag_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->group_item }}</div>
                                        @if($item->sub_group_item)
                                            <div class="text-xs text-gray-400">{{ $item->sub_group_item }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->category->name }}</div>
                                <div class="text-sm text-gray-500">{{ ucfirst($item->category->type) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div>Weight: {{ $item->weight }}g</div>
                                    @if($item->karat)
                                        <div>Karat: {{ $item->karat }}</div>
                                    @endif
                                    <div>Qty: {{ $item->quantity }} ({{ $item->pieces }} pcs)</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="font-medium text-green-600">PKR {{ number_format($item->total_price) }}</div>
                                    <div class="text-xs">Making: PKR {{ number_format($item->making_cost) }}</div>
                                    @if($item->stone_price > 0)
                                        <div class="text-xs">Stone: PKR {{ number_format($item->stone_price) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $item->status == 'in_stock' ? 'bg-green-100 text-green-800' : 
                                       ($item->status == 'sold' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                                @if($item->quantity <= 1 && $item->status == 'in_stock')
                                    <div class="text-xs text-red-600 mt-1">Low Stock</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No items found matching the selected criteria
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
    <div class="flex justify-center">
        {{ $items->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection