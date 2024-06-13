<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PreOrder;
use App\Models\User;
use App\Models\PreOrderProduct;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Auxiliary;
use App\Models\Purchase;

class CashierPreOrderController extends Controller
{
    public function getPreOrders() {
        $preOrders = PreOrder::with('user')->with('preOrderProducts.product')->get();
        return $preOrders;
    }
    
    public function approvePreOrder(Request $request){
        // PreOrder::where('id', $request->id)->delete();
        // PreOrderProduct::where('pre_order_id', $request->id)->delete();
        // $preOrder =  PreOrder::where('id', $request->id)->with('preOrderProducts.product')->get();


        // $pruchase = new Purchase;
        // $pruchase->user_id = Auth::id();
        // $pruchase->product_id = $request->status;
        // $pruchase->save();
        $preOrder =  PreOrder::where('id', $request->id)->get();
        $preOrderProducts = PreOrderProduct::where('pre_order_id', $request->id)->with('product')->get();

        foreach ($preOrderProducts as $index => $preOrderProduct) {
            $pruchase = new Purchase;
            $pruchase->user_id = $preOrder[0]->user_id;
            $pruchase->product_id = $preOrderProduct->product_id;
            $pruchase->status = $preOrder[0]->status;
            $pruchase->price = $preOrderProduct->product->price;
            $pruchase->quantity = $preOrderProduct->quantity;
            $pruchase->save();
        }

        PreOrder::where('id', $request->id)->delete();
        PreOrderProduct::where('pre_order_id', $request->id)->delete();

        return $request->id;
    }
}
