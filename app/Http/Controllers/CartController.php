<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Auxiliary;
use App\Models\PreOrder;
use App\Models\CartItems;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\PreOrderProduct;
use DB;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $validated = $request->validate([
            'product_id' => 'required',    
            'quantity' => 'required',    
            'auxiliary_id' => 'required',          
        ]);

        $addToCart = new CartItems;
        $addToCart->user_id = Auth::id();
        $addToCart->product_id = $request->product_id;
        $addToCart->auxiliary_id = $request->auxiliary_id;
        $addToCart->quantity = $request->quantity;
        $addToCart->save();

        return $addToCart;
    }

    public function getCartItems() {
        $cartItems = CartItems::where('user_id', Auth::id())->with('user')->with('product')->get();
        return $cartItems;
    }

    public function cartToOrder(Request $request){
        $preOrder = new PreOrder;
        $preOrder->user_id = Auth::id();
        $preOrder->status = $request->status;
        $preOrder->status = $request->status;
        if($preOrder->save()){
            for($i = 0; $i <= count($request->ids)-1; $i++){
                $cart = CartItems::where('id', $request->ids[$i])->get();
                $preOrderProduct = new PreOrderProduct;
                $preOrderProduct->pre_order_id = $preOrder->id;
                $preOrderProduct->product_id = $cart[0]->product_id;
                $preOrderProduct->quantity = $cart[0]->quantity;
                if($preOrderProduct->save()){
                    CartItems::where('id', $request->ids[$i])->delete();
                }
            };  
        }
        return response()->json([
            'qrid' => $preOrder->id,
            'ids' => $request->ids
        ]);
    }

    public function getCartItemEdit($id){
        $changeCartItem = CartItems::where('user_id', Auth::id())->with('user')->with('product.auxiliary')->get();
        return $changeCartItem;
    }

    public function changeCartItemUpdate(Request $request){
        DB::table('cart_items')
        ->where('id', $request->id)
        ->update([
        'quantity' => $request->quantity
        ]);
        return $request->quantity;
    }
}
