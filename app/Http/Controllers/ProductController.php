<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Auxiliary;
use App\Models\PreOrder;
use App\Models\User;
use App\Models\PreOrderProduct;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getProduct($id){
        $product = Product::where('id', $id)->with('auxiliary')->get();
        $filter = Product::where('name', $product[0]->name)->with('auxiliary')->get();
        $products = Product::orderBy('price', 'asc')->where('auxiliary_id', $product[0]->auxiliary_id)->with('auxiliary')->get();

        // Eliminate duplicate auxiliary_id entries
        $available = $filter->unique('auxiliary_id');

        // return $product;
        return response()->json([
            'product' => $product, 
            'available' => $available,
            'products' => $products,
        ]);
    }

    
}
