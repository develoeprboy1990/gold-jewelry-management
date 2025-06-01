{{-- resources/views/sales/create.blade.php --}}
@extends('layouts.app')

@section('title', 'New Sale')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Create New Sale</h1>
        <p class="mt-2 text-gray-600">Process a new jewelry sale</p>
    </div>

    <form method="POST" action="{{ route('sales.store') }}" id="sale-form" x-data="saleForm()" class="space-y-6">
        @csrf
        
        <!-- Sale Header -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Sale Information</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date *</label>
                        <input type="date" name="sale_date" id="sale_date" 
                               value="{{ old('sale_date', date('Y-m-d')) }}" 
                               class="form-input" required>
                    </div>

                    <div>
                        <label for="bill_book_no" class="block text-sm font-medium text-gray-700">Bill Book No.</label>
                        <input type="text" name="bill_book_no" id="bill_book_no" 
                               value="{{ old('bill_book_no') }}" 
                               class="form-input">
                    </div>

                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                        <select name="customer_id" id="customer_id" class="form-select" required x-model="selectedCustomer">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->contact_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Selection -->
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Items</h2>
                    <button type="button" @click="addItem()" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>Add Item
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Item</label>
                                    <select :name="`items[${index}][item_id]`" class="form-select" x-model="item.item_id" @change="updateItemDetails(index)">
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-weight="{{ $item->weight }}" data-price="{{ $item->total_price }}">
                                                {{ $item->tag_number }} - {{ $item->group_item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Weight (g)</label>
                                    <input type="number" :name="`items[${index}][weight]`" 
                                           x-model="item.weight" step="0.001" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Waste %</label>
                                    <input type="number" :name="`items[${index}][waste_percentage]`" 
                                           x-model="item.waste_percentage" step="0.01" min="0" max="100" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Making/Gram</label>
                                    <input type="number" :name="`items[${index}][making_per_gram]`" 
                                           x-model="item.making_per_gram" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div class="flex items-end">
                                    <button type="button" @click="removeItem(index)" class="btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stone Price</label>
                                    <input type="number" :name="`items[${index}][stone_price]`" 
                                           x-model="item.stone_price" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Other Charges</label>
                                    <input type="number" :name="`items[${index}][other_charges]`" 
                                           x-model="item.other_charges" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gold Rate</label>
                                    <input type="number" :name="`items[${index}][gold_rate]`" 
                                           x-model="item.gold_rate" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Discount</label>
                                    <input type="number" :name="`items[${index}][discount]`" 
                                           x-model="item.discount" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>
                            </div>

                            <div class="mt-4 bg-white p-3 rounded border">
                                <div class="grid grid-cols-4 gap-4 text-sm">
                                    <div>Total Weight: <span class="font-semibold" x-text="item.total_weight.toFixed(3)"></span>g</div>
                                    <div>Making Cost: <span class="font-semibold">PKR <span x-text="item.making_cost.toFixed(2)"></span></span></div>
                                    <div>Gold Price: <span class="font-semibold">PKR <span x-text="item.gold_price.toFixed(2)"></span></span></div>
                                    <div>Net Price: <span class="font-semibold">PKR <span x-text="item.net_price.toFixed(2)"></span></span></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Bill Summary -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Bill Summary</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Totals -->
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span>Total Making:</span>
                            <span class="font-semibold">PKR <span x-text="totals.making.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Stone Charges:</span>
                            <span class="font-semibold">PKR <span x-text="totals.stone.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Other Charges:</span>
                            <span class="font-semibold">PKR <span x-text="totals.other.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Gold Price:</span>
                            <span class="font-semibold">PKR <span x-text="totals.gold.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="font-semibold">Gross Total:</span>
                            <span class="font-semibold">PKR <span x-text="totals.gross.toFixed(2)"></span></span>
                        </div>
                        
                        <div>
                            <label for="bill_discount" class="block text-sm font-medium text-gray-700">Bill Discount</label>
                            <input type="number" name="bill_discount" id="bill_discount" 
                                   x-model="billDiscount" step="0.01" min="0" 
                                   class="form-input" @input="calculateTotals()">
                        </div>

                        <div class="flex justify-between border-t pt-2 text-lg">
                            <span class="font-bold">Net Bill:</span>
                            <span class="font-bold text-green-600">PKR <span x-text="totals.net.toFixed(2)"></span></span>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900">Payment Details</h3>
                        
                        <div>
                            <label for="cash_received" class="block text-sm font-medium text-gray-700">Cash Received</label>
                            <input type="number" name="cash_received" id="cash_received" 
                                   x-model="payment.cash" step="0.01" min="0" 
                                   class="form-input" @input="calculateBalance()">
                        </div>

                        <div>
                            <label for="credit_card_amount" class="block text-sm font-medium text-gray-700">Credit Card</label>
                            <input type="number" name="credit_card_amount" id="credit_card_amount" 
                                   x-model="payment.credit_card" step="0.01" min="0" 
                                   class="form-input" @input="calculateBalance()">
                        </div>

                        <div>
                            <label for="check_amount" class="block text-sm font-medium text-gray-700">Check</label>
                            <input type="number" name="check_amount" id="check_amount" 
                                   x-model="payment.check" step="0.01" min="0" 
                                   class="form-input" @input="calculateBalance()">
                        </div>

                        <div>
                            <label for="used_gold_amount" class="block text-sm font-medium text-gray-700">Used Gold</label>
                            <input type="number" name="used_gold_amount" id="used_gold_amount" 
                                   x-model="payment.used_gold" step="0.01" min="0" 
                                   class="form-input" @input="calculateBalance()">
                        </div>

                        <div>
                            <label for="pure_gold_amount" class="block text-sm font-medium text-gray-700">Pure Gold</label>
                            <input type="number" name="pure_gold_amount" id="pure_gold_amount" 
                                   x-model="payment.pure_gold" step="0.01" min="0" 
                                   class="form-input" @input="calculateBalance()">
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between">
                                <span class="font-semibold">Total Received:</span>
                                <span class="font-semibold">PKR <span x-text="payment.total.toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="font-semibold">Balance:</span>
                                <span class="font-semibold" :class="payment.balance >= 0 ? 'text-green-600' : 'text-red-600'">
                                    PKR <span x-text="payment.balance.toFixed(2)"></span>
                                </span>
                            </div>
                        </div>

                        <div>
                            <label for="promise_date" class="block text-sm font-medium text-gray-700">Promise Date (if balance pending)</label>
                            <input type="date" name="promise_date" id="promise_date" class="form-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('sales.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Complete Sale</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function saleForm() {
    return {
        selectedCustomer: '',
        items: [],
        billDiscount: 0,
        payment: {
            cash: 0,
            credit_card: 0,
            check: 0,
            used_gold: 0,
            pure_gold: 0,
            total: 0,
            balance: 0
        },
        totals: {
            making: 0,
            stone: 0,
            other: 0,
            gold: 0,
            gross: 0,
            net: 0
        },

        addItem() {
            this.items.push({
                item_id: '',
                weight: 0,
                waste_percentage: 0,
                making_per_gram: 0,
                stone_price: 0,
                other_charges: 0,
                gold_rate: 180000, // Default gold rate
                discount: 0,
                total_weight: 0,
                making_cost: 0,
                gold_price: 0,
                net_price: 0
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
            this.calculateTotals();
        },

        updateItemDetails(index) {
            const select = document.querySelector(`select[name="items[${index}][item_id]"]`);
            const option = select.selectedOptions[0];
            if (option) {
                this.items[index].weight = parseFloat(option.dataset.weight) || 0;
                this.calculateItemTotal(index);
            }
        },

        calculateItemTotal(index) {
            const item = this.items[index];
            const wasteWeight = (item.weight * item.waste_percentage) / 100;
            item.total_weight = item.weight + wasteWeight;
            item.making_cost = item.total_weight * item.making_per_gram;
            item.gold_price = item.total_weight * item.gold_rate;
            const grossPrice = item.making_cost + item.stone_price + item.other_charges + item.gold_price;
            item.net_price = grossPrice - item.discount;
            
            this.calculateTotals();
        },

        calculateTotals() {
            this.totals.making = this.items.reduce((sum, item) => sum + item.making_cost, 0);
            this.totals.stone = this.items.reduce((sum, item) => sum + parseFloat(item.stone_price || 0), 0);
            this.totals.other = this.items.reduce((sum, item) => sum + parseFloat(item.other_charges || 0), 0);
            this.totals.gold = this.items.reduce((sum, item) => sum + item.gold_price, 0);
            this.totals.gross = this.totals.making + this.totals.stone + this.totals.other + this.totals.gold;
            this.totals.net = this.totals.gross - this.billDiscount;
            
            this.calculateBalance();
        },

        calculateBalance() {
            this.payment.total = parseFloat(this.payment.cash || 0) + 
                               parseFloat(this.payment.credit_card || 0) + 
                               parseFloat(this.payment.check || 0) + 
                               parseFloat(this.payment.used_gold || 0) + 
                               parseFloat(this.payment.pure_gold || 0);
            this.payment.balance = this.totals.net - this.payment.total;
        },

        init() {
            this.addItem(); // Start with one item
        }
    }
}
</script>
@endpush
@endsection