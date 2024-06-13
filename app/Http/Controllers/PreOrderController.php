<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreOrder;
use App\Models\User;
use App\Models\PreOrderProduct;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Auxiliary;

class PreOrderController extends Controller
{
    public function getPreOrder(){
        $preOrders = PreOrder::where('user_id', Auth::id())->with('user')->with('preOrderProducts.product')->get();
        return $preOrders ;
    }

    public function placeOrder(Request $request){

        $validated = $request->validate([
            'payment' => 'required',
            'product_id' => 'required',    
            'quantity' => 'required',    
            'status' => 'required',          
        ]);

        $preOrder = new PreOrder;
        $preOrder->user_id = Auth::id();
        $preOrder->status = $request->status;
        if($preOrder->save()){
            $preOrderProduct = new PreOrderProduct;
            $preOrderProduct->pre_order_id = $preOrder->id;
            $preOrderProduct->product_id = $request->product_id;
            $preOrderProduct->quantity = $request->quantity;
            if($preOrderProduct->save()){
                return  $preOrder->id;
            }
        }
    }

    public function deletePreOrder(Request $request){
        PreOrder::where('id', $request->id)->delete();
        PreOrderProduct::where('pre_order_id', $request->id)->delete();

        return $request->id;
    }
}
