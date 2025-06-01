{{-- resources/views/orders/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Edit Order #{{ $order->order_no }}</h1>
        <p class="mt-2 text-gray-600">Update order information and status</p>
    </div>

    <form method="POST" action="{{ route('orders.update', $order) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Order Header -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Order Information</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date *</label>
                        <input type="date" name="order_date" id="order_date" 
                               value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" 
                               class="form-input @error('order_date') border-red-500 @enderror" required>
                        @error('order_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="promised_date" class="block text-sm font-medium text-gray-700">Promised Delivery Date</label>
                        <input type="date" name="promised_date" id="promised_date" 
                               value="{{ old('promised_date', $order->promised_date?->format('Y-m-d')) }}" 
                               class="form-input @error('promised_date') border-red-500 @enderror">
                        @error('promised_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="order_type" class="block text-sm font-medium text-gray-700">Order Type *</label>
                        <select name="order_type" id="order_type" 
                                class="form-select @error('order_type') border-red-500 @enderror" required>
                            <option value="custom_order" {{ old('order_type', $order->order_type) == 'custom_order' ? 'selected' : '' }}>Custom Order</option>
                            <option value="repair" {{ old('order_type', $order->order_type) == 'repair' ? 'selected' : '' }}>Repair</option>
                            <option value="size_adjustment" {{ old('order_type', $order->order_type) == 'size_adjustment' ? 'selected' : '' }}>Size Adjustment</option>
                        </select>
                        @error('order_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" id="status" 
                                class="form-select @error('status') border-red-500 @enderror" required>
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $order->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="ready" {{ old('status', $order->status) == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                        <select name="customer_id" id="customer_id" 
                                class="form-select @error('customer_id') border-red-500 @enderror" required>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                        {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
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

        <!-- Financial Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Financial Information</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="advance_payment" class="block text-sm font-medium text-gray-700">Advance Payment *</label>
                        <input type="number" name="advance_payment" id="advance_payment" 
                               value="{{ old('advance_payment', $order->advance_payment) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('advance_payment') border-red-500 @enderror" required>
                        @error('advance_payment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estimated Total</label>
                        <input type="text" value="PKR {{ number_format($order->estimated_total, 2) }}" 
                               class="form-input bg-gray-100" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Remaining Balance</label>
                        <input type="text" 
                               value="PKR {{ number_format($order->estimated_total - $order->advance_payment, 2) }}" 
                               class="form-input bg-gray-100 font-semibold text-red-600" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Instructions -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Additional Information</h2>
            </div>
            <div class="card-body">
                <div>
                    <label for="special_instructions" class="block text-sm font-medium text-gray-700">Special Instructions</label>
                    <textarea name="special_instructions" id="special_instructions" 
                              rows="4" class="form-input @error('special_instructions') border-red-500 @enderror" 
                              placeholder="Any special requirements, design preferences, delivery instructions...">{{ old('special_instructions', $order->special_instructions) }}</textarea>
                    @error('special_instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Order Items (Read Only) -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                <p class="text-sm text-gray-600">Items cannot be edited here. Create a new order for changes.</p>
            </div>
            <div class="card-body p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Weight</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($item->item_type) }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->description }}</div>
                                        @if($item->karat)
                                            <div class="text-xs text-gray-400">{{ $item->karat }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $item->estimated_weight ? $item->estimated_weight . 'g' : 'TBD' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    PKR {{ number_format($item->estimated_total) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('orders.show', $order) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Order</button>
        </div>
    </form>
</div>
@endsection