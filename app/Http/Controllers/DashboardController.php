<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Auxiliary;

class DashboardController extends Controller
{
    public function getDashboardProducts(){
        $products = Product::latest()->with('auxiliary')->get();
        return $products;
    }

    public function sortBy($id){
        if($id == 1){
            $products = Product::orderBy('price', 'asc')->where('is_available', 1)->with('auxiliary')->get();
        };
        if($id == 2){
            $products = Product::orderBy('price', 'desc')->where('is_available', 1)->with('auxiliary')->get();
        };
        if($id == 3){
            $products = Product::orderBy('id', 'desc')->where('is_available', 1)->with('auxiliary')->get();
        };
        return $products;
    }

    public function filter(Request $request){
        $products = Product::orderBy('id', 'desc')->where('is_available', 1)->with('auxiliary')->get();
        return $request;
    }


}
