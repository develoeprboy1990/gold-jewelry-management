<?php
// app/Http/Controllers/ItemController.php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Stone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'stones'])
            ->latest()
            ->paginate(15);
        
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = ItemCategory::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag_number' => 'required|string|unique:items,tag_number|max:50',
            'bar_code' => 'nullable|string|max:100',
            'category_id' => 'required|exists:item_categories,id',
            'group_item' => 'required|string|max:100',
            'sub_group_item' => 'nullable|string|max:100',
            'sub_item' => 'nullable|string|max:100',
            'weight' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'pieces' => 'required|integer|min:1',
            'karat' => 'nullable|string|max:10',
            'pure_weight' => 'nullable|numeric|min:0',
            'design_no' => 'nullable|string|max:50',
            'worker_name' => 'nullable|string|max:100',
            'worker_id' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'making_cost' => 'required|numeric|min:0',
            'stone_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stones' => 'nullable|array',
            'stones.*.type' => 'required_with:stones|string',
            'stones.*.name' => 'required_with:stones|string',
            'stones.*.weight' => 'required_with:stones|numeric|min:0',
            'stones.*.quantity' => 'required_with:stones|integer|min:1',
            'stones.*.rate' => 'required_with:stones|numeric|min:0',
            'stones.*.price' => 'required_with:stones|numeric|min:0',
            'stones.*.color' => 'nullable|string',
            'stones.*.cut' => 'nullable|string',
            'stones.*.clarity' => 'nullable|string',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('item-images', 'public');
                $imagePaths[] = $path;
            }
        }
        $validated['images'] = $imagePaths;

        $item = Item::create($validated);

        // Create stones if provided
        if ($request->has('stones')) {
            foreach ($request->stones as $stoneData) {
                if (!empty($stoneData['type']) && !empty($stoneData['name'])) {
                    $item->stones()->create($stoneData);
                }
            }
        }

        return redirect()->route('items.index')
            ->with('success', 'Item created successfully.');
    }

    public function show(Item $item)
    {
        $item->load(['category', 'stones']);
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = ItemCategory::all();
        $item->load('stones');
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'tag_number' => 'required|string|unique:items,tag_number,' . $item->id . '|max:50',
            'bar_code' => 'nullable|string|max:100',
            'category_id' => 'required|exists:item_categories,id',
            'group_item' => 'required|string|max:100',
            'sub_group_item' => 'nullable|string|max:100',
            'sub_item' => 'nullable|string|max:100',
            'weight' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'pieces' => 'required|integer|min:1',
            'karat' => 'nullable|string|max:10',
            'pure_weight' => 'nullable|numeric|min:0',
            'design_no' => 'nullable|string|max:50',
            'worker_name' => 'nullable|string|max:100',
            'worker_id' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'making_cost' => 'required|numeric|min:0',
            'stone_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:in_stock,sold,on_order',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle new image uploads
        $imagePaths = $item->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('item-images', 'public');
                $imagePaths[] = $path;
            }
        }
        $validated['images'] = $imagePaths;

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        // Delete associated images
        if ($item->images) {
            foreach ($item->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $item->delete();
        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $items = Item::with('category')
            ->where('tag_number', 'LIKE', "%{$query}%")
            ->orWhere('group_item', 'LIKE', "%{$query}%")
            ->orWhere('bar_code', 'LIKE', "%{$query}%")
            ->where('status', 'in_stock')
            ->limit(10)
            ->get(['id', 'tag_number', 'group_item', 'weight', 'total_price']);

        return response()->json($items);
    }
}
