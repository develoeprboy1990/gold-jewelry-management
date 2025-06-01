{{-- resources/views/customers/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Customer Details')
@section('page-title', $customer->name)
@section('page-description', 'Customer information and transaction history')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('customers.edit', $customer) }}" class="btn-primary">
        <i class="fas fa-edit mr-2"></i>Edit Customer
    </a>
    <a href="{{ route('sales.create', ['customer' => $customer->id]) }}" class="btn-primary">
        <i class="fas fa-shopping-cart mr-2"></i>New Sale
    </a>
    <a href="{{ route('customers.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to List
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Customer Information Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Personal Information
                </h2>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Full Name</label>
                    <p class="text-gray-900">{{ $customer->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">CNIC</label>
                    <p class="text-gray-900 font-mono">{{ $customer->cnic }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Contact Number</label>
                    <p class="text-gray-900">{{ $customer->contact_no }}</p>
                </div>
                @if($customer->email)
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-900">{{ $customer->email }}</p>
                </div>
                @endif
                @if($customer->company)
                <div>
                    <label class="text-sm font-medium text-gray-500">Company</label>
                    <p class="text-gray-900">{{ $customer->company }}</p>
                </div>
                @endif
                @if($customer->birth_date)
                <div>
                    <label class="text-sm font-medium text-gray-500">Birth Date</label>
                    <p class="text-gray-900">{{ $customer->birth_date->format('M d, Y') }}</p>
                </div>
                @endif
                @if($customer->anniversary_date)
                <div>
                    <label class="text-sm font-medium text-gray-500">Anniversary Date</label>
                    <p class="text-gray-900">{{ $customer->anniversary_date->format('M d, Y') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Address Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
                    Address Information
                </h2>
            </div>
            <div class="card-body space-y-3">
                @if($customer->house_no || $customer->street_no || $customer->block_no)
                <div>
                    <label class="text-sm font-medium text-gray-500">House/Street/Block</label>
                    <p class="text-gray-900">
                        @if($customer->house_no) House: {{ $customer->house_no }} @endif
                        @if($customer->street_no) Street: {{ $customer->street_no }} @endif
                        @if($customer->block_no) Block: {{ $customer->block_no }} @endif
                    </p>
                </div>
                @endif
                @if($customer->colony)
                <div>
                    <label class="text-sm font-medium text-gray-500">Colony/Area</label>
                    <p class="text-gray-900">{{ $customer->colony }}</p>
                </div>
                @endif
                <div>
                    <label class="text-sm font-medium text-gray-500">City</label>
                    <p class="text-gray-900">{{ $customer->city }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Country</label>
                    <p class="text-gray-900">{{ $customer->country }}</p>
                </div>
                @if($customer->address)
                <div>
                    <label class="text-sm font-medium text-gray-500">Complete Address</label>
                    <p class="text-gray-900">{{ $customer->address }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Financial Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-wallet mr-2 text-purple-600"></i>
                    Financial Information
                </h2>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Cash Balance</label>
                    <p class="text-2xl font-bold {{ $customer->cash_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        PKR {{ number_format($customer->cash_balance, 2) }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Payment Preference</label>
                    <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', $customer->payment_preference) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Sales</label>
                    <p class="text-gray-900">{{ $customer->sales->count() }} transactions</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Purchase Value</label>
                    <p class="text-gray-900">PKR {{ number_format($customer->sales->sum('net_bill'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales History -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history mr-2 text-yellow-600"></i>
                    Sales History
                </h2>
                <a href="{{ route('sales.create', ['customer' => $customer->id]) }}" class="btn-primary text-sm">
                    <i class="fas fa-plus mr-2"></i>New Sale
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($customer->sales->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customer->sales->sortByDesc('sale_date') as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $sale->sale_no }}</div>
                                    <div class="text-sm text-gray-500">{{ $sale->sale_date->format('M d, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">PKR {{ number_format($sale->net_bill) }}</div>
                                <div class="text-sm text-gray-500">Received: PKR {{ number_format($sale->total_received) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($sale->cash_balance == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                @elseif($sale->cash_balance > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Pending: PKR {{ number_format($sale->cash_balance) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Overpaid: PKR {{ number_format(abs($sale->cash_balance)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sales.invoice', $sale) }}" class="text-green-600 hover:text-green-900" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No sales history</h3>
                <p class="text-gray-500 mb-4">This customer hasn't made any purchases yet.</p>
                <a href="{{ route('sales.create', ['customer' => $customer->id]) }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Create First Sale
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Gold Purchases History -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-coins mr-2 text-yellow-600"></i>
                    Gold Purchases History
                </h2>
                <a href="{{ route('gold-purchases.create', ['customer' => $customer->id]) }}" class="btn-primary text-sm">
                    <i class="fas fa-plus mr-2"></i>New Purchase
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($customer->goldPurchases->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight & Karat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customer->goldPurchases->sortByDesc('date') as $purchase)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $purchase->voucher_no }}</div>
                                    <div class="text-sm text-gray-500">{{ $purchase->date->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $purchase->type) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $purchase->weight }}g ({{ $purchase->karat }})</div>
                                <div class="text-sm text-gray-500">Pure: {{ $purchase->pure_weight }}g</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">PKR {{ number_format($purchase->amount) }}</div>
                                <div class="text-sm text-gray-500">Rate: PKR {{ number_format($purchase->rate) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('gold-purchases.show', $purchase) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('gold-purchases.edit', $purchase) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-coins text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No gold purchases</h3>
                <p class="text-gray-500 mb-4">This customer hasn't sold any gold to you yet.</p>
                <a href="{{ route('gold-purchases.create', ['customer' => $customer->id]) }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Record First Purchase
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection