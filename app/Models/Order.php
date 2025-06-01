<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no', 'customer_id', 'user_id', 'order_date', 'delivery_date', 
        'promised_date', 'status', 'order_type', 'estimated_total', 
        'advance_payment', 'final_amount', 'special_instructions', 
        'customer_requirements'
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'promised_date' => 'date',
        'estimated_total' => 'decimal:2',
        'advance_payment' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'customer_requirements' => 'array'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_no)) {
                $order->order_no = 'ORD-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
