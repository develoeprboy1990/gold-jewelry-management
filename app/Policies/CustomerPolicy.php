<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    public function viewAny(User $user)
    {
        return  $user->hasPermissionTo('view customers'); // or custom logic
    }

    public function view(User $user, Customer $customer)
    {
        return true; // $user->id === $customer->user_id || $user->hasPermissionTo('view customers');
    }

    public function create(User $user)
    {
        return true; // $user->hasPermissionTo('create customers');
    }

    public function update(User $user, Customer $customer)
    {
        return true; // $user->id === $customer->user_id || $user->hasPermissionTo('update customers');
    }

    public function delete(User $user, Customer $customer)
    {
        return true; // $user->id === $customer->user_id || $user->hasPermissionTo('delete customers');
    }

    public function restore(User $user, Customer $customer)
    {
        return false; // Adjust if soft deletes used
    }

    public function forceDelete(User $user, Customer $customer)
    {
        return false;
    }
}
