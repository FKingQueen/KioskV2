<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrderProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'pre_order_id',
        'product_id',
        'quantity',
    ];

    public function preOrder()
    {
        return $this->belongsTo(PreOrder::class);
    }

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
