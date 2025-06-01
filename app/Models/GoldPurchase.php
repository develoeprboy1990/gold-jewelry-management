<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no', 'weight', 'karat', 'pure_weight', 'karrat_24', 'rate',
        'amount', 'type', 'description', 'date', 'customer_id', 'customer_name',
        'contact_no', 'address', 'cash_payment'
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'pure_weight' => 'decimal:3',
        'karrat_24' => 'decimal:3',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'cash_payment' => 'decimal:2',
        'date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($purchase) {
            if (empty($purchase->voucher_no)) {
                $purchase->voucher_no = 'GP-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
