<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Auxiliary;
use App\Models\PreOrder;
use App\Models\User;
use App\Models\PreOrderProduct;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use DB;

class PurchaseController extends Controller
{
    public function getHistoryData(){
        $averagePricesByDay = Purchase::selectRaw('DATE(created_at) as day, AVG(price) as average_price')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();
        foreach($averagePricesByDay as $index => $ave){
            $ave->date = explode('-',$ave->day); 
        };

        $historyOrder = Purchase::where('user_id', Auth::id())->orderBy('created_at', 'ASC')->with('product')->get();

        return response()->json([
            'data'  =>  $averagePricesByDay,
            'historyOrder' =>  $historyOrder
        ]);
    }
}
