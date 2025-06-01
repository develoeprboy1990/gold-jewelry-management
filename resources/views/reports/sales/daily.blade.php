{{-- resources/views/reports/sales/daily.blade.php --}}
@extends('layouts.app')

@section('title', 'Daily Sales Report')
@section('page-title', 'Daily Sales Report')
@section('page-description', 'Detailed sales analysis for ' . \Carbon\Carbon::parse($date)->format('M d, Y'))

@section('page-actions')
<div class="flex space-x-3">
    <form method="GET" action="{{ route('reports.sales.daily') }}" class="flex items-center space-x-2">
        <input type="date" name="date" value="{{ $date }}" class="form-input" onchange="this.form.submit()">
    </form>
    <a href="{{ route('reports.sales.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Reports
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Daily Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="stat-card">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $summary['total_sales'] }}</div>
                <div class="text-sm text-gray-600">Total Sales</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">PKR {{ number_format($summary['total_revenue']) }}</div>
                <div class="text-sm text-gray-600">Revenue</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">PKR {{ number_format($summary['total_received']) }}</div>
                <div class="text-sm text-gray-600">Received</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">PKR {{ number_format($summary['total_pending']) }}</div>
                <div class="text-sm text-gray-600">Pending</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">PKR {{ number_format($summary['avg_sale_value']) }}</div>
                <div class="text-sm text-gray-600">Avg Sale</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ $summary['total_items_sold'] }}</div>
                <div class="text-sm text-gray-600">Items Sold</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Hourly Sales -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Hourly Breakdown</h2>
            </div>
            <div class="card-body">
                <div class="h-64" id="hourly-chart"></div>
            </div>
        </div>

        <!-- Sales List -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Sales Transactions</h2>
            </div>
            <div class="card-body p-0 max-h-80 overflow-y-auto">
                <div class="space-y-2 p-4">
                    @forelse($dailySales as $sale)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $sale->sale_no }}</div>
                            <div class="text-sm text-gray-500">{{ $sale->customer->name }}</div>
                            <div class="text-xs text-gray-400">{{ $sale->created_at->format('h:i A') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-green-600">PKR {{ number_format($sale->net_bill) }}</div>
                            <div class="text-xs text-gray-500">{{ $sale->saleItems->count() }} items</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        No sales for this date
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Sales Table -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Detailed Sales List</h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dailySales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->created_at->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $sale->sale_no }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $sale->customer->contact_no }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sale->saleItems->count() }} items
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">PKR {{ number_format($sale->net_bill) }}</div>
                                @if($sale->bill_discount > 0)
                                    <div class="text-sm text-red-600">Discount: PKR {{ number_format($sale->bill_discount) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">PKR {{ number_format($sale->total_received) }}</div>
                                @if($sale->cash_balance > 0)
                                    <div class="text-sm text-red-600">Pending: PKR {{ number_format($sale->cash_balance) }}</div>
                                @elseif($sale->cash_balance < 0)
                                    <div class="text-sm text-blue-600">Advance: PKR {{ number_format(abs($sale->cash_balance)) }}</div>
                                @else
                                    <div class="text-sm text-green-600">Paid</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Hourly Sales Chart
const hourlyData = @json($hourlySales);
const hourlyCtx = document.getElementById('hourly-chart').getContext('2d');

new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: hourlyData.map(item => item.hour),
        datasets: [{
            label: 'Revenue (PKR)',
            data: hourlyData.map(item => item.revenue),
            backgroundColor: 'rgba(217, 119, 6, 0.7)',
            borderColor: '#d97706',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Revenue (PKR)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Hour of Day'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'PKR ' + new Intl.NumberFormat('en-PK').format(context.parsed.y);
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection