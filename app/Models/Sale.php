<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_no', 'bill_book_no', 'sale_date', 'customer_id', 'user_id',
        'total_making', 'total_stone_charges', 'total_other_charges',
        'total_gold_price', 'total_item_discount', 'bill_discount', 'net_bill',
        'cash_received', 'credit_card_amount', 'check_amount', 'used_gold_amount',
        'pure_gold_amount', 'total_received', 'cash_balance', 'promise_date'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'promise_date' => 'date',
        'total_making' => 'decimal:2',
        'total_stone_charges' => 'decimal:2',
        'total_other_charges' => 'decimal:2',
        'total_gold_price' => 'decimal:2',
        'total_item_discount' => 'decimal:2',
        'bill_discount' => 'decimal:2',
        'net_bill' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'credit_card_amount' => 'decimal:2',
        'check_amount' => 'decimal:2',
        'used_gold_amount' => 'decimal:2',
        'pure_gold_amount' => 'decimal:2',
        'total_received' => 'decimal:2',
        'cash_balance' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($sale) {
            if (empty($sale->sale_no)) {
                $sale->sale_no = 'SAL-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
