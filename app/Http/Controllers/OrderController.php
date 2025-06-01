<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'user'])
            ->latest()
            ->paginate(15);
        
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::select('id', 'name', 'cnic', 'contact_no')->get();
        return view('orders.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'promised_date' => 'nullable|date|after_or_equal:order_date',
            'order_type' => 'required|in:custom_order,repair,size_adjustment',
            'advance_payment' => 'required|numeric|min:0',
            'special_instructions' => 'nullable|string',
            'customer_requirements' => 'nullable|array',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimated_weight' => 'nullable|numeric|min:0',
            'items.*.karat' => 'nullable|string',
            'items.*.estimated_making_cost' => 'nullable|numeric|min:0',
            'items.*.estimated_stone_cost' => 'nullable|numeric|min:0',
            'items.*.estimated_total' => 'required|numeric|min:0',
            'items.*.specifications' => 'nullable|array',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $estimatedTotal = collect($validated['items'])->sum('estimated_total');

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'order_date' => $validated['order_date'],
                'promised_date' => $validated['promised_date'],
                'order_type' => $validated['order_type'],
                'estimated_total' => $estimatedTotal,
                'advance_payment' => $validated['advance_payment'],
                'special_instructions' => $validated['special_instructions'],
                'customer_requirements' => $validated['customer_requirements'] ?? [],
                'status' => 'pending'
            ]);

            foreach ($validated['items'] as $itemData) {
                $order->orderItems()->create($itemData);
            }
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'user', 'orderItems']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $customers = Customer::select('id', 'name', 'cnic', 'contact_no')->get();
        $order->load(['orderItems']);
        return view('orders.edit', compact('order', 'customers'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'promised_date' => 'nullable|date|after_or_equal:order_date',
            'order_type' => 'required|in:custom_order,repair,size_adjustment',
            'advance_payment' => 'required|numeric|min:0',
            'special_instructions' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,ready,delivered,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        if ($order->status === 'delivered') {
            return redirect()->back()->with('error', 'Cannot delete delivered orders.');
        }

        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    public function markReady(Order $order)
    {
        $order->update(['status' => 'ready']);
        
        // You can add notification logic here
        // $this->notifyCustomer($order);
        
        return redirect()->back()->with('success', 'Order marked as ready for delivery.');
    }

    public function convertToSale(Order $order)
    {
        if ($order->status !== 'ready') {
            return redirect()->back()->with('error', 'Order must be ready before converting to sale.');
        }

        // Create a new sale based on the order
        return redirect()->route('sales.create', ['order_id' => $order->id])
            ->with('info', 'Creating sale from order #' . $order->order_no);
    }

    public function generateReceipt(Order $order)
    {
        $order->load(['customer', 'orderItems']);
        
        $pdf = PDF::loadView('orders.receipt', compact('order'));
        return $pdf->stream('order-receipt-' . $order->order_no . '.pdf');
    }
}