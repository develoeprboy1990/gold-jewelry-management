{{-- resources/views/sales/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Sale')
@section('page-title', 'Edit Sale')
@section('page-description', 'Modify sale details and payment information')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('sales.show', $sale) }}" class="btn-secondary">
        <i class="fas fa-eye mr-2"></i>
        View Sale
    </a>
    <a href="{{ route('sales.invoice', $sale) }}" class="btn-primary" target="_blank">
        <i class="fas fa-file-pdf mr-2"></i>
        Generate Invoice
    </a>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Sale Information Header -->
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Sale #{{ $sale->sale_no }}</h2>
                    <p class="text-sm text-gray-600">Created on {{ $sale->sale_date->format('F d, Y') }} by {{ $sale->user->name }}</p>
                </div>
                <div class="text-right">
                    @if($sale->cash_balance == 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Fully Paid
                        </span>
                    @elseif($sale->cash_balance > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Balance Pending
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Overpaid
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('sales.update', $sale) }}" id="sale-edit-form" x-data="saleEditForm()" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Sale Header Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Sale Information</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date *</label>
                        <input type="date" name="sale_date" id="sale_date" 
                               value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" 
                               class="form-input @error('sale_date') border-red-500 @enderror" required>
                        @error('sale_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bill_book_no" class="block text-sm font-medium text-gray-700">Bill Book No.</label>
                        <input type="text" name="bill_book_no" id="bill_book_no" 
                               value="{{ old('bill_book_no', $sale->bill_book_no) }}" 
                               class="form-input @error('bill_book_no') border-red-500 @enderror">
                        @error('bill_book_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                        <select name="customer_id" id="customer_id" 
                                class="form-select @error('customer_id') border-red-500 @enderror" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                        {{ old('customer_id', $sale->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->contact_no }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sale Items (Read-only display) -->
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Sale Items</h2>
                    <span class="text-sm text-gray-500">{{ $sale->saleItems->count() }} items</span>
                </div>
            </div>
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Weight</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Wt.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Making</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gold Rate</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gold Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Others</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($sale->saleItems as $saleItem)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $saleItem->item->tag_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $saleItem->item->group_item }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($saleItem->weight, 3) }}g</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($saleItem->total_weight, 3) }}g</td>
                                <td class="px-4 py-3 text-sm text-gray-900">PKR {{ number_format($saleItem->total_making) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">PKR {{ number_format($saleItem->gold_rate) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">PKR {{ number_format($saleItem->gold_price) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">PKR {{ number_format($saleItem->stone_price) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">PKR {{ number_format($saleItem->other_charges) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">PKR {{ number_format($saleItem->discount) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">PKR {{ number_format($saleItem->net_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Total Making:</span>
                            <span class="font-semibold ml-2">PKR {{ number_format($sale->total_making) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Stone:</span>
                            <span class="font-semibold ml-2">PKR {{ number_format($sale->total_stone_charges) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Gold:</span>
                            <span class="font-semibold ml-2">PKR {{ number_format($sale->total_gold_price) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Other Charges:</span>
                            <span class="font-semibold ml-2">PKR {{ number_format($sale->total_other_charges) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill Summary and Payment Updates -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Bill Summary -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900">Bill Summary</h2>
                </div>
                <div class="card-body space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gross Total:</span>
                        <span class="font-semibold">PKR {{ number_format($sale->total_making + $sale->total_stone_charges + $sale->total_other_charges + $sale->total_gold_price) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Item Discounts:</span>
                        <span class="font-semibold text-red-600">- PKR {{ number_format($sale->total_item_discount) }}</span>
                    </div>

                    <div>
                        <label for="bill_discount" class="block text-sm font-medium text-gray-700">Bill Discount</label>
                        <input type="number" name="bill_discount" id="bill_discount" 
                               value="{{ old('bill_discount', $sale->bill_discount) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('bill_discount') border-red-500 @enderror"
                               x-model="billDiscount" @input="calculateBalance()">
                        @error('bill_discount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between border-t pt-4 text-lg">
                        <span class="font-bold">Net Bill:</span>
                        <span class="font-bold text-green-600" x-text="'PKR ' + netBill.toLocaleString()"></span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900">Payment Information</h2>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <label for="cash_received" class="block text-sm font-medium text-gray-700">Cash Received</label>
                        <input type="number" name="cash_received" id="cash_received" 
                               value="{{ old('cash_received', $sale->cash_received) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('cash_received') border-red-500 @enderror"
                               x-model="payment.cash" @input="calculateBalance()">
                        @error('cash_received')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="credit_card_amount" class="block text-sm font-medium text-gray-700">Credit Card</label>
                        <input type="number" name="credit_card_amount" id="credit_card_amount" 
                               value="{{ old('credit_card_amount', $sale->credit_card_amount) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('credit_card_amount') border-red-500 @enderror"
                               x-model="payment.credit_card" @input="calculateBalance()">
                        @error('credit_card_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="check_amount" class="block text-sm font-medium text-gray-700">Check</label>
                        <input type="number" name="check_amount" id="check_amount" 
                               value="{{ old('check_amount', $sale->check_amount) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('check_amount') border-red-500 @enderror"
                               x-model="payment.check" @input="calculateBalance()">
                        @error('check_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="used_gold_amount" class="block text-sm font-medium text-gray-700">Used Gold</label>
                        <input type="number" name="used_gold_amount" id="used_gold_amount" 
                               value="{{ old('used_gold_amount', $sale->used_gold_amount) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('used_gold_amount') border-red-500 @enderror"
                               x-model="payment.used_gold" @input="calculateBalance()">
                        @error('used_gold_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pure_gold_amount" class="block text-sm font-medium text-gray-700">Pure Gold</label>
                        <input type="number" name="pure_gold_amount" id="pure_gold_amount" 
                               value="{{ old('pure_gold_amount', $sale->pure_gold_amount) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('pure_gold_amount') border-red-500 @enderror"
                               x-model="payment.pure_gold" @input="calculateBalance()">
                        @error('pure_gold_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="font-semibold">Total Received:</span>
                            <span class="font-semibold" x-text="'PKR ' + totalReceived.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Balance:</span>
                            <span class="font-semibold" 
                                  :class="balance >= 0 ? 'text-red-600' : 'text-green-600'"
                                  x-text="'PKR ' + Math.abs(balance).toLocaleString() + (balance >= 0 ? ' (Pending)' : ' (Overpaid)')"></span>
                        </div>
                    </div>

                    <div>
                        <label for="promise_date" class="block text-sm font-medium text-gray-700">Promise Date (if balance pending)</label>
                        <input type="date" name="promise_date" id="promise_date" 
                               value="{{ old('promise_date', $sale->promise_date?->format('Y-m-d')) }}" 
                               class="form-input @error('promise_date') border-red-500 @enderror">
                        @error('promise_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between">
            <div class="flex space-x-4">
                <a href="{{ route('sales.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Sales
                </a>
                <a href="{{ route('sales.show', $sale) }}" class="btn-secondary">
                    <i class="fas fa-eye mr-2"></i>
                    View Details
                </a>
            </div>
            <div class="flex space-x-4">
                <button type="button" onclick="resetForm()" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-undo mr-2"></i>
                    Reset Changes
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Update Sale
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function saleEditForm() {
    return {
        billDiscount: {{ $sale->bill_discount }},
        payment: {
            cash: {{ $sale->cash_received }},
            credit_card: {{ $sale->credit_card_amount }},
            check: {{ $sale->check_amount }},
            used_gold: {{ $sale->used_gold_amount }},
            pure_gold: {{ $sale->pure_gold_amount }}
        },
        grossTotal: {{ $sale->total_making + $sale->total_stone_charges + $sale->total_other_charges + $sale->total_gold_price }},
        itemDiscounts: {{ $sale->total_item_discount }},

        get netBill() {
            return this.grossTotal - this.itemDiscounts - this.billDiscount;
        },

        get totalReceived() {
            return parseFloat(this.payment.cash || 0) + 
                   parseFloat(this.payment.credit_card || 0) + 
                   parseFloat(this.payment.check || 0) + 
                   parseFloat(this.payment.used_gold || 0) + 
                   parseFloat(this.payment.pure_gold || 0);
        },

        get balance() {
            return this.netBill - this.totalReceived;
        },

        calculateBalance() {
            // This triggers reactive updates
        }
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        location.reload();
    }
}

// Show confirmation before leaving with unsaved changes
let formChanged = false;
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sale-edit-form');
    const initialData = new FormData(form);
    
    form.addEventListener('input', function() {
        formChanged = true;
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    form.addEventListener('submit', function() {
        formChanged = false;
    });
});
</script>
@endpush
@endsection