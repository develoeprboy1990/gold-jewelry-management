{{-- resources/views/reports/inventory/valuation.blade.php --}}
@extends('layouts.app')

@section('title', 'Inventory Valuation Report')
@section('page-title', 'Inventory Valuation Report')
@section('page-description', 'Complete inventory valuation and asset analysis')

@section('page-actions')
<div class="flex space-x-3">
    <form method="GET" action="{{ route('reports.inventory.valuation') }}" class="flex items-center space-x-2">
        <label class="text-sm font-medium text-gray-700">As of Date:</label>
        <input type="date" name="as_of_date" value="{{ $asOfDate }}" class="form-input" onchange="this.form.submit()">
    </form>
    <a href="{{ route('reports.inventory.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Total Valuation Summary -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Total Inventory Valuation</h2>
            <p class="text-sm text-gray-600">As of {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}</p>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($totalValuation['total_items']) }}</div>
                    <div class="text-sm text-blue-800">Total Items</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-600">PKR {{ number_format($totalValuation['total_value']) }}</div>
                    <div class="text-sm text-green-800">Total Value</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($totalValuation['total_weight'], 2) }}g</div>
                    <div class="text-sm text-yellow-800">Total Weight</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="text-2xl font-bold text-purple-600">PKR {{ number_format($totalValuation['total_making_cost']) }}</div>
                    <div class="text-sm text-purple-800">Making Cost</div>
                </div>
                <div class="text-center p-4 bg-pink-50 rounded-lg border border-pink-200">
                    <div class="text-2xl font-bold text-pink-600">PKR {{ number_format($totalValuation['total_stone_value']) }}</div>
                    <div class="text-sm text-pink-800">Stone Value</div>
                </div>
                <div class="text-center p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <div class="text-2xl font-bold text-indigo-600">{{ number_format($totalValuation['total_pure_weight'], 2) }}g</div>
                    <div class="text-sm text-indigo-800">Pure Gold</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Valuation -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Valuation by Category</h2>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categoryValuation as $categoryName => $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $categoryName }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $data['count'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    PKR {{ number_format($data['total_value']) }}
                                    <div class="text-xs text-gray-500">Avg: PKR {{ number_format($data['avg_value']) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($data['total_weight'], 2) }}g
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Karat Valuation -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Valuation by Karat</h2>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($karatValuation as $karat => $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-yellow-600">
                                    {{ $karat ?: 'Not Specified' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $data['count'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    PKR {{ number_format($data['total_value']) }}
                                    <div class="text-xs text-gray-500">Avg: PKR {{ number_format($data['avg_value']) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($data['total_weight'], 2) }}g
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Valuable Items -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Top 10 Most Valuable Items</h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topValueItems as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-full {{ $index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }} flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                            </td>
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
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->weight }}g
                                @if($item->karat)
                                    <div class="text-xs text-yellow-600">({{ $item->karat }})</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                PKR {{ number_format($item->total_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Age Analysis -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Inventory Age Analysis</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($ageAnalysis as $ageGroup => $data)
                <div class="text-center p-4 bg-gray-50 rounded-lg border">
                    <div class="text-lg font-bold text-gray-900">{{ $data['count'] }}</div>
                    <div class="text-sm text-gray-600">{{ $ageGroup }}</div>
                    <div class="text-xs text-green-600 mt-1">PKR {{ number_format($data['value']) }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection