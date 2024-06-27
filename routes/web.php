<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcwidController;
use App\Http\Controllers\Cin7Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fetch-orders', [EcwidController::class, 'fetchOrders']);
Route::get('/product-Ids', [EcwidController::class, 'fetchProductsWithOptions']);
Route::get('/product-variation', [EcwidController::class, 'fetchProductVariations']);
Route::get('/fetch-data', [Cin7Controller::class, 'getResponseFromEcwid']);
Route::get('/data', [Cin7Controller::class, 'createCustomer']);

Route::get('/firstdata', [Cin7Controller::class, 'createCustomerInternal']);



Route::get('/fetch', [EcwidController::class, 'fetchOrders']);


// Route::post('/push-orders-to-cin7', [EcwidController::class, 'pushToCin7']);


// Request to create customer
// Route::post('/create-customer', [Cin7Controller::class, 'createCustomer']);

// delete after testing
// Route::post('/test-post', [EcwidController::class, 'handlePostRequest']);

// routes/web.php
Route::get('/test', function () {
    return 'This is garry testing.!';
});


Route::get('/test2', function () {
    return 'This is garry testing.!';
});

Route::get('/test4', function () {
    return 'This is garry testing.!';
});



