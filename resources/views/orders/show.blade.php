{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->order_no }}</h1>
            <p class="mt-2 text-gray-600">Order details and status</p>
        </div>
        <div class="flex space-x-3">
            @if($order->status === 'pending' || $order->status === 'in_progress')
                <form method="POST" action="{{ route('orders.mark-ready', $order) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-primary">Mark as Ready</button>
                </form>
            @endif
            
            @if($order->status === 'ready')
                <a href="{{ route('orders.convert-to-sale', $order) }}" class="btn-primary">
                    <i class="fas fa-shopping-cart mr-2"></i>Convert to Sale
                </a>
            @endif
            
            <a href="{{ route('orders.receipt', $order) }}" class="btn-secondary" target="_blank">
                <i class="fas fa-file-pdf mr-2"></i>Print Receipt
            </a>
            
            <a href="{{ route('orders.edit', $order) }}" class="btn-secondary">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Details -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900">Order Information</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Order Date</label>
                            <p class="text-sm text-gray-900">{{ $order->order_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Order Type</label>
                            <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</p>
                        </div>
                        @if($order->promised_date)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Promised Date</label>
                            <p class="text-sm text-gray-900">{{ $order->promised_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->status === 'ready' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'delivered' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($order->special_instructions)
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-500">Special Instructions</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $order->special_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
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
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($item->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Financial Info -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Name</label>
                            <p class="text-sm text-gray-900">{{ $order->customer->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Contact</label>
                            <p class="text-sm text-gray-900">{{ $order->customer->contact_no }}</p>
                        </div>
                        @if($order->customer->email)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-sm text-gray-900">{{ $order->customer->email }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-500">Address</label>
                            <p class="text-sm text-gray-900">{{ $order->customer->city }}, {{ $order->customer->country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900">Financial Summary</h2>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Estimated Total</span>
                            <span class="text-sm font-medium">PKR {{ number_format($order->estimated_total) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Advance Paid</span>
                            <span class="text-sm font-medium text-green-600">PKR {{ number_format($order->advance_payment) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-sm font-medium">Remaining Balance</span>
                            <span class="text-sm font-bold text-red-600">PKR {{ number_format($order->estimated_total - $order->advance_payment) }}</span>
                        </div>
                        
                        @if($order->final_amount)
                        <div class="border-t pt-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium">Final Amount</span>
                                <span class="text-sm font-bold">PKR {{ number_format($order->final_amount) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-semibold text-gray-900">Order Timeline</h2>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium">Order Created</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($order->status !== 'pending')
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium">In Progress</p>
                                <p class="text-xs text-gray-500">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'ready' || $order->status === 'delivered')
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium">Ready for Delivery</p>
                                <p class="text-xs text-gray-500">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status === 'delivered')
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium">Delivered</p>
                                <p class="text-xs text-gray-500">{{ $order->delivery_date ? $order->delivery_date->format('M d, Y H:i') : 'Date not recorded' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection