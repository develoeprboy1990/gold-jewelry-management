<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_number', 'bar_code', 'category_id', 'group_item', 'sub_group_item',
        'sub_item', 'weight', 'quantity', 'pieces', 'karat', 'pure_weight',
        'design_no', 'worker_name', 'worker_id', 'description', 'making_cost',
        'stone_price', 'total_price', 'images', 'status', 'date_created'
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'pure_weight' => 'decimal:3',
        'making_cost' => 'decimal:2',
        'stone_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'images' => 'array',
        'date_created' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function stones()
    {
        return $this->hasMany(Stone::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
