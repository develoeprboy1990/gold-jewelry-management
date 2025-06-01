{{-- resources/views/gold-purchases/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Gold Purchase Details')
@section('page-title', 'Purchase: ' . $goldPurchase->voucher_no)
@section('page-description', 'Gold purchase transaction details')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('gold-purchases.edit', $goldPurchase) }}" class="btn-primary">
        <i class="fas fa-edit mr-2"></i>Edit Purchase
    </a>
    <button onclick="window.print()" class="btn-secondary">
        <i class="fas fa-print mr-2"></i>Print Receipt
    </button>
    <a href="{{ route('gold-purchases.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Purchases
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Purchase Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-receipt text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Voucher No.</p>
                    <p class="text-lg font-bold text-gray-900">{{ $goldPurchase->voucher_no }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-weight text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Weight</p>
                    <p class="text-lg font-bold text-blue-600">{{ $goldPurchase->weight }}g</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Amount</p>
                    <p class="text-lg font-bold text-green-600">PKR {{ number_format($goldPurchase->amount) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon {{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'bg-green-100' : 'bg-red-100' }}">
                    <i class="fas fa-balance-scale {{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'text-green-600' : 'text-red-600' }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Balance</p>
                    <p class="text-lg font-bold {{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'text-green-600' : 'text-red-600' }}">
                        PKR {{ number_format(abs($goldPurchase->amount - $goldPurchase->cash_payment)) }}
                        @if($goldPurchase->amount > $goldPurchase->cash_payment) (Pending) @elseif($goldPurchase->amount < $goldPurchase->cash_payment) (Overpaid) @else (Settled) @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Purchase Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Purchase Information
                </h2>
            </div>
            <div class="card-body space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Purchase Date</label>
                        <p class="text-gray-900">{{ $goldPurchase->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Gold Type</label>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $goldPurchase->type == 'pure_gold' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $goldPurchase->type)) }}
                        </span>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Voucher Number</label>
                        <p class="font-mono text-gray-900">{{ $goldPurchase->voucher_no }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Rate per Gram</label>
                        <p class="text-gray-900 font-semibold">PKR {{ number_format($goldPurchase->rate) }}</p>
                    </div>
                </div>

                @if($goldPurchase->description)
                <div>
                    <label class="text-sm font-medium text-gray-500">Description</label>
                    <p class="text-gray-900">{{ $goldPurchase->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer/Seller Information -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-green-600"></i>
                        Customer Information
                    </h2>
                    @if($goldPurchase->customer)
                    <a href="{{ route('customers.show', $goldPurchase->customer) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View Profile →
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Name</label>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $goldPurchase->customer ? $goldPurchase->customer->name : $goldPurchase->customer_name }}
                    </p>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Contact Number</label>
                        <p class="text-gray-900">
                            {{ $goldPurchase->customer ? $goldPurchase->customer->contact_no : $goldPurchase->contact_no }}
                        </p>
                    </div>
                    
                    @if($goldPurchase->customer && $goldPurchase->customer->cnic)
                    <div>
                        <label class="text-sm font-medium text-gray-500">CNIC</label>
                        <p class="font-mono text-gray-900">{{ $goldPurchase->customer->cnic }}</p>
                    </div>
                    @endif
                    
                    @if($goldPurchase->customer && $goldPurchase->customer->email)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $goldPurchase->customer->email }}</p>
                    </div>
                    @endif
                    
                    @if($goldPurchase->address || ($goldPurchase->customer && $goldPurchase->customer->address))
                    <div>
                        <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900">
                            {{ $goldPurchase->address ?: $goldPurchase->customer->address }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gold Details -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-coins mr-2 text-yellow-600"></i>
                Gold Details & Calculations
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Physical Properties -->
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="text-yellow-600 mb-2">
                        <i class="fas fa-weight text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-medium text-yellow-800 mb-1">Total Weight</h4>
                    <p class="text-2xl font-bold text-yellow-900">{{ $goldPurchase->weight }}g</p>
                    <p class="text-sm text-yellow-700">{{ $goldPurchase->karat }}</p>
                </div>

                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-blue-600 mb-2">
                        <i class="fas fa-gem text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-medium text-blue-800 mb-1">Pure Weight</h4>
                    <p class="text-2xl font-bold text-blue-900">{{ $goldPurchase->pure_weight }}g</p>
                    <p class="text-sm text-blue-700">Actual Gold Content</p>
                </div>

                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-green-600 mb-2">
                        <i class="fas fa-balance-scale text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-medium text-green-800 mb-1">24K Equivalent</h4>
                    <p class="text-2xl font-bold text-green-900">{{ $goldPurchase->karrat_24 }}g</p>
                    <p class="text-sm text-green-700">Standard Measure</p>
                </div>

                <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="text-purple-600 mb-2">
                        <i class="fas fa-calculator text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-medium text-purple-800 mb-1">Rate per Gram</h4>
                    <p class="text-2xl font-bold text-purple-900">PKR {{ number_format($goldPurchase->rate) }}</p>
                    <p class="text-sm text-purple-700">Market Rate</p>
                </div>
            </div>

            <!-- Calculation Breakdown -->
            <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-3">Calculation Breakdown</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Weight Analysis</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Total Weight:</span>
                                <span class="font-medium">{{ $goldPurchase->weight }}g</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Karat:</span>
                                <span class="font-medium">{{ $goldPurchase->karat }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pure Gold Content:</span>
                                <span class="font-medium">{{ $goldPurchase->pure_weight }}g</span>
                            </div>
                            <div class="flex justify-between">
                                <span>24K Equivalent:</span>
                                <span class="font-medium">{{ $goldPurchase->karrat_24 }}g</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Price Calculation</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Rate per Gram:</span>
                                <span class="font-medium">PKR {{ number_format($goldPurchase->rate) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Weight:</span>
                                <span class="font-medium">{{ $goldPurchase->weight }}g</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-semibold">Total Amount:</span>
                                <span class="font-semibold text-green-600">PKR {{ number_format($goldPurchase->amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-credit-card mr-2 text-green-600"></i>
                Payment Information
            </h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-blue-600 mb-3">
                        <i class="fas fa-dollar-sign text-3xl"></i>
                    </div>
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Total Amount</h4>
                    <p class="text-2xl font-bold text-blue-900">PKR {{ number_format($goldPurchase->amount) }}</p>
                </div>

                <div class="text-center p-6 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-green-600 mb-3">
                        <i class="fas fa-money-bill-wave text-3xl"></i>
                    </div>
                    <h4 class="text-sm font-medium text-green-800 mb-2">Cash Paid</h4>
                    <p class="text-2xl font-bold text-green-900">PKR {{ number_format($goldPurchase->cash_payment) }}</p>
                </div>

                <div class="text-center p-6 {{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }} rounded-lg border">
                    <div class="{{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'text-green-600' : 'text-red-600' }} mb-3">
                        <i class="fas fa-balance-scale text-3xl"></i>
                    </div>
                    <h4 class="text-sm font-medium {{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'text-green-800' : 'text-red-800' }} mb-2">
                        @if($goldPurchase->amount == $goldPurchase->cash_payment)
                            Status: Settled
                        @elseif($goldPurchase->amount > $goldPurchase->cash_payment)
                            Balance Pending
                        @else
                            Overpaid
                        @endif
                    </h4>
                    <p class="text-2xl font-bold {{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'text-green-900' : 'text-red-900' }}">
                        PKR {{ number_format(abs($goldPurchase->amount - $goldPurchase->cash_payment)) }}
                    </p>
                </div>
            </div>

            <!-- Payment Summary Table -->
            <div class="mt-6 overflow-hidden">
                <table class="min-w-full">
                    <tbody class="bg-white">
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Total Purchase Amount</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">PKR {{ number_format($goldPurchase->amount) }}</td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">Cash Payment</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">PKR {{ number_format($goldPurchase->cash_payment) }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 text-sm font-bold text-gray-900">Balance</td>
                            <td class="px-4 py-3 text-sm font-bold text-right {{ $goldPurchase->amount == $goldPurchase->cash_payment ? 'text-green-600' : 'text-red-600' }}">
                                PKR {{ number_format(abs($goldPurchase->amount - $goldPurchase->cash_payment)) }}
                                @if($goldPurchase->amount > $goldPurchase->cash_payment) (Pending) 
                                @elseif($goldPurchase->amount < $goldPurchase->cash_payment) (Overpaid) 
                                @else (Settled) @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transaction History (if customer exists) -->
    @if($goldPurchase->customer && $goldPurchase->customer->goldPurchases->count() > 1)
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history mr-2 text-purple-600"></i>
                    Customer's Purchase History
                </h2>
                <a href="{{ route('customers.show', $goldPurchase->customer) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    View All Transactions →
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight & Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($goldPurchase->customer->goldPurchases->sortByDesc('date')->take(5) as $purchase)
                        <tr class="{{ $purchase->id == $goldPurchase->id ? 'bg-yellow-50' : 'hover:bg-gray-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $purchase->date->format('M d, Y') }}
                                @if($purchase->id == $goldPurchase->id)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                        Current
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                {{ $purchase->voucher_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $purchase->weight }}g ({{ $purchase->karat }})
                                <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $purchase->type)) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                PKR {{ number_format($purchase->amount) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
</style>
@endsection