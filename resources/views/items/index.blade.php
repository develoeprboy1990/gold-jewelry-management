{{-- resources/views/items/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Items & Stock')
@section('page-title', 'Items & Stock')
@section('page-description', 'Manage jewelry inventory and stock')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('items.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Add Item
    </a>
    <button class="btn-secondary">
        <i class="fas fa-download mr-2"></i>Export Inventory
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Inventory Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-ring text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $items->total() }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $items->where('status', 'in_stock')->count() }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $items->where('status', 'sold')->count() }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $items->where('status', 'on_order')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('items.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by tag, group, or description..." 
                           class="form-input">
                </div>
                <div>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\ItemCategory::all() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="on_order" {{ request('status') == 'on_order' ? 'selected' : '' }}>On Order</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('items.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($items as $item)
        <div class="card hover:shadow-lg transition-shadow duration-200">
            <div class="relative">
                @if($item->images && count($item->images) > 0)
                    <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->group_item }}" 
                         class="w-full h-48 object-cover rounded-t-lg">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-t-lg flex items-center justify-center">
                        <i class="fas fa-ring text-yellow-600 text-4xl"></i>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-2 right-2">
                    @if($item->status == 'in_stock')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            In Stock
                        </span>
                    @elseif($item->status == 'sold')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Sold
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            On Order
                        </span>
                    @endif
                </div>

                <!-- Tag Number Badge -->
                <div class="absolute top-2 left-2">
                    <span class="inline-flex items-center px-2 py-1 rounded bg-black bg-opacity-70 text-white text-xs font-mono">
                        {{ $item->tag_number }}
                    </span>
                </div>
            </div>

            <div class="p-4">
                <div class="mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $item->group_item }}</h3>
                    <p class="text-sm text-gray-600">{{ $item->category->name }}</p>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Weight:</span>
                        <span class="font-medium">{{ $item->weight }}g</span>
                    </div>
                    @if($item->karat)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Karat:</span>
                        <span class="font-medium">{{ $item->karat }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Price:</span>
                        <span class="font-bold text-yellow-600">PKR {{ number_format($item->total_price) }}</span>
                    </div>
                </div>

                @if($item->description)
                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $item->description }}</p>
                @endif

                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('items.show', $item) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <a href="{{ route('items.edit', $item) }}" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <i class="fas fa-ring text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No items found</h3>
                <p class="text-gray-500 mb-4">Start building your inventory by adding your first item.</p>
                <a href="{{ route('items.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add First Item
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
    <div class="flex justify-center">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection