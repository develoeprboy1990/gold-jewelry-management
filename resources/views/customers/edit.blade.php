{{-- resources/views/customers/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')
@section('page-description', 'Update customer information')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('customers.show', $customer) }}" class="btn-secondary">
        <i class="fas fa-eye mr-2"></i>View Customer
    </a>
    <a href="{{ route('customers.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to List
    </a>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" 
                               class="form-input @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cnic" class="block text-sm font-medium text-gray-700">CNIC *</label>
                        <input type="text" name="cnic" id="cnic" value="{{ old('cnic', $customer->cnic) }}" 
                               placeholder="12345-1234567-1" 
                               class="form-input @error('cnic') border-red-500 @enderror" required>
                        @error('cnic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact Number *</label>
                        <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no', $customer->contact_no) }}" 
                               class="form-input @error('contact_no') border-red-500 @enderror" required>
                        @error('contact_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" 
                               class="form-input @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $customer->birth_date?->format('Y-m-d')) }}" 
                               class="form-input @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="anniversary_date" class="block text-sm font-medium text-gray-700">Anniversary Date</label>
                        <input type="date" name="anniversary_date" id="anniversary_date" value="{{ old('anniversary_date', $customer->anniversary_date?->format('Y-m-d')) }}" 
                               class="form-input @error('anniversary_date') border-red-500 @enderror">
                        @error('anniversary_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="company" class="block text-sm font-medium text-gray-700">Company/Organization</label>
                        <input type="text" name="company" id="company" value="{{ old('company', $customer->company) }}" 
                               class="form-input @error('company') border-red-500 @enderror">
                        @error('company')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Address Information</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="house_no" class="block text-sm font-medium text-gray-700">House #</label>
                        <input type="text" name="house_no" id="house_no" value="{{ old('house_no', $customer->house_no) }}" 
                               class="form-input @error('house_no') border-red-500 @enderror">
                        @error('house_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="street_no" class="block text-sm font-medium text-gray-700">Street #</label>
                        <input type="text" name="street_no" id="street_no" value="{{ old('street_no', $customer->street_no) }}" 
                               class="form-input @error('street_no') border-red-500 @enderror">
                        @error('street_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="block_no" class="block text-sm font-medium text-gray-700">Block #</label>
                        <input type="text" name="block_no" id="block_no" value="{{ old('block_no', $customer->block_no) }}" 
                               class="form-input @error('block_no') border-red-500 @enderror">
                        @error('block_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="colony" class="block text-sm font-medium text-gray-700">Colony/Area</label>
                        <input type="text" name="colony" id="colony" value="{{ old('colony', $customer->colony) }}" 
                               class="form-input @error('colony') border-red-500 @enderror">
                        @error('colony')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $customer->city) }}" 
                               class="form-input @error('city') border-red-500 @enderror" required>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Country *</label>
                        <select name="country" id="country" class="form-select @error('country') border-red-500 @enderror" required>
                            <option value="Pakistan" {{ old('country', $customer->country) == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                            <option value="India" {{ old('country', $customer->country) == 'India' ? 'selected' : '' }}>India</option>
                            <option value="UAE" {{ old('country', $customer->country) == 'UAE' ? 'selected' : '' }}>UAE</option>
                            <option value="Saudi Arabia" {{ old('country', $customer->country) == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                        </select>
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Complete Address</label>
                    <textarea name="address" id="address" rows="3" 
                              class="form-input @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Financial Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Financial Information</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="cash_balance" class="block text-sm font-medium text-gray-700">Cash Balance</label>
                        <input type="number" name="cash_balance" id="cash_balance" value="{{ old('cash_balance', $customer->cash_balance) }}" 
                               step="0.01" 
                               class="form-input @error('cash_balance') border-red-500 @enderror">
                        @error('cash_balance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_preference" class="block text-sm font-medium text-gray-700">Payment Preference *</label>
                        <select name="payment_preference" id="payment_preference" 
                                class="form-select @error('payment_preference') border-red-500 @enderror" required>
                            <option value="cash" {{ old('payment_preference', $customer->payment_preference) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="credit_card" {{ old('payment_preference', $customer->payment_preference) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="check" {{ old('payment_preference', $customer->payment_preference) == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="pure_gold" {{ old('payment_preference', $customer->payment_preference) == 'pure_gold' ? 'selected' : '' }}>Pure Gold</option>
                            <option value="used_gold" {{ old('payment_preference', $customer->payment_preference) == 'used_gold' ? 'selected' : '' }}>Used Gold</option>
                        </select>
                        @error('payment_preference')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('customers.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Customer</button>
        </div>
    </form>
</div>
@endsection