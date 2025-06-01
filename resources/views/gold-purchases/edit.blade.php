{{-- resources/views/gold-purchases/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Gold Purchase')
@section('page-title', 'Edit Gold Purchase: ' . $goldPurchase->voucher_no)
@section('page-description', 'Update gold purchase information')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('gold-purchases.show', $goldPurchase) }}" class="btn-secondary">
        <i class="fas fa-eye mr-2"></i>View Purchase
    </a>
    <a href="{{ route('gold-purchases.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Purchases
    </a>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <form method="POST" action="{{ route('gold-purchases.update', $goldPurchase) }}" class="space-y-6" x-data="goldPurchaseEditForm()">
        @csrf
        @method('PUT')
        
        <!-- Purchase Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Purchase Information</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Purchase Date *</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $goldPurchase->date->format('Y-m-d')) }}" 
                               class="form-input @error('date') border-red-500 @enderror" required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Gold Type *</label>
                        <select name="type" id="type" class="form-select @error('type') border-red-500 @enderror" required>
                            <option value="">Select Type</option>
                            <option value="pure_gold" {{ old('type', $goldPurchase->type) == 'pure_gold' ? 'selected' : '' }}>Pure Gold</option>
                            <option value="used_gold" {{ old('type', $goldPurchase->type) == 'used_gold' ? 'selected' : '' }}>Used Gold</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="voucher_no" class="block text-sm font-medium text-gray-700">Voucher Number *</label>
                        <input type="text" name="voucher_no" id="voucher_no" value="{{ old('voucher_no', $goldPurchase->voucher_no) }}" 
                               class="form-input @error('voucher_no') border-red-500 @enderror" required>
                        @error('voucher_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="form-input @error('description') border-red-500 @enderror">{{ old('description', $goldPurchase->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Customer/Seller Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Customer/Seller Information</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="flex items-center space-x-4 mb-4">
                    <label class="flex items-center">
                        <input type="radio" name="customer_type" value="existing" 
                               x-model="customerType" 
                               {{ $goldPurchase->customer_id ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm font-medium text-gray-700">Existing Customer</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="customer_type" value="new" 
                               x-model="customerType" 
                               {{ !$goldPurchase->customer_id ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm font-medium text-gray-700">New Customer</span>
                    </label>
                </div>

                <div x-show="customerType === 'existing'">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Select Customer</label>
                    <select name="customer_id" id="customer_id" class="form-select @error('customer_id') border-red-500 @enderror">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $goldPurchase->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->contact_no }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="customerType === 'new'" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name *</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $goldPurchase->customer_name) }}" 
                                   class="form-input @error('customer_name') border-red-500 @enderror">
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact Number *</label>
                            <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no', $goldPurchase->contact_no) }}" 
                                   class="form-input @error('contact_no') border-red-500 @enderror">
                            @error('contact_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="2" 
                                  class="form-input @error('address') border-red-500 @enderror">{{ old('address', $goldPurchase->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Gold Details -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Gold Details</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700">Total Weight (grams) *</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight', $goldPurchase->weight) }}" 
                               step="0.001" min="0" x-model="weight" @input="calculateAmounts()"
                               class="form-input @error('weight') border-red-500 @enderror" required>
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="karat" class="block text-sm font-medium text-gray-700">Karat *</label>
                        <select name="karat" id="karat" x-model="karat" @change="calculateAmounts()"
                                class="form-select @error('karat') border-red-500 @enderror" required>
                            <option value="">Select Karat</option>
                            <option value="24K" {{ old('karat', $goldPurchase->karat) == '24K' ? 'selected' : '' }}>24K</option>
                            <option value="22K" {{ old('karat', $goldPurchase->karat) == '22K' ? 'selected' : '' }}>22K</option>
                            <option value="21K" {{ old('karat', $goldPurchase->karat) == '21K' ? 'selected' : '' }}>21K</option>
                            <option value="18K" {{ old('karat', $goldPurchase->karat) == '18K' ? 'selected' : '' }}>18K</option>
                            <option value="14K" {{ old('karat', $goldPurchase->karat) == '14K' ? 'selected' : '' }}>14K</option>
                            <option value="10K" {{ old('karat', $goldPurchase->karat) == '10K' ? 'selected' : '' }}>10K</option>
                        </select>
                        @error('karat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pure_weight" class="block text-sm font-medium text-gray-700">Pure Weight (grams) *</label>
                        <input type="number" name="pure_weight" id="pure_weight" value="{{ old('pure_weight', $goldPurchase->pure_weight) }}" 
                               step="0.001" min="0" x-model="pureWeight"
                               class="form-input @error('pure_weight') border-red-500 @enderror" required>
                        @error('pure_weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="karrat_24" class="block text-sm font-medium text-gray-700">24K Equivalent (grams) *</label>
                        <input type="number" name="karrat_24" id="karrat_24" value="{{ old('karrat_24', $goldPurchase->karrat_24) }}" 
                               step="0.001" min="0" x-model="karrat24"
                               class="form-input @error('karrat_24') border-red-500 @enderror" required>
                        @error('karrat_24')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rate" class="block text-sm font-medium text-gray-700">Rate per Gram *</label>
                        <input type="number" name="rate" id="rate" value="{{ old('rate', $goldPurchase->rate) }}" 
                               step="0.01" min="0" x-model="rate" @input="calculateAmounts()"
                               class="form-input @error('rate') border-red-500 @enderror" required>
                        @error('rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Total Amount *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $goldPurchase->amount) }}" 
                               step="0.01" min="0" x-model="amount"
                               class="form-input @error('amount') border-red-500 @enderror" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
                    <label for="cash_payment" class="block text-sm font-medium text-gray-700">Cash Payment *</label>
                    <input type="number" name="cash_payment" id="cash_payment" value="{{ old('cash_payment', $goldPurchase->cash_payment) }}" 
                           step="0.01" min="0" x-model="cashPayment"
                           class="form-input @error('cash_payment') border-red-500 @enderror" required>
                    @error('cash_payment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Summary -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Payment Summary</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span>Total Amount:</span>
                            <span class="font-medium">PKR <span x-text="amount.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Cash Payment:</span>
                            <span class="font-medium">PKR <span x-text="cashPayment.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between col-span-2 border-t pt-2">
                            <span class="font-semibold">Balance:</span>
                            <span class="font-semibold" :class="balance >= 0 ? 'text-red-600' : 'text-green-600'">
                                PKR <span x-text="Math.abs(balance).toFixed(2)"></span>
                                <span x-show="balance > 0" class="text-red-600">(Pending)</span>
                                <span x-show="balance < 0" class="text-green-600">(Overpaid)</span>
                                <span x-show="balance === 0" class="text-green-600">(Settled)</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('gold-purchases.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Purchase</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function goldPurchaseEditForm() {
    return {
        customerType: '{{ $goldPurchase->customer_id ? "existing" : "new" }}',
        weight: {{ old('weight', $goldPurchase->weight) }},
        karat: '{{ old('karat', $goldPurchase->karat) }}',
        pureWeight: {{ old('pure_weight', $goldPurchase->pure_weight) }},
        karrat24: {{ old('karrat_24', $goldPurchase->karrat_24) }},
        rate: {{ old('rate', $goldPurchase->rate) }},
        amount: {{ old('amount', $goldPurchase->amount) }},
        cashPayment: {{ old('cash_payment', $goldPurchase->cash_payment) }},
        
        get balance() {
            return this.amount - this.cashPayment;
        },
        
        calculateAmounts() {
            if (this.weight && this.rate) {
                this.amount = this.weight * this.rate;
            }
            
            if (this.weight && this.karat) {
                const karatPercentages = {
                    '24K': 1.0,
                    '22K': 0.916,
                    '21K': 0.875,
                    '18K': 0.75,
                    '14K': 0.583,
                    '10K': 0.417
                };
                
                if (karatPercentages[this.karat]) {
                    this.pureWeight = this.weight * karatPercentages[this.karat];
                    this.karrat24 = this.pureWeight;
                }
            }
        }
    }
}
</script>
@endpush
@endsection