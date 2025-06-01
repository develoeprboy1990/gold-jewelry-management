<?php
// app/Http/Controllers/GoldPurchaseController.php
namespace App\Http\Controllers;

use App\Models\GoldPurchase;
use App\Models\Customer;
use Illuminate\Http\Request;

class GoldPurchaseController extends Controller
{
    public function index()
    {
        $goldPurchases = GoldPurchase::with('customer')
            ->latest()
            ->paginate(15);
        
        return view('gold-purchases.index', compact('goldPurchases'));
    }

    public function create()
    {
        $customers = Customer::select('id', 'name', 'cnic', 'contact_no')->get();
        return view('gold-purchases.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0',
            'karat' => 'required|string|max:10',
            'pure_weight' => 'required|numeric|min:0',
            'karrat_24' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:pure_gold,used_gold',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'contact_no' => 'required_without:customer_id|string|max:20',
            'address' => 'nullable|string',
            'cash_payment' => 'required|numeric|min:0'
        ]);

        GoldPurchase::create($validated);

        return redirect()->route('gold-purchases.index')
            ->with('success', 'Gold purchase recorded successfully.');
    }

    public function show(GoldPurchase $goldPurchase)
    {
        $goldPurchase->load('customer');
        return view('gold-purchases.show', compact('goldPurchase'));
    }

    public function edit(GoldPurchase $goldPurchase)
    {
        $customers = Customer::select('id', 'name', 'cnic', 'contact_no')->get();
        return view('gold-purchases.edit', compact('goldPurchase', 'customers'));
    }

    public function update(Request $request, GoldPurchase $goldPurchase)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0',
            'karat' => 'required|string|max:10',
            'pure_weight' => 'required|numeric|min:0',
            'karrat_24' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:pure_gold,used_gold',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'contact_no' => 'required_without:customer_id|string|max:20',
            'address' => 'nullable|string',
            'cash_payment' => 'required|numeric|min:0'
        ]);

        $goldPurchase->update($validated);

        return redirect()->route('gold-purchases.index')
            ->with('success', 'Gold purchase updated successfully.');
    }

    public function destroy(GoldPurchase $goldPurchase)
    {
        $goldPurchase->delete();
        return redirect()->route('gold-purchases.index')
            ->with('success', 'Gold purchase deleted successfully.');
    }
}