{{-- resources/views/items/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Item')
@section('page-title', 'Edit Item: ' . $item->tag_number)
@section('page-description', 'Update item information')

@section('page-actions')
<div class="flex space-x-3">
    <a href="{{ route('items.show', $item) }}" class="btn-secondary">
        <i class="fas fa-eye mr-2"></i>View Item
    </a>
    <a href="{{ route('items.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tag_number" class="block text-sm font-medium text-gray-700">Tag Number *</label>
                        <input type="text" name="tag_number" id="tag_number" value="{{ old('tag_number', $item->tag_number) }}" 
                               class="form-input @error('tag_number') border-red-500 @enderror" required>
                        @error('tag_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bar_code" class="block text-sm font-medium text-gray-700">Bar Code</label>
                        <input type="text" name="bar_code" id="bar_code" value="{{ old('bar_code', $item->bar_code) }}" 
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
                                <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ ucfirst($category->type) }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                        <select name="status" id="status" class="form-select @error('status') border-red-500 @enderror" required>
                            <option value="in_stock" {{ old('status', $item->status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="sold" {{ old('status', $item->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="on_order" {{ old('status', $item->status) == 'on_order' ? 'selected' : '' }}>On Order</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="group_item" class="block text-sm font-medium text-gray-700">Group Item *</label>
                        <input type="text" name="group_item" id="group_item" value="{{ old('group_item', $item->group_item) }}" 
                               class="form-input @error('group_item') border-red-500 @enderror" required>
                        @error('group_item')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sub_group_item" class="block text-sm font-medium text-gray-700">Sub Group Item</label>
                        <input type="text" name="sub_group_item" id="sub_group_item" value="{{ old('sub_group_item', $item->sub_group_item) }}" 
                               class="form-input @error('sub_group_item') border-red-500 @enderror">
                        @error('sub_group_item')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="sub_item" class="block text-sm font-medium text-gray-700">Sub Item</label>
                        <input type="text" name="sub_item" id="sub_item" value="{{ old('sub_item', $item->sub_item) }}" 
                               class="form-input @error('sub_item') border-red-500 @enderror">
                        @error('sub_item')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="form-input @error('description') border-red-500 @enderror">{{ old('description', $item->description) }}</textarea>
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
                        <input type="number" name="weight" id="weight" value="{{ old('weight', $item->weight) }}" 
                               step="0.001" min="0" 
                               class="form-input @error('weight') border-red-500 @enderror" required>
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity *</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $item->quantity) }}" 
                               min="1" 
                               class="form-input @error('quantity') border-red-500 @enderror" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pieces" class="block text-sm font-medium text-gray-700">Pieces *</label>
                        <input type="number" name="pieces" id="pieces" value="{{ old('pieces', $item->pieces) }}" 
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
                            <option value="24K" {{ old('karat', $item->karat) == '24K' ? 'selected' : '' }}>24K</option>
                            <option value="22K" {{ old('karat', $item->karat) == '22K' ? 'selected' : '' }}>22K</option>
                            <option value="21K" {{ old('karat', $item->karat) == '21K' ? 'selected' : '' }}>21K</option>
                            <option value="18K" {{ old('karat', $item->karat) == '18K' ? 'selected' : '' }}>18K</option>
                            <option value="14K" {{ old('karat', $item->karat) == '14K' ? 'selected' : '' }}>14K</option>
                            <option value="10K" {{ old('karat', $item->karat) == '10K' ? 'selected' : '' }}>10K</option>
                        </select>
                        @error('karat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pure_weight" class="block text-sm font-medium text-gray-700">Pure Weight (grams)</label>
                        <input type="number" name="pure_weight" id="pure_weight" value="{{ old('pure_weight', $item->pure_weight) }}" 
                               step="0.001" min="0" 
                               class="form-input @error('pure_weight') border-red-500 @enderror">
                        @error('pure_weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="design_no" class="block text-sm font-medium text-gray-700">Design #</label>
                        <input type="text" name="design_no" id="design_no" value="{{ old('design_no', $item->design_no) }}" 
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
                        <input type="text" name="worker_name" id="worker_name" value="{{ old('worker_name', $item->worker_name) }}" 
                               class="form-input @error('worker_name') border-red-500 @enderror">
                        @error('worker_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="worker_id" class="block text-sm font-medium text-gray-700">Worker ID</label>
                        <input type="text" name="worker_id" id="worker_id" value="{{ old('worker_id', $item->worker_id) }}" 
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
                        <input type="number" name="making_cost" id="making_cost" value="{{ old('making_cost', $item->making_cost) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('making_cost') border-red-500 @enderror" required>
                        @error('making_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stone_price" class="block text-sm font-medium text-gray-700">Stone Price *</label>
                        <input type="number" name="stone_price" id="stone_price" value="{{ old('stone_price', $item->stone_price) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('stone_price') border-red-500 @enderror" required>
                        @error('stone_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price *</label>
                        <input type="number" name="total_price" id="total_price" value="{{ old('total_price', $item->total_price) }}" 
                               step="0.01" min="0" 
                               class="form-input @error('total_price') border-red-500 @enderror" required>
                        @error('total_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Images -->
        @if($item->images && count($item->images) > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Current Images</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($item->images as $image)
                    <div class="relative">
                        <img src="{{ Storage::url($image) }}" alt="Item image" class="w-full h-32 object-cover rounded-lg">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Add New Images -->
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-semibold text-gray-900">Add New Images</h2>
            </div>
            <div class="card-body">
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700">Additional Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" 
                           class="form-input @error('images.*') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">You can select multiple images. Supported formats: JPG, PNG, GIF (Max: 2MB each)</p>
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('items.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Item</button>
        </div>
    </form>
</div>
@endsection