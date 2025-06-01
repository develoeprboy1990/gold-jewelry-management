{{-- resources/views/gold-purchases/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gold Purchases')
@section('page-title', 'Gold Purchases')
@section('page-description', 'Manage gold purchase transactions')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('gold-purchases.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>New Purchase
    </a>
    <button class="btn-secondary">
        <i class="fas fa-download mr-2"></i>Export Data
    </button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Purchase Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-100">
                    <i class="fas fa-coins text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Purchases</p>
                    <p class="text-2xl font-bold text-gray-900">{{ @$goldPurchases->total() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-100">
                    <i class="fas fa-weight text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Weight</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($goldPurchases->sum('weight'), 2) }}g</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-100">
                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900">PKR {{ number_format($goldPurchases->sum('amount')) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-purple-100">
                    <i class="fas fa-calendar text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $goldPurchases->where('date', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('gold-purchases.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by voucher, customer name..." 
                           class="form-input">
                </div>
                <div>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="pure_gold" {{ request('type') == 'pure_gold' ? 'selected' : '' }}>Pure Gold</option>
                        <option value="used_gold" {{ request('type') == 'used_gold' ? 'selected' : '' }}>Used Gold</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="form-input" placeholder="From Date">
                </div>
                <div class="flex space-x-2">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="form-input flex-1" placeholder="To Date">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('gold-purchases.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer/Seller</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight & Karat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate & Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($goldPurchases as $purchase)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $purchase->voucher_no }}</div>
                                    <div class="text-sm text-gray-500">{{ $purchase->date->format('M d, Y') }}</div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $purchase->type == 'pure_gold' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $purchase->type)) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    @if($purchase->customer)
                                        <div class="text-sm font-medium text-gray-900">{{ $purchase->customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $purchase->customer->contact_no }}</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-900">{{ $purchase->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $purchase->contact_no }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $purchase->weight }}g ({{ $purchase->karat }})</div>
                                    <div class="text-sm text-gray-500">Pure: {{ $purchase->pure_weight }}g</div>
                                    <div class="text-sm text-gray-500">24K: {{ $purchase->karrat_24 }}g</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">PKR {{ number_format($purchase->rate) }}/g</div>
                                    <div class="text-lg font-bold text-green-600">PKR {{ number_format($purchase->amount) }}</div>
                                    <div class="text-sm text-gray-500">Paid: PKR {{ number_format($purchase->cash_payment) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('gold-purchases.show', $purchase) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('gold-purchases.edit', $purchase) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('gold-purchases.destroy', $purchase) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure you want to delete this purchase?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-coins text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No gold purchases found</h3>
                                <p class="mb-4">Start recording your gold purchase transactions.</p>
                                <a href="{{ route('gold-purchases.create') }}" class="btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Record First Purchase
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($goldPurchases->hasPages())
    <div class="flex justify-center">
        {{ $goldPurchases->links() }}
    </div>
    @endif
</div>
@endsection