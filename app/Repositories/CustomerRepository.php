<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    public function all()
    {
        return Customer::latest()->paginate(15);
    }

    public function find($id)
    {
        return Customer::findOrFail($id);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update(Customer $customer, array $data)
    {
        return $customer->update($data);
    }

    public function delete(Customer $customer)
    {
        return $customer->delete();
    }


    public function search(array $data)
    {
        $query = $data['q'];
        $customers = Customer::where('name', 'LIKE', "%{$query}%")
            ->orWhere('cnic', 'LIKE', "%{$query}%")
            ->orWhere('contact_no', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'cnic', 'contact_no']);
        return $customers;
    }
}
