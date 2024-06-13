<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function auxiliary()
    {
        return $this->belongsTo(Auxiliary::class);
    }

    public function preOrderProducts()
    {
        return $this->hasMany(PreOrderProduct::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItems::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

}
