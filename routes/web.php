<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcwidController;

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
Route::post('/push-orders-to-cin7', [EcwidController::class, 'pushToCin7']);


// routes/web.php
Route::get('/test', function () {
    return 'This is garry testing.!';
});

