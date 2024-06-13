<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auxiliary extends Model
{
    use HasFactory;

    public function cartItems()
    {
        return $this->hasMany(CartItems::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
