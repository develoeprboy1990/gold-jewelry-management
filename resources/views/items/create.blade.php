{{-- resources/views/items/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Add Item')
@section('page-title', 'Add New Item')
@section('page-description', 'Add jewelry item to inventory')

@section('page-actions')
<a href="{{ route('items.index') }}" class="btn-secondary">
    <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tag_number" class="block text-sm font-medium text-gray-700">Tag Number *</label>
                        <input type="text" name="tag_number" id="tag_number" value="{{ old('tag_number') }}" 
                               class="form-input @error('tag_number') border-red-500 @enderror" required>
                        @error('tag_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bar_code" class="block text-sm font-medium text-gray-700">Bar Code</label>
                        <input type="text" name="bar_code" id="bar_code" value="{{ old('bar_code') }}" 
                               class="form-input @error('bar_code') border-red-500 @enderror">
                        @error('bar_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') border-red-500 @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ ucfirst($category->type) }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="group_item" class="block text-sm font-medium text-gray-700">Group Item *</label>
                        <input type="text" name="group_item" id="group_item" value="{{ old('group_item') }}" 
                               placeholder="e.g., Ring, Necklace, Earrings"
                               class="form-input @error('group_item') border-red-500 @enderror" required>
                        @error('group_item')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sub_group_item" class="block text-sm font-medium text-gray-700">Sub Group Item</label>
                        <input type="text" name="sub_group_item" id="sub_group_item" value="{{ old('sub_group_item') }}" 
                               placeholder="e.g., Wedding Ring, Chain"
                               class="form-input @error('sub_group_item') border-red-500 @enderror">
                        @error('sub_group_item')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sub_item" class="block text-sm font-medium text-gray-700">Sub Item</label>
                        <input type="text" name="sub_item" id="sub_item" value="{{ old('sub_item') }}" 
                               class="form-input @error('sub_item') border-red-500 @enderror">
                        @error('sub_item')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="form-input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Physical Properties -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Physical Properties</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (grams) *</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight') }}" 
                               step="0.001" min="0" 
                               class="form-input @error('weight') border-red-500 @enderror" required>
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" 
                               min="1" 
                               class="form-input @error('quantity') border-red-500 @enderror" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pieces" class="block text-sm font-medium text-gray-700">Pieces *</label>
                        <input type="number" name="pieces" id="pieces" value="{{ old('pieces', 1) }}" 
                               min="1" 
                               class="form-input @error('pieces') border-red-500 @enderror" required>
                        @error('pieces')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="karat" class="block text-sm font-medium text-gray-700">Karat</label>
                        <select name="karat" id="karat" class="form-select @error('karat') border-red-500 @enderror">
                            <option value="">Select Karat</option>
                            <option value="24K" {{ old('karat') == '24K' ? 'selected' : '' }}>24K</option>
                            <option value="22K" {{ old('karat') == '22K' ? 'selected' : '' }}>22K</option>
                            <option value="21K" {{ old('karat') == '21K' ? 'selected' : '' }}>21K</option>
                            <option value="18K" {{ old('karat') == '18K' ? 'selected' : '' }}>18K</option>
                            <option value="14K" {{ old('karat') == '14K' ? 'selected' : '' }}>14K</option>
                            <option value="10K" {{ old('karat') == '10K' ? 'selected' : '' }}>10K</option>
                        </select>
                        @error('karat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pure_weight" class="block text-sm font-medium text-gray-700">Pure Weight (grams)</label>
                        <input type="number" name="pure_weight" id="pure_weight" value="{{ old('pure_weight') }}" 
                               step="0.001" min="0" 
                               class="form-input @error('pure_weight') border-red-500 @enderror">
                        @error('pure_weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="design_no" class="block text-sm font-medium text-gray-700">Design #</label>
                        <input type="text" name="design_no" id="design_no" value="{{ old('design_no') }}" 
                               class="form-input @error('design_no') border-red-500 @enderror">
                        @error('design_no')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Manufacturing Details -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Manufacturing Details</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="worker_name" class="block text-sm font-medium text-gray-700">Worker Name</label>
                        <input type="text" name="worker_name" id="worker_name" value="{{ old('worker_name') }}" 
                               class="form-input @error('worker_name') border-red-500 @enderror">
                        @error('worker_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="worker_id" class="block text-sm font-medium text-gray-700">Worker ID</label>
                        <input type="text" name="worker_id" id="worker_id" value="{{ old('worker_id') }}" 
                               class="form-input @error('worker_id') border-red-500 @enderror">
                        @error('worker_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Pricing</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="making_cost" class="block text-sm font-medium text-gray-700">Making Cost *</label>
                        <input type="number" name="making_cost" id="making_cost" value="{{ old('making_cost') }}" 
                               step="0.01" min="0" 
                               class="form-input @error('making_cost') border-red-500 @enderror" required>
                        @error('making_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stone_price" class="block text-sm font-medium text-gray-700">Stone Price *</label>
                        <input type="number" name="stone_price" id="stone_price" value="{{ old('stone_price', 0) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('stone_price') border-red-500 @enderror" required>
                        @error('stone_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price *</label>
                        <input type="number" name="total_price" id="total_price" value="{{ old('total_price') }}" 
                               step="0.01" min="0" 
                               class="form-input @error('total_price') border-red-500 @enderror" required>
                        @error('total_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Images</h2>
            </div>
            <div class="card-body">
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700">Item Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" 
                           class="form-input @error('images.*') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">You can select multiple images. Supported formats: JPG, PNG, GIF (Max: 2MB each)</p>
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Stones Information (Optional) -->
        <div class="card" x-data="stonesForm()">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Stones Information (Optional)</h2>
                    <button type="button" @click="addStone()" class="btn-primary text-sm">
                        <i class="fas fa-plus mr-2"></i>Add Stone
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <template x-for="(stone, index) in stones" :key="index">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-medium text-gray-900">Stone <span x-text="index + 1"></span></h4>
                                <button type="button" @click="removeStone(index)" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Type</label>
                                    <select :name="`stones[${index}][type]`" x-model="stone.type" class="form-select">
                                        <option value="">Select Type</option>
                                        <option value="Diamond">Diamond</option>
                                        <option value="Ruby">Ruby</option>
                                        <option value="Emerald">Emerald</option>
                                        <option value="Sapphire">Sapphire</option>
                                        <option value="Pearl">Pearl</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" :name="`stones[${index}][name]`" x-model="stone.name" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Weight (carat)</label>
                                    <input type="number" :name="`stones[${index}][weight]`" x-model="stone.weight" 
                                           step="0.001" min="0" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" :name="`stones[${index}][quantity]`" x-model="stone.quantity" 
                                           min="1" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rate</label>
                                    <input type="number" :name="`stones[${index}][rate]`" x-model="stone.rate" 
                                           step="0.01" min="0" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" :name="`stones[${index}][price]`" x-model="stone.price" 
                                           step="0.01" min="0" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Color</label>
                                    <input type="text" :name="`stones[${index}][color]`" x-model="stone.color" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cut</label>
                                    <input type="text" :name="`stones[${index}][cut]`" x-model="stone.cut" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Clarity</label>
                                    <input type="text" :name="`stones[${index}][clarity]`" x-model="stone.clarity" class="form-input">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('items.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Save Item</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function stonesForm() {
        return {
            stones: [],
            
            addStone() {
                this.stones.push({
                    type: '',
                    name: '',
                    weight: 0,
                    quantity: 1,
                    rate: 0,
                    price: 0,
                    color: '',
                    cut: '',
                    clarity: ''
                });
            },
            
            removeStone(index) {
                this.stones.splice(index, 1);
            }
        }
    }

    // Auto-calculate total price
    document.addEventListener('DOMContentLoaded', function() {
        const makingCost = document.getElementById('making_cost');
        const stonePrice = document.getElementById('stone_price');
        const totalPrice = document.getElementById('total_price');
        
        function calculateTotal() {
            const making = parseFloat(makingCost.value) || 0;
            const stone = parseFloat(stonePrice.value) || 0;
            totalPrice.value = (making + stone).toFixed(2);
        }
        
        makingCost.addEventListener('input', calculateTotal);
        stonePrice.addEventListener('input', calculateTotal);
    });
</script>
@endpush
@endsection