<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id', 'item_id', 'weight', 'waste_percentage', 'waste_weight',
        'total_weight', 'making_per_gram', 'total_making', 'stone_price',
        'other_charges', 'gold_rate', 'gold_price', 'gross_weight',
        'total_price', 'discount', 'net_price'
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'waste_percentage' => 'decimal:2',
        'waste_weight' => 'decimal:3',
        'total_weight' => 'decimal:3',
        'making_per_gram' => 'decimal:2',
        'total_making' => 'decimal:2',
        'stone_price' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'gold_rate' => 'decimal:2',
        'gold_price' => 'decimal:2',
        'gross_weight' => 'decimal:3',
        'total_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_price' => 'decimal:2'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
