{{-- resources/views/reports/sales/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Sales Reports')
@section('page-title', 'Sales Reports')
@section('page-description', 'Comprehensive sales analytics and reporting')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('reports.sales.export', request()->query()) }}" class="btn-primary">
        <i class="fas fa-download mr-2"></i>Export Data
    </a>
    <a href="{{ route('reports.sales.daily') }}" class="btn-secondary">
        <i class="fas fa-calendar-day mr-2"></i>Daily Report
    </a>
    <a href="{{ route('reports.sales.monthly') }}" class="btn-secondary">
        <i class="fas fa-calendar-alt mr-2"></i>Monthly Report
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalSales) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-600">PKR {{ number_format($totalRevenue) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-credit-card text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Amount Received</p>
                    <p class="text-2xl font-bold text-yellow-600">PKR {{ number_format($totalReceived) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-red-100">
                    <i class="fas fa-clock text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Amount</p>
                    <p class="text-2xl font-bold text-red-600">PKR {{ number_format($totalPending) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Filters & Search</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.sales.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}" class="form-input">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}" class="form-input">
                </div>
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $customerId == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Sales Chart -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Daily Sales Trend</h2>
            </div>
            <div class="card-body">
                <div class="h-64" id="daily-sales-chart"></div>
            </div>
        </div>

        <!-- Payment Methods Breakdown -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Payment Methods</h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach($paymentMethods as $method => $amount)
                    @if($amount > 0)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ 
                                $method == 'Cash' ? '#10b981' : 
                                ($method == 'Credit Card' ? '#3b82f6' : 
                                ($method == 'Check' ? '#f59e0b' : 
                                ($method == 'Used Gold' ? '#8b5cf6' : '#ec4899'))) }}"></div>
                            <span class="text-sm font-medium text-gray-700">{{ $method }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">PKR {{ number_format($amount) }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Top Customers</h2>
                <a href="{{ route('reports.sales.customer') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    View Customer Report →
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Count</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg per Sale</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topCustomers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $customer->customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $customer->customer->contact_no }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $customer->sales_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                PKR {{ number_format($customer->total_spent) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                PKR {{ number_format($customer->total_spent / $customer->sales_count) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sales List -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Sales Transactions</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('reports.sales.export', array_merge(request()->query(), ['format' => 'csv'])) }}" 
                       class="btn-secondary text-sm">
                        <i class="fas fa-file-csv mr-1"></i>CSV
                    </a>
                    <a href="{{ route('reports.sales.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" 
                       class="btn-secondary text-sm">
                        <i class="fas fa-file-pdf mr-1"></i>PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $sale->sale_no }}</div>
                                    <div class="text-sm text-gray-500">{{ $sale->sale_date->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">By: {{ $sale->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $sale->customer->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $sale->customer->contact_no }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">PKR {{ number_format($sale->net_bill) }}</div>
                                    @if($sale->bill_discount > 0)
                                        <div class="text-sm text-red-600">Discount: PKR {{ number_format($sale->bill_discount) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">PKR {{ number_format($sale->total_received) }}</div>
                                @if($sale->cash_balance != 0)
                                    <div class="text-sm {{ $sale->cash_balance > 0 ? 'text-red-600' : 'text-blue-600' }}">
                                        Balance: PKR {{ number_format(abs($sale->cash_balance)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($sale->cash_balance <= 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No sales found for the selected criteria
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($sales->hasPages())
    <div class="flex justify-center">
        {{ $sales->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Sales Chart
const dailySalesData = @json($dailySales);
const ctx = document.getElementById('daily-sales-chart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: dailySalesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }),
        datasets: [{
            label: 'Revenue (PKR)',
            data: dailySalesData.map(item => item.revenue),
            borderColor: '#d97706',
            backgroundColor: 'rgba(217, 119, 6, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Sales Count',
            data: dailySalesData.map(item => item.count),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Revenue (PKR)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Sales Count'
                },
                grid: {
                    drawOnChartArea: false,
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.datasetIndex === 0) {
                            label += 'PKR ' + new Intl.NumberFormat('en-PK').format(context.parsed.y);
                        } else {
                            label += context.parsed.y + ' sales';
                        }
                        return label;
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection