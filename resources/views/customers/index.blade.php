{{-- resources/views/customers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Customers</h1>
            <p class="mt-2 text-gray-600">Manage your customer database</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Add Customer
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('customers.index') }}" class="flex space-x-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search customers by name, CNIC, or contact..." 
                           class="form-input">
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Info
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Balance
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                    <div class="text-sm text-gray-500">CNIC: {{ $customer->cnic }}</div>
                                    @if($customer->company)
                                        <div class="text-sm text-gray-500">{{ $customer->company }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $customer->contact_no }}</div>
                                @if($customer->email)
                                    <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $customer->city }}</div>
                                <div class="text-sm text-gray-500">{{ $customer->country }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium {{ $customer->cash_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    PKR {{ number_format($customer->cash_balance) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure you want to delete this customer?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No customers found
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
        {{ $customers->links() }}
    </div>
</div>
@endsection