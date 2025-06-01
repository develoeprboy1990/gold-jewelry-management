
{{-- resources/views/reports/financial/receivables.blade.php --}}
@extends('layouts.app')

@section('title', 'Accounts Receivable')
@section('page-title', 'Accounts Receivable Report')
@section('page-description', 'Track outstanding payments and customer dues')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('reports.financial.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Financial Dashboard
    </a>
    <a href="{{ route('reports.financial.export', ['type' => 'receivables', 'as_of_date' => $asOfDate]) }}" 
       class="btn-primary">
        <i class="fas fa-download mr-2"></i>
        Export Receivables
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Date Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.financial.receivables') }}" class="flex items-end space-x-4">
                <div>
                    <label for="as_of_date" class="block text-sm font-medium text-gray-700">As Of Date</label>
                    <input type="date" name="as_of_date" id="as_of_date" value="{{ $asOfDate }}" class="form-input">
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Outstanding -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-red-100">
                    <i class="fas fa-file-invoice-dollar text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Outstanding</p>
                    <p class="text-2xl font-bold text-red-600">PKR {{ number_format($summary['total_outstanding']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $summary['number_of_invoices'] }} invoices</p>
                </div>
            </div>
        </div>

        <!-- Number of Customers -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Customers with Dues</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $summary['number_of_customers'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Active receivables</p>
                </div>
            </div>
        </div>

        <!-- Average Invoice Amount -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-calculator text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Average Invoice</p>
                    <p class="text-2xl font-bold text-yellow-600">PKR {{ number_format($summary['average_invoice_amount']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Per invoice</p>
                </div>
            </div>
        </div>

        <!-- Oldest Invoice -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-purple-100">
                    <i class="fas fa-clock text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Oldest Invoice</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $summary['oldest_invoice_days'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Days old</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Aging Analysis -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Aging Analysis</h2>
            <p class="text-sm text-gray-500">Outstanding receivables by age groups</p>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($agingBuckets as $bucket => $amount)
                @php
                    $percentage = $summary['total_outstanding'] > 0 ? ($amount / $summary['total_outstanding']) * 100 : 0;
                    $bucketColor = match($bucket) {
                        '0-30 days' => 'green',
                        '31-60 days' => 'yellow',
                        '61-90 days' => 'orange',
                        '90+ days' => 'red',
                        default => 'gray'
                    };
                @endphp
                <div class="text-center p-4 bg-{{ $bucketColor }}-50 border border-{{ $bucketColor }}-200 rounded-lg">
                    <h3 class="text-sm font-medium text-{{ $bucketColor }}-800">{{ $bucket }}</h3>
                    <p class="text-xl font-bold text-{{ $bucketColor }}-900 mt-2">PKR {{ number_format($amount) }}</p>
                    <p class="text-xs text-{{ $bucketColor }}-600 mt-1">{{ number_format($percentage, 1) }}% of total</p>
                    
                    <!-- Progress bar -->
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-{{ $bucketColor }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Customer-wise Receivables -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Top Customer Receivables</h2>
            </div>
            <div class="card-body">
                @if($customerReceivables->count() > 0)
                    <div class="space-y-3">
                        @foreach($customerReceivables->take(10) as $customer)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $customer->contact_no }}</p>
                                    <p class="text-xs text-gray-400">{{ $customer->outstanding_invoices }} invoice(s)</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">PKR {{ number_format($customer->total_outstanding) }}</p>
                                <a href="{{ route('customers.show', $customer) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                    View Details →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-400 text-3xl mb-4"></i>
                        <p class="text-gray-500">No outstanding receivables!</p>
                        <p class="text-sm text-gray-400">All customers have paid their dues.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Collection Trend -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Payment Collection Trend</h2>
                <p class="text-sm text-gray-500">Last 6 months collection performance</p>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach($collectionTrend as $month)
                    @php
                        $maxCollection = collect($collectionTrend)->max('collections');
                        $percentage = $maxCollection > 0 ? ($month['collections'] / $maxCollection) * 100 : 0;
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <span class="font-medium text-gray-900">{{ $month['month'] }}</span>
                        <div class="flex items-center space-x-3">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="font-bold text-blue-600 text-sm">PKR {{ number_format($month['collections']) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Outstanding Invoices Detail -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900">Outstanding Invoices Detail</h2>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outstanding</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($outstandingReceivables as $sale)
                        @php
                            $daysPastDue = $sale->sale_date->diffInDays(\Carbon\Carbon::parse($asOfDate));
                            $urgencyClass = match(true) {
                                $daysPastDue <= 30 => 'text-green-600',
                                $daysPastDue <= 60 => 'text-yellow-600',
                                $daysPastDue <= 90 => 'text-orange-600',