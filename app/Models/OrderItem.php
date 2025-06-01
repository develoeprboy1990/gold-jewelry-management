<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'item_type', 'description', 'quantity', 'estimated_weight',
        'karat', 'estimated_making_cost', 'estimated_stone_cost', 'estimated_total',
        'specifications', 'status', 'final_item_id'
    ];

    protected $casts = [
        'estimated_weight' => 'decimal:3',
        'estimated_making_cost' => 'decimal:2',
        'estimated_stone_cost' => 'decimal:2',
        'estimated_total' => 'decimal:2',
        'specifications' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function finalItem()
    {
        return $this->belongsTo(Item::class, 'final_item_id');
    }
}
