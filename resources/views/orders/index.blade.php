{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Orders & Estimates')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Orders & Estimates</h1>
            <p class="mt-2 text-gray-600">Manage custom orders and customer estimates</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            New Order
        </a>
    </div>

    <!-- Status Filter Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('orders.index') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                All Orders
            </a>
            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="border-orange-500 text-orange-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Pending
            </a>
            <a href="{{ route('orders.index', ['status' => 'in_progress']) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                In Progress
            </a>
            <a href="{{ route('orders.index', ['status' => 'ready']) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Ready
            </a>
        </nav>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount & Payment
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $order->order_no }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->order_date->format('M d, Y') }}</div>
                                    @if($order->promised_date)
                                        <div class="text-sm text-blue-600">Delivery: {{ $order->promised_date->format('M d, Y') }}</div>
                                    @endif
                                    <div class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer->contact_no }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">PKR {{ number_format($order->estimated_total) }}</div>
                                <div class="text-sm text-gray-500">Advance: PKR {{ number_format($order->advance_payment) }}</div>
                                <div class="text-sm text-red-600">Pending: PKR {{ number_format($order->estimated_total - $order->advance_payment) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'ready' => 'bg-green-100 text-green-800',
                                        'delivered' => 'bg-gray-100 text-gray-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('orders.receipt', $order) }}" class="text-green-600 hover:text-green-900" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @if($order->status === 'ready')
                                    <a href="{{ route('orders.convert-to-sale', $order) }}" class="text-purple-600 hover:text-purple-900" title="Convert to Sale">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                @endif
                                <a href="{{ route('orders.edit', $order) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($order->status !== 'delivered')
                                    <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                onclick="return confirm('Are you sure you want to delete this order?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No orders found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection