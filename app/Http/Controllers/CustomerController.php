<?php
// app/Http/Controllers/CustomerController.php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(15);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|unique:customers,cnic|max:20',
            'contact_no' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'company' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:50',
            'street_no' => 'nullable|string|max:50',
            'block_no' => 'nullable|string|max:50',
            'colony' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'address' => 'nullable|string',
            'cash_balance' => 'nullable|numeric|min:0',
            'payment_preference' => 'required|in:cash,credit_card,check,pure_gold,used_gold'
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['sales', 'goldPurchases']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|unique:customers,cnic,' . $customer->id . '|max:20',
            'contact_no' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'company' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:50',
            'street_no' => 'nullable|string|max:50',
            'block_no' => 'nullable|string|max:50',
            'colony' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'address' => 'nullable|string',
            'cash_balance' => 'nullable|numeric|min:0',
            'payment_preference' => 'required|in:cash,credit_card,check,pure_gold,used_gold'
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $customers = Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('cnic', 'LIKE', "%{$query}%")
            ->orWhere('contact_no', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'cnic', 'contact_no']);

        return response()->json($customers);
    }
}