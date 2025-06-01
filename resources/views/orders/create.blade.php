{{-- Enhanced Order Creation Form --}}
@extends('layouts.app')

@section('title', 'New Order')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Create New Order/Service</h1>
        <p class="mt-2 text-gray-600">Handle custom orders, repairs, and adjustments in one order</p>
    </div>

    <form method="POST" action="{{ route('orders.store') }}" x-data="mixedOrderForm()" class="space-y-6">
        @csrf
        
        <!-- Order Header -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Order Information</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="order_date" class="block text-sm font-medium text-gray-700">Order Date *</label>
                        <input type="date" name="order_date" id="order_date" 
                               value="{{ old('order_date', date('Y-m-d')) }}" 
                               class="form-input" required>
                    </div>

                    <div>
                        <label for="promised_date" class="block text-sm font-medium text-gray-700">Promised Delivery Date</label>
                        <input type="date" name="promised_date" id="promised_date" 
                               value="{{ old('promised_date') }}" 
                               class="form-input">
                    </div>

                    <div>
                        <label for="order_type" class="block text-sm font-medium text-gray-700">Primary Order Type *</label>
                        <select name="order_type" id="order_type" class="form-select" required>
                            <option value="custom_order">Mixed/Custom Order</option>
                            <option value="repair">Repair Only</option>
                            <option value="size_adjustment">Size Adjustment Only</option>
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer *</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }} - {{ $customer->contact_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services/Items Section -->
        <div class="card">
            <div class="card-header">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Services & Items</h2>
                        <p class="text-sm text-gray-600">Add all services - repairs, adjustments, and new orders</p>
                    </div>
                    <div class="flex space-x-2">
                        <button type="button" @click="addItem('repair')" class="btn-secondary text-sm">
                            <i class="fas fa-tools mr-1"></i>Add Repair
                        </button>
                        <button type="button" @click="addItem('size_adjustment')" class="btn-secondary text-sm">
                            <i class="fas fa-expand-arrows-alt mr-1"></i>Add Resize
                        </button>
                        <button type="button" @click="addItem('custom_order')" class="btn-primary text-sm">
                            <i class="fas fa-plus mr-1"></i>Add New Item
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-6">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="border rounded-lg p-4" 
                             :class="item.service_type === 'repair' ? 'border-red-200 bg-red-50' : 
                                     item.service_type === 'size_adjustment' ? 'border-blue-200 bg-blue-50' : 
                                     'border-green-200 bg-green-50'">
                            
                            <!-- Service Type Header -->
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                          :class="item.service_type === 'repair' ? 'bg-red-100 text-red-800' : 
                                                 item.service_type === 'size_adjustment' ? 'bg-blue-100 text-blue-800' : 
                                                 'bg-green-100 text-green-800'">
                                        <i class="fas" 
                                           :class="item.service_type === 'repair' ? 'fa-tools' : 
                                                  item.service_type === 'size_adjustment' ? 'fa-expand-arrows-alt' : 
                                                  'fa-plus'"></i>
                                        <span x-text="item.service_type === 'repair' ? 'REPAIR' : 
                                                     item.service_type === 'size_adjustment' ? 'RESIZE' : 
                                                     'NEW ORDER'"></span>
                                    </span>
                                </div>
                                <button type="button" @click="removeItem(index)" class="btn-danger text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Hidden service type input -->
                            <input type="hidden" :name="`items[${index}][service_type]`" x-model="item.service_type">
                            <input type="hidden" :name="`items[${index}][item_type]`" x-model="item.service_type">

                            <!-- Common Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Description *
                                        <span class="text-xs text-gray-500">
                                            (<span x-text="item.service_type === 'repair' ? 'What needs to be repaired?' : 
                                                          item.service_type === 'size_adjustment' ? 'What size changes needed?' : 
                                                          'Describe the new item'"></span>)
                                        </span>
                                    </label>
                                    <textarea :name="`items[${index}][description]`" 
                                              x-model="item.description" 
                                              rows="2" class="form-input" required
                                              :placeholder="item.service_type === 'repair' ? 'e.g., Fix broken chain links on gold necklace' : 
                                                           item.service_type === 'size_adjustment' ? 'e.g., Resize ring from size 16 to size 18' : 
                                                           'e.g., 22K gold bangles with traditional design'"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                    <input type="number" :name="`items[${index}][quantity]`" 
                                           x-model="item.quantity" min="1" 
                                           class="form-input" required>
                                </div>

                                <div x-show="item.service_type === 'custom_order'">
                                    <label class="block text-sm font-medium text-gray-700">Estimated Weight (g)</label>
                                    <input type="number" :name="`items[${index}][estimated_weight]`" 
                                           x-model="item.estimated_weight" step="0.001" min="0" 
                                           class="form-input">
                                </div>
                            </div>

                            <!-- Service-Specific Fields -->
                            <div x-show="item.service_type === 'custom_order'" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Karat</label>
                                    <select :name="`items[${index}][karat]`" x-model="item.karat" class="form-select">
                                        <option value="">Select</option>
                                        <option value="24k">24K</option>
                                        <option value="22k">22K</option>
                                        <option value="21k">21K</option>
                                        <option value="18k">18K</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Making Cost</label>
                                    <input type="number" :name="`items[${index}][estimated_making_cost]`" 
                                           x-model="item.estimated_making_cost" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stone Cost</label>
                                    <input type="number" :name="`items[${index}][estimated_stone_cost]`" 
                                           x-model="item.estimated_stone_cost" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gold Cost</label>
                                    <input type="number" x-model="item.gold_cost" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)">
                                </div>
                            </div>

                            <!-- Service Cost (for repairs and adjustments) -->
                            <div x-show="item.service_type !== 'custom_order'" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Service Cost *</label>
                                    <input type="number" x-model="item.service_cost" step="0.01" min="0" 
                                           class="form-input" required @input="calculateItemTotal(index)"
                                           placeholder="Enter service charges">
                                </div>

                                <div x-show="item.service_type === 'size_adjustment'">
                                    <label class="block text-sm font-medium text-gray-700">Additional Gold Cost</label>
                                    <input type="number" x-model="item.gold_cost" step="0.01" min="0" 
                                           class="form-input" @input="calculateItemTotal(index)"
                                           placeholder="If gold needs to be added">
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">Item Total:</span>
                                    <div class="flex items-center space-x-4">
                                        <input type="number" :name="`items[${index}][estimated_total]`" 
                                               x-model="item.estimated_total" step="0.01" min="0" 
                                               class="form-input w-32 font-semibold" required>
                                        <span class="text-lg font-bold text-blue-600">
                                            PKR <span x-text="item.estimated_total || 0"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Special Instructions -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Special Instructions</label>
                                <textarea :name="`items[${index}][specifications][notes]`" 
                                          x-model="item.specifications_notes" 
                                          rows="2" class="form-input" 
                                          :placeholder="item.service_type === 'repair' ? 'e.g., Keep original design, customer sentimental about it' : 
                                                       item.service_type === 'size_adjustment' ? 'e.g., Customer prefers adding gold rather than stretching' : 
                                                       'e.g., Size preferences, design details, finish type'"></textarea>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                        <p>No items added yet. Use the buttons above to add services.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Service Breakdown -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900">Service Breakdown</h3>
                        
                        <div x-show="getServiceTotal('repair') > 0" class="flex justify-between">
                            <span class="text-red-700">Repair Services:</span>
                            <span class="font-semibold">PKR <span x-text="getServiceTotal('repair').toFixed(2)"></span></span>
                        </div>
                        
                        <div x-show="getServiceTotal('size_adjustment') > 0" class="flex justify-between">
                            <span class="text-blue-700">Size Adjustments:</span>
                            <span class="font-semibold">PKR <span x-text="getServiceTotal('size_adjustment').toFixed(2)"></span></span>
                        </div>
                        
                        <div x-show="getServiceTotal('custom_order') > 0" class="flex justify-between">
                            <span class="text-green-700">New Items:</span>
                            <span class="font-semibold">PKR <span x-text="getServiceTotal('custom_order').toFixed(2)"></span></span>
                        </div>

                        <div class="flex justify-between text-lg border-t pt-2">
                            <span class="font-bold">Total Estimate:</span>
                            <span class="font-bold text-blue-600">PKR <span x-text="orderTotal.toFixed(2)"></span></span>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900">Payment</h3>
                        
                        <div>
                            <label for="advance_payment" class="block text-sm font-medium text-gray-700">Advance Payment *</label>
                            <input type="number" name="advance_payment" id="advance_payment" 
                                   x-model="advancePayment" step="0.01" min="0" 
                                   class="form-input" required @input="calculateBalance()">
                        </div>

                        <div class="flex justify-between border-t pt-2">
                            <span class="font-semibold">Remaining Balance:</span>
                            <span class="font-semibold" :class="remainingBalance >= 0 ? 'text-red-600' : 'text-green-600'">
                                PKR <span x-text="Math.abs(remainingBalance).toFixed(2)"></span>
                            </span>
                        </div>

                        <div>
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700">General Instructions</label>
                            <textarea name="special_instructions" id="special_instructions" 
                                      rows="3" class="form-input" 
                                      placeholder="Overall order instructions, delivery preferences, etc...">{{ old('special_instructions') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('orders.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Order</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function mixedOrderForm() {
    return {
        items: [],
        orderTotal: 0,
        advancePayment: 0,
        remainingBalance: 0,

        addItem(serviceType) {
            this.items.push({
                service_type: serviceType,
                description: '',
                quantity: 1,
                estimated_weight: 0,
                karat: '',
                estimated_making_cost: 0,
                estimated_stone_cost: 0,
                gold_cost: 0,
                service_cost: 0,
                estimated_total: 0,
                specifications_notes: ''
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
            this.calculateOrderTotal();
        },

        calculateItemTotal(index) {
            const item = this.items[index];
            
            if (item.service_type === 'custom_order') {
                const makingCost = parseFloat(item.estimated_making_cost || 0);
                const stoneCost = parseFloat(item.estimated_stone_cost || 0);
                const goldCost = parseFloat(item.gold_cost || 0);
                item.estimated_total = (makingCost + stoneCost + goldCost) * item.quantity;
            } else {
                const serviceCost = parseFloat(item.service_cost || 0);
                const goldCost = parseFloat(item.gold_cost || 0);
                item.estimated_total = (serviceCost + goldCost) * item.quantity;
            }
            
            this.calculateOrderTotal();
        },

        calculateOrderTotal() {
            this.orderTotal = this.items.reduce((sum, item) => sum + parseFloat(item.estimated_total || 0), 0);
            this.calculateBalance();
        },

        calculateBalance() {
            this.remainingBalance = this.orderTotal - parseFloat(this.advancePayment || 0);
        },

        getServiceTotal(serviceType) {
            return this.items
                .filter(item => item.service_type === serviceType)
                .reduce((sum, item) => sum + parseFloat(item.estimated_total || 0), 0);
        }
    }
}
</script>
@endpush
@endsection