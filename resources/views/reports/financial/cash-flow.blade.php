{{-- resources/views/reports/financial/cash-flow.blade.php --}}
@extends('layouts.app')

@section('title', 'Cash Flow Statement')
@section('page-title', 'Cash Flow Statement')
@section('page-description', 'Track cash inflows and outflows for your business')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('reports.financial.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Financial Dashboard
    </a>
    <a href="{{ route('reports.financial.export', ['type' => 'cash_flow', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
       class="btn-primary">
        <i class="fas fa-download mr-2"></i>
        Export Cash Flow
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Date Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.financial.cash-flow') }}" class="flex items-end space-x-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}" class="form-input">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}" class="form-input">
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Cash Flow Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Inflows -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Cash Inflows</p>
                    <p class="text-2xl font-bold text-green-600">PKR {{ number_format($totalCashInflows) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Money received</p>
                </div>
            </div>
        </div>

        <!-- Total Outflows -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-red-100">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Cash Outflows</p>
                    <p class="text-2xl font-bold text-red-600">PKR {{ number_format($totalCashOutflows) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Money paid out</p>
                </div>
            </div>
        </div>

        <!-- Net Cash Flow -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon {{ $netCashFlow >= 0 ? 'bg-blue-100' : 'bg-orange-100' }}">
                    <i class="fas fa-balance-scale {{ $netCashFlow >= 0 ? 'text-blue-600' : 'text-orange-600' }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Net Cash Flow</p>
                    <p class="text-2xl font-bold {{ $netCashFlow >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                        PKR {{ number_format($netCashFlow) }}
                    </p>
                    <p class="text-xs {{ $netCashFlow >= 0 ? 'text-green-500' : 'text-red-500' }} mt-1">
                        {{ $netCashFlow >= 0 ? 'Positive flow' : 'Negative flow' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cash Inflows Breakdown -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-arrow-down text-green-600 mr-2"></i>
                    Cash Inflows
                </h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($inflowCategories as $category => $amount)
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="font-medium text-gray-900">{{ $category }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-bold text-green-600">PKR {{ number_format($amount) }}</span>
                            <div class="text-xs text-gray-500">
                                {{ $totalCashInflows > 0 ? number_format(($amount/$totalCashInflows)*100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Cash Outflows Breakdown -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-arrow-up text-red-600 mr-2"></i>
                    Cash Outflows
                </h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($outflowCategories as $category => $amount)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <span class="font-medium text-gray-900">{{ $category }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-bold text-red-600">PKR {{ number_format($amount) }}</span>
                            <div class="text-xs text-gray-500">
                                {{ $totalCashOutflows > 0 ? number_format(($amount/$totalCashOutflows)*100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Operating Expenses Detail -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Operating Expenses Breakdown</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($operatingCashOutflows as $expense => $amount)
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $expense) }}</h3>
                    <p class="text-xl font-bold text-gray-900 mt-2">PKR {{ number_format($amount) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Monthly expense</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Daily Cash Flow -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Daily Cash Flow Analysis</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inflows</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outflows</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Flow</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dailyCashFlow as $day)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($day['date'])->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                PKR {{ number_format($day['inflows']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                PKR {{ number_format($day['outflows']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <span class="{{ $day['net_flow'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    PKR {{ number_format($day['net_flow']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <span class="{{ $day['running_balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                    PKR {{ number_format($day['running_balance']) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No cash flow data for the selected period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cash Flow Analysis -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Cash Flow Analysis</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Key Insights -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Key Insights</h3>
                    <div class="space-y-3">
                        @if($netCashFlow >= 0)
                        <div class="flex items-start p-3 bg-green-50 border border-green-200 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-green-800">Positive Cash Flow</p>
                                <p class="text-xs text-green-600">Your business is generating more cash than it's spending.</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-start p-3 bg-red-50 border border-red-200 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-red-800">Negative Cash Flow</p>
                                <p class="text-xs text-red-600">Your business is spending more cash than it's receiving.</p>
                            </div>
                        </div>
                        @endif

                        @php
                            $avgDailyInflow = count($dailyCashFlow) > 0 ? collect($dailyCashFlow)->avg('inflows') : 0;
                            $avgDailyOutflow = count($dailyCashFlow) > 0 ? collect($dailyCashFlow)->avg('outflows') : 0;
                        @endphp
                        
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm font-medium text-blue-800">Average Daily Cash Flow</p>
                            <p class="text-xs text-blue-600">
                                Inflow: PKR {{ number_format($avgDailyInflow) }} | 
                                Outflow: PKR {{ number_format($avgDailyOutflow) }}
                            </p>
                        </div>

                        @php
                            $cashFlowRatio = $totalCashOutflows > 0 ? $totalCashInflows / $totalCashOutflows : 0;
                        @endphp
                        
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm font-medium text-yellow-800">Cash Flow Ratio</p>
                            <p class="text-xs text-yellow-600">
                                {{ number_format($cashFlowRatio, 2) }} 
                                ({{ $cashFlowRatio >= 1 ? 'Healthy' : 'Needs Attention' }})
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recommendations -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Recommendations</h3>
                    <div class="space-y-3">
                        @if($netCashFlow < 0)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm font-medium text-red-800">Improve Cash Collection</p>
                            <p class="text-xs text-red-600">Follow up on pending receivables to improve cash inflow.</p>
                        </div>
                        @endif

                        @if($cashFlowRatio < 1.2)
                        <div class="p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <p class="text-sm font-medium text-orange-800">Monitor Expenses</p>
                            <p class="text-xs text-orange-600">Consider reviewing and optimizing operating expenses.</p>
                        </div>
                        @endif

                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm font-medium text-blue-800">Maintain Cash Reserve</p>
                            <p class="text-xs text-blue-600">Keep 2-3 months of operating expenses as cash reserve.</p>
                        </div>

                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm font-medium text-green-800">Investment Opportunities</p>
                            <p class="text-xs text-green-600">Consider investing excess cash in inventory or business growth.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Add chart visualization if needed
document.addEventListener('DOMContentLoaded', function() {
    // Optional: Add Chart.js visualization for cash flow trends
    console.log('Cash Flow Report loaded');
});
</script>
@endpush
@endsection