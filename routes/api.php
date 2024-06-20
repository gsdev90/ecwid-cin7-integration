<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcwidController;
use App\Http\Controllers\Cin7Controller;
use App\Http\Controllers\Ecwid_Cin7Controller; // for presentation controller
use App\Http\Controllers\Ecwid_Cin7UpdateController;
use App\Http\Controllers\NewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// fetch All order from Ecwid
Route::get('/fetch-orders', [EcwidController::class, 'fetchOrders']);

// fetch first order
Route::get('/fetch-first-order', [EcwidController::class, 'fetchOrders'])->defaults('type', 'first'); // First order

// create customer to cin7
Route::post('/create-customer', [Cin7Controller::class, 'createCustomer']);

// create sale through localhost
Route::post('/Create-Sale-Through-Localhost', [Cin7Controller::class, 'createSale']);

Route::post('/create-customer-for-cin7', [Cin7Controller::class, 'createCustomerForCin7']);

Route::get('/test', function () {
    return response()->json(['status' => 'ok']);
});


Route::get('/fetch', [EcwidController::class, 'fetchOrders']);


// New controller for ecwid_cinController
Route::get('/Fetch-All-Orders', [Ecwid_Cin7Controller::class, 'fetchOrders']);

// Route::get('/garry', [Ecwid_Cin7Controller::class, 'garry']);


Route::get('/All-Sale-Order', [Ecwid_Cin7UpdateController::class, 'fetchOrder']);


// 20-06-2024 garryController
Route::get('/All-Orders', [NewController::class, 'fetchOrder']);
    


