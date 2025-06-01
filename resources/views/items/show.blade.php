{{-- resources/views/items/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Item Details')
@section('page-title', $item->group_item . ' - ' . $item->tag_number)
@section('page-description', 'Detailed item information and specifications')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('items.edit', $item) }}" class="btn-primary">
        <i class="fas fa-edit mr-2"></i>Edit Item
    </a>
    @if($item->status == 'in_stock')
    <a href="{{ route('sales.create', ['item' => $item->id]) }}" class="btn-primary">
        <i class="fas fa-shopping-cart mr-2"></i>Sell Item
    </a>
    @endif
    <button onclick="window.print()" class="btn-secondary">
        <i class="fas fa-print mr-2"></i>Print Details
    </button>
    <a href="{{ route('items.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Item Status and Price Header -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-tag text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tag Number</p>
                    <p class="text-lg font-bold text-gray-900 font-mono">{{ $item->tag_number }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-weight text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Weight</p>
                    <p class="text-lg font-bold text-yellow-600">{{ $item->weight }}g</p>
                    @if($item->karat)
                        <p class="text-sm text-gray-500">{{ $item->karat }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Price</p>
                    <p class="text-lg font-bold text-green-600">PKR {{ number_format($item->total_price) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon {{ $item->status == 'in_stock' ? 'bg-green-100' : ($item->status == 'sold' ? 'bg-red-100' : 'bg-yellow-100') }}">
                    <i class="fas {{ $item->status == 'in_stock' ? 'fa-check-circle text-green-600' : ($item->status == 'sold' ? 'fa-times-circle text-red-600' : 'fa-clock text-yellow-600') }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Status</p>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium
                        {{ $item->status == 'in_stock' ? 'bg-green-100 text-green-800' : ($item->status == 'sold' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Item Images and Basic Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Images Gallery -->
        <div class="card">
            <div class="card-body p-0">
                @if($item->images && count($item->images) > 0)
                    <div class="relative" x-data="{ currentImage: 0, images: {{ json_encode($item->images) }} }">
                        <!-- Main Image Display -->
                        <div class="aspect-w-1 aspect-h-1">
                            <img :src="`{{ Storage::url('') }}${images[currentImage]}`" 
                                 alt="{{ $item->group_item }}" 
                                 class="w-full h-96 object-cover rounded-t-lg">
                        </div>
                        
                        <!-- Image Navigation Controls -->
                        @if(count($item->images) > 1)
                        <div class="absolute inset-y-0 left-0 flex items-center">
                            <button @click="currentImage = currentImage > 0 ? currentImage - 1 : images.length - 1" 
                                    class="ml-3 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-opacity">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        </div>
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <button @click="currentImage = currentImage < images.length - 1 ? currentImage + 1 : 0" 
                                    class="mr-3 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-opacity">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        
                        <!-- Image Counter -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm">
                                <span x-text="currentImage + 1"></span> / <span x-text="images.length"></span>
                            </span>
                        </div>
                        @endif
                        
                        <!-- Thumbnail Gallery -->
                        @if(count($item->images) > 1)
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                            <div class="flex space-x-2 bg-black bg-opacity-50 p-2 rounded-lg">
                                @foreach($item->images as $index => $image)
                                <button @click="currentImage = {{ $index }}" 
                                        :class="currentImage === {{ $index }} ? 'ring-2 ring-white opacity-100' : 'opacity-70 hover:opacity-100'"
                                        class="w-12 h-12 rounded-lg overflow-hidden transition-opacity">
                                    <img src="{{ Storage::url($image) }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-t-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-ring text-yellow-600 text-6xl mb-4"></i>
                            <p class="text-yellow-700 font-medium">No images available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Item Information -->
        <div class="space-y-6">
            <!-- Basic Details -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Item Details
                    </h2>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Category</label>
                            <p class="text-gray-900 font-medium">{{ $item->category->name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($item->category->type) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Group Item</label>
                            <p class="text-gray-900 font-medium">{{ $item->group_item }}</p>
                        </div>
                        @if($item->sub_group_item)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sub Group</label>
                            <p class="text-gray-900">{{ $item->sub_group_item }}</p>
                        </div>
                        @endif
                        @if($item->sub_item)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sub Item</label>
                            <p class="text-gray-900">{{ $item->sub_item }}</p>
                        </div>
                        @endif
                        @if($item->bar_code)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Bar Code</label>
                            <p class="font-mono text-gray-900">{{ $item->bar_code }}</p>
                        </div>
                        @endif
                        @if($item->design_no)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Design #</label>
                            <p class="text-gray-900">{{ $item->design_no }}</p>
                        </div>
                        @endif
                    </div>

                    @if($item->description)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Description</label>
                        <p class="text-gray-900 leading-relaxed">{{ $item->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pricing Breakdown -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calculator mr-2 text-green-600"></i>
                        Price Breakdown
                    </h2>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Making Cost:</span>
                            <span class="font-medium">PKR {{ number_format($item->making_cost) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Stone Price:</span>
                            <span class="font-medium">PKR {{ number_format($item->stone_price) }}</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total Price:</span>
                                <span class="text-xl font-bold text-green-600">PKR {{ number_format($item->total_price) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Physical Properties and Manufacturing -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Physical Properties -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-weight mr-2 text-purple-600"></i>
                    Physical Properties
                </h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-blue-600 mb-2">
                            <i class="fas fa-balance-scale text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-blue-800 mb-1">Weight</h4>
                        <p class="text-xl font-bold text-blue-900">{{ $item->weight }}g</p>
                    </div>

                    <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="text-green-600 mb-2">
                            <i class="fas fa-cubes text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-green-800 mb-1">Quantity</h4>
                        <p class="text-xl font-bold text-green-900">{{ $item->quantity }}</p>
                    </div>

                    <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="text-yellow-600 mb-2">
                            <i class="fas fa-puzzle-piece text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-yellow-800 mb-1">Pieces</h4>
                        <p class="text-xl font-bold text-yellow-900">{{ $item->pieces }}</p>
                    </div>

                    @if($item->karat)
                    <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                        <div class="text-orange-600 mb-2">
                            <i class="fas fa-gem text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-orange-800 mb-1">Karat</h4>
                        <p class="text-xl font-bold text-orange-900">{{ $item->karat }}</p>
                    </div>
                    @endif
                </div>

                @if($item->pure_weight)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Pure Weight:</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $item->pure_weight }}g</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Manufacturing Details -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-tools mr-2 text-indigo-600"></i>
                    Manufacturing Details
                </h2>
            </div>
            <div class="card-body">
                @if($item->worker_name || $item->worker_id || $item->date_created)
                <div class="space-y-4">
                    @if($item->worker_name)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Worker Name</label>
                        <p class="text-gray-900 font-medium">{{ $item->worker_name }}</p>
                    </div>
                    @endif
                    
                    @if($item->worker_id)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Worker ID</label>
                        <p class="font-mono text-gray-900">{{ $item->worker_id }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date Created</label>
                        <p class="text-gray-900">{{ $item->date_created->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $item->date_created->diffForHumans() }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Last Updated</label>
                        <p class="text-gray-900">{{ $item->updated_at->format('M d, Y h:i A') }}</p>
                        <p class="text-sm text-gray-500">{{ $item->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-tools text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Manufacturing Details</h3>
                    <p class="text-gray-500">Manufacturing information not provided for this item.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stones Information -->
    @if($item->stones->count() > 0)
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-gem mr-2 text-pink-600"></i>
                Stones Information
            </h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stone Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight & Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate & Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Properties</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($item->stones as $stone)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $stone->type }}</div>
                                    <div class="text-sm text-gray-500">{{ $stone->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $stone->weight }} carat</div>
                                    <div class="text-sm text-gray-500">Qty: {{ $stone->quantity }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm text-gray-900">PKR {{ number_format($stone->rate) }}/carat</div>
                                    <div class="text-sm font-medium text-green-600">PKR {{ number_format($stone->price) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($stone->color)
                                        <div><span class="font-medium">Color:</span> {{ $stone->color }}</div>
                                    @endif
                                    @if($stone->cut)
                                        <div><span class="font-medium">Cut:</span> {{ $stone->cut }}</div>
                                    @endif
                                    @if($stone->clarity)
                                        <div><span class="font-medium">Clarity:</span> {{ $stone->clarity }}</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm font-medium text-gray-900 text-right">Total Stone Value:</td>
                            <td class="px-6 py-3 text-sm font-bold text-green-600">PKR {{ number_format($item->stones->sum('price')) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Sales History (if sold) -->
    @if($item->status == 'sold' && $item->saleItems->count() > 0)
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-history mr-2 text-green-600"></i>
                Sales History
            </h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($item->saleItems as $saleItem)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $saleItem->sale->sale_no }}</div>
                                    <div class="text-sm text-gray-500">{{ $saleItem->sale->sale_date->format('M d, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $saleItem->sale->customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $saleItem->sale->customer->contact_no }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">PKR {{ number_format($saleItem->total_price) }}</div>
                                    @if($saleItem->discount > 0)
                                        <div class="text-sm text-red-600">Discount: PKR {{ number_format($saleItem->discount) }}</div>
                                    @endif
                                    <div class="text-sm font-semibold text-green-600">Net: PKR {{ number_format($saleItem->net_price) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('sales.show', $saleItem->sale) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>View Sale
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

    <!-- Quick Actions (if in stock) -->
    @if($item->status == 'in_stock')
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-bolt mr-2 text-orange-600"></i>
                Quick Actions
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('sales.create', ['item' => $item->id]) }}" 
                   class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 text-center">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Sell This Item</h3>
                            <p class="text-sm opacity-90">Create a new sale</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('items.edit', $item) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 text-center">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-edit text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Edit Details</h3>
                            <p class="text-sm opacity-90">Update information</p>
                        </div>
                    </div>
                </a>
                
                <button onclick="window.print()" 
                        class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all transform hover:scale-105">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-print text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold">Print Details</h3>
                            <p class="text-sm opacity-90">Generate printout</p>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Print Styles -->
<style media="print">
    .no-print { display: none !important; }
    body { font-size: 12px; }
    .stat-card { break-inside: avoid; }
    .card { break-inside: avoid; margin-bottom: 1rem; }
    .grid { display: block; }
    .grid > * { margin-bottom: 1rem; }
</style>
@endsection