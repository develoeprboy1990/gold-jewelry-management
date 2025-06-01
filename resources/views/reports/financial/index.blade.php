{{-- resources/views/reports/financial/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Dashboard')
@section('page-description', 'Overview of your business financial performance')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('reports.financial.profit-loss') }}" class="btn-primary">
        <i class="fas fa-chart-line mr-2"></i>
        P&L Statement
    </a>
    <a href="{{ route('reports.financial.cash-flow') }}" class="btn-secondary">
        <i class="fas fa-money-bill-wave mr-2"></i>
        Cash Flow
    </a>
    <a href="{{ route('reports.financial.receivables') }}" class="btn-secondary">
        <i class="fas fa-file-invoice-dollar mr-2"></i>
        Receivables
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Date Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.financial.index') }}" class="flex items-end space-x-4">
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
                <a href="{{ route('reports.financial.export', ['type' => 'summary', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                   class="btn-secondary">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </a>
            </form>
        </div>
    </div>

    <!-- Key Financial Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">PKR {{ number_format($totalRevenue) }}</p>
                    @if($revenueGrowth >= 0)
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ number_format($revenueGrowth, 1) }}% vs last period
                        </p>
                    @else
                        <p class="text-xs text-red-600 flex items-center mt-1">
                            <i class="fas fa-arrow-down mr-1"></i>
                            {{ number_format($revenueGrowth, 1) }}% vs last period
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Gross Profit -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gross Profit</p>
                    <p class="text-2xl font-bold text-gray-900">PKR {{ number_format($grossProfit) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($grossProfitMargin, 1) }}% margin</p>
                </div>
            </div>
        </div>

        <!-- Cash Received -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-hand-holding-usd text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cash Received</p>
                    <p class="text-2xl font-bold text-gray-900">PKR {{ number_format($totalReceived) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format(($totalReceived/$totalRevenue)*100, 1) }}% of revenue</p>
                </div>
            </div>
        </div>

        <!-- Pending Receivables -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Receivables</p>
                    <p class="text-2xl font-bold text-gray-900">PKR {{ number_format($pendingReceivables) }}</p>
                    <p class="text-xs text-red-500 mt-1">Needs attention</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Methods Breakdown -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Payment Methods</h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($paymentBreakdown as $method => $amount)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" 
                                 style="background-color: {{ 
                                     $method === 'cash' ? '#10b981' : 
                                     ($method === 'credit_card' ? '#3b82f6' : 
                                     ($method === 'check' ? '#f59e0b' : 
                                     ($method === 'used_gold' ? '#ef4444' : '#8b5cf6'))) 
                                 }}"></div>
                            <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $method) }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900">PKR {{ number_format($amount) }}</span>
                            <div class="text-xs text-gray-500">{{ number_format(($amount/$totalReceived)*100, 1) }}%</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Outstanding Customers -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Top Outstanding Receivables</h2>
            </div>
            <div class="card-body">
                @if($outstandingReceivables->count() > 0)
                    <div class="space-y-3">
                        @foreach($outstandingReceivables as $customer)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->contact_no }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">PKR {{ number_format($customer->outstanding_amount) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-400 text-3xl mb-4"></i>
                        <p class="text-gray-500">No outstanding receivables!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Daily Financial Chart -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Daily Financial Performance</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cash Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchases</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Cash Flow</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dailyFinancials as $day)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($day['date'])->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                PKR {{ number_format($day['revenue']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                PKR {{ number_format($day['received']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                PKR {{ number_format($day['purchases']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <span class="{{ $day['net_cash_flow'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    PKR {{ number_format($day['net_cash_flow']) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No financial data for the selected period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- resources/views/reports/financial/profit-loss.blade.php --}}
@extends('layouts.app')

@section('title', 'Profit & Loss Statement')
@section('page-title', 'Profit & Loss Statement')
@section('page-description', 'Detailed profit and loss analysis')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('reports.financial.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Financial Dashboard
    </a>
    <a href="{{ route('reports.financial.export', ['type' => 'profit_loss', 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
       class="btn-primary">
        <i class="fas fa-download mr-2"></i>
        Export P&L
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Date Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.financial.profit-loss') }}" class="flex items-end space-x-4">
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

    <!-- P&L Statement -->
    <div class="card">
        <div class="card-header">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">Golden Jewellers</h1>
                <h2 class="text-lg font-semibold text-gray-700">Profit & Loss Statement</h2>
                <p class="text-sm text-gray-500">Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="card-body">
            <div class="space-y-6">
                <!-- Revenue Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">REVENUE</h3>
                    <div class="space-y-2 pl-4">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Sales Revenue</span>
                            <span class="font-medium">PKR {{ number_format($totalSalesRevenue) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 pl-4">Making Charges</span>
                            <span class="text-gray-600">PKR {{ number_format($totalMakingCharges) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 pl-4">Stone Charges</span>
                            <span class="text-gray-600">PKR {{ number_format($totalStoneCharges) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 pl-4">Other Charges</span>
                            <span class="text-gray-600">PKR {{ number_format($totalOtherCharges) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2">
                            <span>Total Revenue</span>
                            <span>PKR {{ number_format($totalSalesRevenue) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Cost of Goods Sold -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">COST OF GOODS SOLD</h3>
                    <div class="space-y-2 pl-4">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Gold Cost</span>
                            <span class="text-gray-600">PKR {{ number_format($goldCost) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Stone Cost</span>
                            <span class="text-gray-600">PKR {{ number_format($stoneCost) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Making Cost</span>
                            <span class="text-gray-600">PKR {{ number_format($makingCost) }}</span>
                        </div>
                        <div class="flex justify-between font-bold border-t border-gray-200 pt-2">
                            <span>Total COGS</span>
                            <span>PKR {{ number_format($totalCOGS) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Gross Profit -->
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex justify-between text-xl font-bold text-green-800">
                        <span>GROSS PROFIT</span>
                        <span>PKR {{ number_format($grossProfit) }}</span>
                    </div>
                    <div class="text-sm text-green-600 text-right">
                        Margin: {{ number_format(($grossProfit/$totalSalesRevenue)*100, 1) }}%
                    </div>
                </div>

                <!-- Operating Expenses -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">OPERATING EXPENSES</h3>
                    <div class="space-y-2 pl-4">
                        @foreach($operatingExpenses as $expense => $amount)
                        <div class="flex justify-between">
                            <span class="text-gray-700 capitalize">{{ str_replace('_', ' ', $expense) }}</span>
                            <span class="text-gray-600">PKR {{ number_format($amount) }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between font-bold border-t border-gray-200 pt-2">
                            <span>Total Operating Expenses</span>
                            <span>PKR {{ number_format($totalOperatingExpenses) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Net Profit -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex justify-between text-xl font-bold text-blue-800">
                        <span>NET PROFIT</span>
                        <span>PKR {{ number_format($netProfit) }}</span>
                    </div>
                    <div class="text-sm text-blue-600 text-right">
                        Margin: {{ number_format($netProfitMargin, 1) }}%
                    </div>
                </div>

                <!-- Previous Period Comparison -->
                @if(isset($previousPeriod))
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">Previous Period Comparison</h4>
                    <div class="text-sm text-gray-600">
                        Previous Period ({{ $previousPeriod['dates'] }}): PKR {{ number_format($previousPeriod['revenue']) }}
                        <br>
                        @php
                            $change = $totalSalesRevenue - $previousPeriod['revenue'];
                            $changePercent = $previousPeriod['revenue'] > 0 ? ($change / $previousPeriod['revenue']) * 100 : 0;
                        @endphp
                        Change: 
                        <span class="{{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            PKR {{ number_format($change) }} ({{ number_format($changePercent, 1) }}%)
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection