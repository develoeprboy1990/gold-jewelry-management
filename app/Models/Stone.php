<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stone extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'type', 'name', 'weight', 'quantity', 'rate', 'price',
        'color', 'cut', 'clarity'
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'rate' => 'decimal:2',
        'price' => 'decimal:2'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}