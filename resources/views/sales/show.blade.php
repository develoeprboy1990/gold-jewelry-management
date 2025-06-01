{{-- resources/views/sales/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Sale Details')
@section('page-title', 'Sale: ' . $sale->sale_no)
@section('page-description', 'Sale transaction details and information')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('sales.invoice', $sale) }}" class="btn-primary" target="_blank">
        <i class="fas fa-file-pdf mr-2"></i>Generate Invoice
    </a>
    <a href="{{ route('sales.edit', $sale) }}" class="btn-secondary">
        <i class="fas fa-edit mr-2"></i>Edit Sale
    </a>
    <a href="{{ route('sales.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Sales
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Sale Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-receipt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sale Number</p>
                    <p class="text-lg font-bold text-gray-900">{{ $sale->sale_no }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Net Bill</p>
                    <p class="text-lg font-bold text-green-600">PKR {{ number_format($sale->net_bill) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-credit-card text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Received</p>
                    <p class="text-lg font-bold text-yellow-600">PKR {{ number_format($sale->total_received) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon {{ $sale->cash_balance == 0 ? 'bg-green-100' : ($sale->cash_balance > 0 ? 'bg-red-100' : 'bg-blue-100') }}">
                    <i class="fas fa-balance-scale {{ $sale->cash_balance == 0 ? 'text-green-600' : ($sale->cash_balance > 0 ? 'text-red-600' : 'text-blue-600') }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Balance</p>
                    <p class="text-lg font-bold {{ $sale->cash_balance == 0 ? 'text-green-600' : ($sale->cash_balance > 0 ? 'text-red-600' : 'text-blue-600') }}">
                        PKR {{ number_format(abs($sale->cash_balance)) }}
                        @if($sale->cash_balance > 0) (Pending) @elseif($sale->cash_balance < 0) (Overpaid) @else (Paid) @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sale Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Sale Information
                </h2>
            </div>
            <div class="card-body space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Sale Date</label>
                        <p class="text-gray-900">{{ $sale->sale_date->format('M d, Y') }}</p>
                    </div>
                    @if($sale->bill_book_no)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Bill Book No.</label>
                        <p class="text-gray-900">{{ $sale->bill_book_no }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-gray-500">Sale Person</label>
                        <p class="text-gray-900">{{ $sale->user->name }}</p>
                    </div>
                    @if($sale->promise_date)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Promise Date</label>
                        <p class="text-gray-900">{{ $sale->promise_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user mr-2 text-green-600"></i>
                        Customer Information
                    </h2>
                    <a href="{{ route('customers.show', $sale->customer) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View Profile →
                    </a>
                </div>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $sale->customer->name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">CNIC</label>
                        <p class="font-mono text-gray-900">{{ $sale->customer->cnic }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Contact</label>
                        <p class="text-gray-900">{{ $sale->customer->contact_no }}</p>
                    </div>
                    @if($sale->customer->email)
                    <div class="col-span-2">
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $sale->customer->email }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Items -->
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-shopping-cart mr-2 text-yellow-600"></i>
                Sale Items
            </h2>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight & Making</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gold Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pricing</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sale->saleItems as $saleItem)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($saleItem->item->images && count($saleItem->item->images) > 0)
                                        <img src="{{ Storage::url($saleItem->item->images[0]) }}" 
                                             alt="{{ $saleItem->item->group_item }}" 
                                             class="w-12 h-12 object-cover rounded-lg mr-4">
                                    @else
                                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-ring text-yellow-600"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $saleItem->item->group_item }}</div>
                                        <div class="text-sm text-gray-500">{{ $saleItem->item->tag_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $saleItem->item->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div>Weight: <span class="font-medium">{{ $saleItem->weight }}g</span></div>
                                    <div>Waste: <span class="font-medium">{{ $saleItem->waste_percentage }}% ({{ $saleItem->waste_weight }}g)</span></div>
                                    <div>Total: <span class="font-medium">{{ $saleItem->total_weight }}g</span></div>
                                    <div>Making: <span class="font-medium">PKR {{ number_format($saleItem->making_per_gram) }}/g</span></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div>Rate: <span class="font-medium">PKR {{ number_format($saleItem->gold_rate) }}</span></div>
                                    <div>Gold Price: <span class="font-medium">PKR {{ number_format($saleItem->gold_price) }}</span></div>
                                    <div>Making Cost: <span class="font-medium">PKR {{ number_format($saleItem->total_making) }}</span></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div>Stone: <span class="font-medium">PKR {{ number_format($saleItem->stone_price) }}</span></div>
                                    <div>Other: <span class="font-medium">PKR {{ number_format($saleItem->other_charges) }}</span></div>
                                    <div>Total: <span class="font-medium">PKR {{ number_format($saleItem->total_price) }}</span></div>
                                    <div>Discount: <span class="font-medium text-red-600">PKR {{ number_format($saleItem->discount) }}</span></div>
                                    <div class="font-bold text-green-600 border-t pt-1">Net: PKR {{ number_format($saleItem->net_price) }}</div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bill Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bill Totals -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-calculator mr-2 text-purple-600"></i>
                    Bill Summary
                </h2>
            </div>
            <div class="card-body space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Making:</span>
                    <span class="font-medium">PKR {{ number_format($sale->total_making) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Stone Charges:</span>
                    <span class="font-medium">PKR {{ number_format($sale->total_stone_charges) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Other Charges:</span>
                    <span class="font-medium">PKR {{ number_format($sale->total_other_charges) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Gold Price:</span>
                    <span class="font-medium">PKR {{ number_format($sale->total_gold_price) }}</span>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <span class="text-gray-600">Gross Total:</span>
                    <span class="font-semibold">PKR {{ number_format($sale->total_making + $sale->total_stone_charges + $sale->total_other_charges + $sale->total_gold_price) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Item Discount:</span>
                    <span class="font-medium text-red-600">PKR {{ number_format($sale->total_item_discount) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Bill Discount:</span>
                    <span class="font-medium text-red-600">PKR {{ number_format($sale->bill_discount) }}</span>
                </div>
                <div class="flex justify-between border-t pt-2 text-lg">
                    <span class="font-bold text-gray-900">Net Bill:</span>
                    <span class="font-bold text-green-600">PKR {{ number_format($sale->net_bill) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-credit-card mr-2 text-green-600"></i>
                    Payment Details
                </h2>
            </div>
            <div class="card-body space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Cash Received:</span>
                    <span class="font-medium">PKR {{ number_format($sale->cash_received) }}</span>
                </div>
                @if($sale->credit_card_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Credit Card:</span>
                    <span class="font-medium">PKR {{ number_format($sale->credit_card_amount) }}</span>
                </div>
                @endif
                @if($sale->check_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Check Amount:</span>
                    <span class="font-medium">PKR {{ number_format($sale->check_amount) }}</span>
                </div>
                @endif
                @if($sale->used_gold_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Used Gold:</span>
                    <span class="font-medium">PKR {{ number_format($sale->used_gold_amount) }}</span>
                </div>
                @endif
                @if($sale->pure_gold_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Pure Gold:</span>
                    <span class="font-medium">PKR {{ number_format($sale->pure_gold_amount) }}</span>
                </div>
                @endif
                <div class="flex justify-between border-t pt-2">
                    <span class="font-semibold text-gray-900">Total Received:</span>
                    <span class="font-semibold text-blue-600">PKR {{ number_format($sale->total_received) }}</span>
                </div>
                <div class="flex justify-between border-t pt-2 text-lg">
                    <span class="font-bold text-gray-900">Cash Balance:</span>
                    <span class="font-bold {{ $sale->cash_balance == 0 ? 'text-green-600' : ($sale->cash_balance > 0 ? 'text-red-600' : 'text-blue-600') }}">
                        PKR {{ number_format(abs($sale->cash_balance)) }}
                        @if($sale->cash_balance > 0) (Pending) @elseif($sale->cash_balance < 0) (Advance) @else (Settled) @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection