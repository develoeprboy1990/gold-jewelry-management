<?php

// app/Http/Controllers/SaleController.php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'user'])
            ->latest()
            ->paginate(15);
        
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::select('id', 'name', 'cnic', 'contact_no')->get();
        $items = Item::where('status', 'in_stock')
            ->select('id', 'tag_number', 'group_item', 'weight', 'total_price')
            ->get();
        
        return view('sales.create', compact('customers', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'bill_book_no' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.weight' => 'required|numeric|min:0',
            'items.*.waste_percentage' => 'required|numeric|min:0|max:100',
            'items.*.making_per_gram' => 'required|numeric|min:0',
            'items.*.stone_price' => 'required|numeric|min:0',
            'items.*.other_charges' => 'required|numeric|min:0',
            'items.*.gold_rate' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric|min:0',
            'bill_discount' => 'required|numeric|min:0',
            'cash_received' => 'required|numeric|min:0',
            'credit_card_amount' => 'nullable|numeric|min:0',
            'check_amount' => 'nullable|numeric|min:0',
            'used_gold_amount' => 'nullable|numeric|min:0',
            'pure_gold_amount' => 'nullable|numeric|min:0',
            'promise_date' => 'nullable|date'
        ]);

        DB::transaction(function () use ($validated, $request) {
            // Calculate totals
            $totalMaking = 0;
            $totalStoneCharges = 0;
            $totalOtherCharges = 0;
            $totalGoldPrice = 0;
            $totalItemDiscount = 0;

            foreach ($validated['items'] as $itemData) {
                $wasteWeight = ($itemData['weight'] * $itemData['waste_percentage']) / 100;
                $totalWeight = $itemData['weight'] + $wasteWeight;
                $makingCost = $totalWeight * $itemData['making_per_gram'];
                $goldPrice = $totalWeight * $itemData['gold_rate'];
                
                $totalMaking += $makingCost;
                $totalStoneCharges += $itemData['stone_price'];
                $totalOtherCharges += $itemData['other_charges'];
                $totalGoldPrice += $goldPrice;
                $totalItemDiscount += $itemData['discount'];
            }

            $grossTotal = $totalMaking + $totalStoneCharges + $totalOtherCharges + $totalGoldPrice;
            $netBill = $grossTotal - $totalItemDiscount - $validated['bill_discount'];
            $totalReceived = $validated['cash_received'] + 
                           ($validated['credit_card_amount'] ?? 0) +
                           ($validated['check_amount'] ?? 0) +
                           ($validated['used_gold_amount'] ?? 0) +
                           ($validated['pure_gold_amount'] ?? 0);
            $cashBalance = $netBill - $totalReceived;

            // Create sale
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'sale_date' => $validated['sale_date'],
                'bill_book_no' => $validated['bill_book_no'],
                'total_making' => $totalMaking,
                'total_stone_charges' => $totalStoneCharges,
                'total_other_charges' => $totalOtherCharges,
                'total_gold_price' => $totalGoldPrice,
                'total_item_discount' => $totalItemDiscount,
                'bill_discount' => $validated['bill_discount'],
                'net_bill' => $netBill,
                'cash_received' => $validated['cash_received'],
                'credit_card_amount' => $validated['credit_card_amount'] ?? 0,
                'check_amount' => $validated['check_amount'] ?? 0,
                'used_gold_amount' => $validated['used_gold_amount'] ?? 0,
                'pure_gold_amount' => $validated['pure_gold_amount'] ?? 0,
                'total_received' => $totalReceived,
                'cash_balance' => $cashBalance,
                'promise_date' => $validated['promise_date']
            ]);

            // Create sale items and update item status
            foreach ($validated['items'] as $itemData) {
                $wasteWeight = ($itemData['weight'] * $itemData['waste_percentage']) / 100;
                $totalWeight = $itemData['weight'] + $wasteWeight;
                $makingCost = $totalWeight * $itemData['making_per_gram'];
                $goldPrice = $totalWeight * $itemData['gold_rate'];
                $grossPrice = $makingCost + $itemData['stone_price'] + $itemData['other_charges'] + $goldPrice;
                $netPrice = $grossPrice - $itemData['discount'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $itemData['item_id'],
                    'weight' => $itemData['weight'],
                    'waste_percentage' => $itemData['waste_percentage'],
                    'waste_weight' => $wasteWeight,
                    'total_weight' => $totalWeight,
                    'making_per_gram' => $itemData['making_per_gram'],
                    'total_making' => $makingCost,
                    'stone_price' => $itemData['stone_price'],
                    'other_charges' => $itemData['other_charges'],
                    'gold_rate' => $itemData['gold_rate'],
                    'gold_price' => $goldPrice,
                    'gross_weight' => $totalWeight,
                    'total_price' => $grossPrice,
                    'discount' => $itemData['discount'],
                    'net_price' => $netPrice
                ]);

                // Update item status to sold
                Item::where('id', $itemData['item_id'])->update(['status' => 'sold']);
            }
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'saleItems.item']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $customers = Customer::select('id', 'name', 'cnic', 'contact_no')->get();
        $sale->load(['saleItems.item']);
        return view('sales.edit', compact('sale', 'customers'));
    }
    
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'bill_book_no' => 'nullable|string|max:50',
            'bill_discount' => 'required|numeric|min:0',
            'cash_received' => 'required|numeric|min:0',
            'credit_card_amount' => 'nullable|numeric|min:0',
            'check_amount' => 'nullable|numeric|min:0',
            'used_gold_amount' => 'nullable|numeric|min:0',
            'pure_gold_amount' => 'nullable|numeric|min:0',
            'promise_date' => 'nullable|date'
        ]);

        DB::transaction(function () use ($validated, $sale) {
            // Calculate new totals
            $grossTotal = $sale->total_making + $sale->total_stone_charges + 
                        $sale->total_other_charges + $sale->total_gold_price;
            
            $netBill = $grossTotal - $sale->total_item_discount - $validated['bill_discount'];
            
            $totalReceived = $validated['cash_received'] + 
                            ($validated['credit_card_amount'] ?? 0) +
                            ($validated['check_amount'] ?? 0) +
                            ($validated['used_gold_amount'] ?? 0) +
                            ($validated['pure_gold_amount'] ?? 0);
            
            $cashBalance = $netBill - $totalReceived;

            // Update sale
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'bill_book_no' => $validated['bill_book_no'],
                'bill_discount' => $validated['bill_discount'],
                'net_bill' => $netBill,
                'cash_received' => $validated['cash_received'],
                'credit_card_amount' => $validated['credit_card_amount'] ?? 0,
                'check_amount' => $validated['check_amount'] ?? 0,
                'used_gold_amount' => $validated['used_gold_amount'] ?? 0,
                'pure_gold_amount' => $validated['pure_gold_amount'] ?? 0,
                'total_received' => $totalReceived,
                'cash_balance' => $cashBalance,
                'promise_date' => $validated['promise_date']
            ]);
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Sale updated successfully.');
    }

    public function generateInvoice(Sale $sale)
    {
        $sale->load(['customer', 'saleItems.item']);
        
        $pdf = PDF::loadView('sales.invoice', compact('sale'));
        return $pdf->stream('invoice-' . $sale->sale_no . '.pdf');
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            // Restore item status to in_stock
            foreach ($sale->saleItems as $saleItem) {
                Item::where('id', $saleItem->item_id)->update(['status' => 'in_stock']);
            }
            
            $sale->delete();
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully.');
    }
}
