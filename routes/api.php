<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcwidController;
use App\Http\Controllers\Cin7Controller;

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

Route::post('/create-customer-for-cin7', [Cin7Controller::class, 'createCustomerForCin7']);
