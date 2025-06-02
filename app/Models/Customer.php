<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','customer_group_id',
        'name', 'cnic', 'contact_no', 'email', 'birth_date', 'anniversary_date',
        'company', 'house_no', 'street_no', 'block_no', 'colony', 'city', 
        'country', 'address', 'cash_balance', 'payment_preference','points','is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'anniversary_date' => 'date',
        'cash_balance' => 'decimal:2'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function goldPurchases()
    {
        return $this->hasMany(GoldPurchase::class);
    }
}
