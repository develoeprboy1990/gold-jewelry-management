{{-- resources/views/sales/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sales</h1>
            <p class="mt-2 text-gray-600">Manage jewelry sales and transactions</p>
        </div>
        <a href="{{ route('sales.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            New Sale
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('sales.index') }}" class="flex space-x-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by sale number, customer name..." 
                           class="form-input">
                </div>
                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="form-input" placeholder="From Date">
                </div>
                <div>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="form-input" placeholder="To Date">
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sale Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $sale->sale_no }}</div>
                                    <div class="text-sm text-gray-500">{{ $sale->sale_date->format('M d, Y') }}</div>
                                    @if($sale->bill_book_no)
                                        <div class="text-sm text-gray-500">Bill: {{ $sale->bill_book_no }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $sale->customer->contact_no }}</div>
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
                                <a href="{{ route('sales.edit', $sale) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('sales.destroy', $sale) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure you want to delete this sale?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No sales found
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
        {{ $sales->links() }}
    </div>
</div>
@endsection