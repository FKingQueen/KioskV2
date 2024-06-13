<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Cashier\CashierPreOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Dashboard
Route::get('/getDashboardProducts', [DashboardController::class, 'getDashboardProducts']);
Route::get('/sortBy/{id}', [DashboardController::class, 'sortBy']);
Route::get('/filter', [DashboardController::class, 'filter']);

// Product Info
Route::get('/getProduct/{id}', [ProductController::class, 'getProduct']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/regEmail', [AuthController::class, 'regEmail']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/placeOrder', [PreOrderController::class, 'placeOrder']);
    Route::get('/getPreOrder', [PreOrderController::class, 'getPreOrder']);
    Route::post('/deletePreOrder', [PreOrderController::class, 'deletePreOrder']);
    // 
    Route::post('/addToCart', [CartController::class, 'addToCart']);
    Route::post('/cartToOrder', [CartController::class, 'cartToOrder']);
    Route::get('/getCartItems', [CartController::class, 'getCartItems']);
    Route::get('/getCartItemEdit/{id}', [CartController::class, 'getCartItemEdit']);
    Route::post('/changeCartItemUpdate', [CartController::class, 'changeCartItemUpdate']);

    Route::get('/getHistoryData', [PurchaseController::class, 'getHistoryData']);

    // Add other authenticated routes here

    // Cashier
    Route::get('/getPreOrders', [CashierPreOrderController::class, 'getPreOrders']);
    Route::post('/approvePreOrder', [CashierPreOrderController::class, 'approvePreOrder']);
});

// Dashboard

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
